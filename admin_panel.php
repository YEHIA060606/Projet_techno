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

// Supprimer un utilisateur (sauf admin)
if (isset($_GET["delete_user"])) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$_GET["delete_user"]]);
}

// Modifier une catégorie
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_cat_id']) && isset($_POST['new_name'])) {
    $stmt = $pdo->prepare("UPDATE categories SET name = ? WHERE id = ?");
    $stmt->execute([$_POST['new_name'], $_POST['edit_cat_id']]);
}

// Supprimer une catégorie
if (isset($_GET["delete_cat"])) {
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$_GET["delete_cat"]]);
}

// Données
$users = $pdo->query("SELECT id, email, role FROM users ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$todos = $pdo->query("SELECT COUNT(*) FROM todos")->fetchColumn();
$categories = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$allCats = $pdo->query("SELECT categories.id, categories.name, users.email FROM categories JOIN users ON categories.user_id = users.id")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Panneau Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <h3 class="mb-4">🎛 Panneau d'administration</h3>

    <!-- Statistiques -->
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

    <!-- Liste des utilisateurs -->
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

    <!-- Gestion des catégories -->
    <h4 class="mt-5 mb-3">🗂 Gérer les catégories</h4>
    <table class="table table-bordered bg-white">
        <thead class="table-secondary">
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Créé par</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($allCats as $cat): ?>
                <tr>
                    <td><?= $cat['id'] ?></td>
                    <td>
                        <form method="POST" class="d-flex">
                            <input type="hidden" name="edit_cat_id" value="<?= $cat['id'] ?>">
                            <input type="text" name="new_name" value="<?= htmlspecialchars($cat['name']) ?>" class="form-control me-2" required>
                            <button class="btn btn-sm btn-success">💾</button>
                        </form>
                    </td>
                    <td><?= htmlspecialchars($cat['email']) ?></td>
                    <td>
                        <a href="?delete_cat=<?= $cat['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer cette catégorie ?')">🗑 Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
