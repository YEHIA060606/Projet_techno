<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
include 'includes/navbar.php';

// Vérifier que l'utilisateur est admin
$stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$role = $stmt->fetchColumn();
if ($role !== 'admin') {
    echo "<div class='container mt-5 alert alert-danger'>Accès refusé. Cette page est réservée à l'administrateur.</div>";
    exit;
}

// Actions possibles
if (isset($_GET["delete_user"])) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$_GET["delete_user"]]);
}

$users = $pdo->query("SELECT id, email, role FROM users ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$todos = $pdo->query("SELECT COUNT(*) FROM todos")->fetchColumn();
$categories = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <h3 class="mb-4">🎛 Panneau d'administration</h3>

    <div class="row text-center mb-4">
        <div class="col-md-4">
            <div class="card border-primary">
                <div class="card-body">
                    <h5 class="card-title">👥 Utilisateurs</h5>
                    <p class="card-text display-6"><?= count($users) ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-success">
                <div class="card-body">
                    <h5 class="card-title">🗂 Catégories</h5>
                    <p class="card-text display-6"><?= $categories ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-warning">
                <div class="card-body">
                    <h5 class="card-title">✅ Tâches</h5>
                    <p class="card-text display-6"><?= $todos ?></p>
                </div>
            </div>
        </div>
    </div>

    <h4 class="mb-3">Liste des utilisateurs</h4>
    <table class="table table-bordered table-hover bg-white">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= $u['id'] ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= $u['role'] ?></td>
                    <td>
                        <?php if ($u['role'] !== 'admin'): ?>
                        <a href="?delete_user=<?= $u['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer cet utilisateur ?')">🗑 Supprimer</a>
                        <?php else: ?>
                        <span class="text-muted">-</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>