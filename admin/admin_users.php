<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
include '../includes/navbar.php';

// Vérification admin
$role = $pdo->prepare("SELECT role FROM users WHERE id = ?");
$role->execute([$_SESSION['user_id']]);
if ($role->fetchColumn() !== 'admin') exit("Accès refusé.");

// Supprimer utilisateur
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$_GET['delete']]);
    header("Location: admin_users.php");
    exit;
}

// Liste utilisateurs
$users = $pdo->query("SELECT * FROM users")->fetchAll();
?>

<div class="container py-5">
    <h3 class="mb-4">👥 Gestion des utilisateurs</h3>
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= ucfirst($user['role']) ?></td>
                        <td>
                            <?php if ($user['role'] !== 'admin'): ?>
                                <a href="?delete=<?= $user['id'] ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?');"
                                   title="Supprimer">
                                   🗑 Supprimer
                                </a>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
