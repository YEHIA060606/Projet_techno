<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
include '../includes/navbar.php';

// Vérification de l'authentification
if (!isset($_SESSION['user_id'])) {
    exit("Utilisateur non connecté.");
}

// Vérification du rôle admin
$roleStmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
$roleStmt->execute([$_SESSION['user_id']]);
if ($roleStmt->fetchColumn() !== 'admin') {
    exit("Accès refusé.");
}

// Supprimer une équipe si l'ID est valide
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $teamId = (int)$_GET['delete'];

    try {
        $pdo->beginTransaction();

        // Suppression ordonnée pour respecter les contraintes
        $pdo->prepare("DELETE FROM todos WHERE team_id = ?")->execute([$teamId]);
        $pdo->prepare("DELETE FROM team_members WHERE team_id = ?")->execute([$teamId]);
        $pdo->prepare("DELETE FROM teams WHERE id = ?")->execute([$teamId]);

        $pdo->commit();
        header("Location: admin_teams.php");
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        exit("Erreur lors de la suppression : " . $e->getMessage());
    }
}

// Récupérer les équipes
$teams = $pdo->query("SELECT * FROM teams ORDER BY id ASC")->fetchAll();
?>

<div class="container py-5">
    <h3 class="mb-4">🧑‍🤝‍🧑 Gestion des équipes</h3>
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($teams): ?>
                    <?php foreach ($teams as $team): ?>
                        <tr>
                            <td><?= $team['id'] ?></td>
                            <td><?= htmlspecialchars($team['name']) ?></td>
                            <td>
                                <a href="?delete=<?= $team['id'] ?>"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Supprimer l\'équipe <?= htmlspecialchars(addslashes($team['name'])) ?> ? Cette action est irréversible.');"
                                   title="Supprimer équipe">
                                   🗑 Supprimer
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="3" class="text-center">Aucune équipe trouvée.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
