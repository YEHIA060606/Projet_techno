<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["new_category"])) {
    $stmt = $pdo->prepare("INSERT INTO categories (user_id, name) VALUES (?, ?)");
    $stmt->execute([$user_id, trim($_POST["new_category"])]);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["title"], $_POST["category_id"])) {
    $stmt = $pdo->prepare("INSERT INTO todos (user_id, category_id, title, description) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $_POST["category_id"], $_POST["title"], $_POST["description"]]);
}

if (isset($_GET["done"])) {
    $stmt = $pdo->prepare("UPDATE todos SET is_done = 1 WHERE id = ? AND user_id = ?");
    $stmt->execute([$_GET["done"], $user_id]);
}

if (isset($_GET["delete"])) {
    $stmt = $pdo->prepare("DELETE FROM todos WHERE id = ? AND user_id = ?");
    $stmt->execute([$_GET["delete"], $user_id]);
}

$categories = $pdo->prepare("SELECT * FROM categories WHERE user_id = ?");
$categories->execute([$user_id]);

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
<body class="container py-4">
    <h2 class="mb-4">Bienvenue sur votre ToDo List</h2>
    <a href="logout.php" class="btn btn-danger mb-4">Se déconnecter</a>

    <div class="card p-3 mb-4">
        <h4>Ajouter une catégorie</h4>
        <form method="POST" class="d-flex gap-2">
            <input name="new_category" placeholder="Nom de la catégorie" class="form-control" required>
            <button class="btn btn-primary">Créer</button>
        </form>
    </div>

    <div class="card p-3 mb-4">
        <h4>Ajouter une tâche</h4>
        <form method="POST" class="row g-2">
            <div class="col-md-3"><input name="title" placeholder="Titre" class="form-control" required></div>
            <div class="col-md-3"><input name="description" placeholder="Description" class="form-control"></div>
            <div class="col-md-3">
                <select name="category_id" class="form-select" required>
                    <option value="">Catégorie</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3"><button class="btn btn-success">Ajouter</button></div>
        </form>
    </div>

    <h4>Vos tâches</h4>
    <ul class="list-group">
        <?php foreach ($todos as $todo): ?>
            <?php
            $stmt = $pdo->prepare("SELECT name FROM categories WHERE id = ? AND user_id = ?");
            $stmt->execute([$todo['category_id'], $user_id]);
            $cat = $stmt->fetch();
            $catName = $cat ? htmlspecialchars($cat['name']) : 'Aucune';
            ?>
            <li class="list-group-item d-flex justify-content-between">
                <div>
                    <strong><?= htmlspecialchars($todo['title']) ?></strong>
                    <small class="text-muted">(<?= htmlspecialchars($todo['description']) ?>) - [<?= $catName ?>]</small>
                    <?= $todo['is_done'] ? '<span class="badge bg-success">Fait</span>' : '' ?>
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
</body>
</html>