<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
include '../includes/navbar.php';

$role = $pdo->prepare("SELECT role FROM users WHERE id = ?");
$role->execute([$_SESSION['user_id']]);
if ($role->fetchColumn() !== 'admin') exit("Accès refusé.");

// Supprimer tâche
if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM todos WHERE id = ?")->execute([$_GET['delete']]);
    header("Location: admin_tasks.php");
    exit;
}

$todos = $pdo->query("SELECT todos.*, users.email FROM todos JOIN users ON todos.user_id = users.id")->fetchAll();
?>

<div class="container py-5">
    <h3 class="mb-4">📋 Gestion des tâches</h3>
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Utilisateur</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($todos as $todo): ?>
                    <tr>
                        <td><?= $todo['id'] ?></td>
                        <td><?= htmlspecialchars($todo['title']) ?></td>
                        <td><?= htmlspecialchars($todo['email']) ?></td>
                        <td>
                            <?php
                            $status = isset($todo['status']) ? strtolower($todo['status']) : 'inconnu';
                            $badgeClass = match($status) {
                                'en cours' => 'badge bg-warning text-dark',
                                'terminé' => 'badge bg-success',
                                'en attente' => 'badge bg-secondary',
                                'inconnu' => 'badge bg-danger',
                                default => 'badge bg-info',
                            };
                            ?>
                            <span class="<?= $badgeClass ?>">
                                <?= ucfirst($status === 'inconnu' ? 'Statut inconnu' : $status) ?>
                            </span>
                        </td>
                        <td>
                            <a href="?delete=<?= $todo['id'] ?>" 
                               class="btn btn-sm btn-danger" 
                               onclick="return confirm('Supprimer tâche ?');" 
                               title="Supprimer tâche">
                               🗑 Supprimer
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

