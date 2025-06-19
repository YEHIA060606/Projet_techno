<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
include 'includes/navbar.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO categories (user_id, name) VALUES (?, ?)");
    $stmt->execute([$_SESSION['user_id'], $_POST['name']]);
}
$cats = $pdo->prepare("SELECT * FROM categories WHERE user_id = ?");
$cats->execute([$_SESSION['user_id']]);
$categories = $cats->fetchAll();
?>
<div class="container py-5">
<h3>Catégories</h3>
<form method="POST" class="input-group mb-3 w-50">
  <input name="name" class="form-control" placeholder="Nouvelle catégorie" required>
  <button class="btn btn-primary">Ajouter</button>
</form>
<ul class="list-group">
  <?php foreach ($categories as $c): ?><li class="list-group-item"><?= $c['name'] ?></li><?php endforeach; ?>
</ul>
</div>