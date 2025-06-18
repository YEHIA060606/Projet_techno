<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
include 'includes/navbar.php';

// Vérifie si l'utilisateur est admin
$stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
$stmt->execute([$_SESSION["user_id"]]);
$role = $stmt->fetchColumn();

if ($role === 'admin') {
    header('Location: admin_panel.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Ajouter une catégorie
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["new_category"])) {
    $stmt = $pdo->prepare("INSERT INTO categories (user_id, name) VALUES (?, ?)");
    $stmt->execute([$user_id, trim($_POST["new_category"])]);
}

// Ajouter une tâche
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["title"], $_POST["category_id"])) {
    $stmt = $pdo->prepare("INSERT INTO todos (user_id, category_id, title, description, deadline) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $_POST["category_id"], $_POST["title"], $_POST["description"], $_POST["deadline"]]);
}

// Marquer une tâche comme faite
if (isset($_GET["done"])) {
    $stmt = $pdo->prepare("UPDATE todos SET is_done = 1 WHERE id = ? AND user_id = ?");
    $stmt->execute([$_GET["done"], $user_id]);
}

// Supprimer une tâche
if (isset($_GET["delete"])) {
    $stmt = $pdo->prepare("DELETE FROM todos WHERE id = ? AND user_id = ?");
    $stmt->execute([$_GET["delete"], $user_id]);
}

// Récupérer les catégories
$categories = $pdo->prepare("SELECT * FROM categories WHERE user_id = ?");
$categories->execute([$user_id]);

// Récupérer les tâches
$todos = $pdo->prepare("SELECT * FROM todos WHERE user_id = ? ORDER BY created_at DESC");
$todos->execute([$user_id]);
$todos = $todos->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">

    <h3 class="mb-3">Ajouter une catégorie</h3>
    <form method="POST" class="d-flex gap-2 mb-4">
        <input name="new_category" class="form-control" placeholder="Nom de la catégorie" required>
        <button class="btn btn-primary">Créer</button>
    </form>

    <h3>Ajouter une tâche</h3>
    <form method="POST" class="row g-2 mb-4">
        <div class="col-md-3"><input name="title" class="form-control" placeholder="Titre" required></div>
        <div class="col-md-3"><input name="description" class="form-control" placeholder="Description"></div>
        <div class="col-md-2"><input type="date" name="deadline" class="form-control" required></div>
        <div class="col-md-3">
            <select name="category_id" class="form-select" required>
                <option value="">Catégorie</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-1"><button class="btn btn-success w-100">Ajouter</button></div>
    </form>

    <div class="d-flex justify-content-end gap-2 mb-4">
        <a href="export_csv.php" class="btn btn-outline-primary">📊 Export CSV</a>
        <a href="export_pdf.php" class="btn btn-outline-danger">📄 Export PDF</a>
    </div>

    <h3>Mes tâches</h3>
    <ul class="list-group">
        <?php foreach ($todos as $todo): ?>
            <?php
            $stmt = $pdo->prepare("SELECT name FROM categories WHERE id = ?");
            $stmt->execute([$todo['category_id']]);
            $cat = $stmt->fetchColumn();
            $late = ($todo['deadline'] && strtotime($todo['deadline']) < time() && !$todo['is_done']);
            ?>
            <li class="list-group-item d-flex justify-content-between align-items-center <?= $late ? 'list-group-item-danger' : '' ?>">
                <div>
                    <strong><?= htmlspecialchars($todo['title']) ?></strong>
                    <small class="text-muted">(<?= htmlspecialchars($todo['description']) ?>)</small>
                    <span class="badge bg-info"><?= htmlspecialchars($cat ?? 'Sans catégorie') ?></span>
                    <?php if ($todo['deadline']): ?>
                        <span class="badge bg-secondary">🗓 <?= $todo['deadline'] ?></span>
                    <?php endif; ?>
                    <?php if ($todo['is_done']) echo '<span class="badge bg-success">Terminée</span>'; ?>
                    <?php if ($late) echo '<span class="badge bg-danger">En retard</span>'; ?>
                </div>
                <div>
                    <?php if (!$todo['is_done']): ?>
                        <a href="?done=<?= $todo['id'] ?>" class="btn btn-sm btn-outline-success">✔</a>
                    <?php endif; ?>
                    <a href="?delete=<?= $todo['id'] ?>" class="btn btn-sm btn-outline-danger">🗑</a>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
</body>
</html>