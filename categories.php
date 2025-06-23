<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
include 'includes/navbar.php';

// Ajouter une catégorie
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
    $stmt = $pdo->prepare("INSERT INTO categories (user_id, name) VALUES (?, ?)");
    $stmt->execute([$_SESSION['user_id'], $_POST['name']]);
}

// Supprimer une catégorie
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_category_id'])) {
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ? AND user_id = ?");
    $stmt->execute([$_POST['delete_category_id'], $_SESSION['user_id']]);
}

// Récupérer les catégories de l'utilisateur
$cats = $pdo->prepare("SELECT * FROM categories WHERE user_id = ?");
$cats->execute([$_SESSION['user_id']]);
$categories = $cats->fetchAll();
?>
<link href='style.css' rel='stylesheet'>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container py-5">
  <h3>Catégories</h3>

  <!-- Formulaire d'ajout -->
  <form method="POST" class="input-group mb-3 w-50">
    <input name="name" class="form-control" placeholder="Nouvelle catégorie" required>
    <button class="btn btn-primary">Ajouter</button>
  </form>

  <!-- Liste des catégories -->
  <ul class="list-group w-50">
    <?php foreach ($categories as $c): ?>
      <li class="list-group-item d-flex justify-content-between align-items-center">
        <?= htmlspecialchars($c['name']) ?>
        <form method="POST" style="margin: 0;">
          <input type="hidden" name="delete_category_id" value="<?= $c['id'] ?>">
          <button class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cette catégorie ?')">🗑️</button>
        </form>
      </li>
    <?php endforeach; ?>
  </ul>
</div>
