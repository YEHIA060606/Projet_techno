<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
include 'includes/navbar.php';

$user_id = $_SESSION['user_id'];
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    if (!empty($name)) {
        $stmt = $pdo->prepare("INSERT INTO categories (user_id, name) VALUES (?, ?)");
        $stmt->execute([$user_id, htmlspecialchars($name)]);
        $message = "<div class='alert alert-success mt-3'><i class='bi bi-check-circle'></i> Catégorie ajoutée avec succès.</div>";
    } else {
        $message = "<div class='alert alert-danger mt-3'><i class='bi bi-exclamation-triangle'></i> Veuillez entrer un nom valide.</div>";
    }
}

// Récupérer les catégories
$cats = $pdo->prepare("SELECT * FROM categories WHERE user_id = ?");
$cats->execute([$user_id]);
$categories = $cats->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Catégories</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link href="style.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
  <h3 class="mb-4"><i class="bi bi-tags"></i> Gestion des catégories</h3>

  <?= $message ?>

  <form method="POST" class="input-group mb-4 w-50">
    <input name="name" class="form-control" placeholder="Nouvelle catégorie" required>
    <button class="btn btn-primary" type="submit"><i class="bi bi-plus-circle"></i> Ajouter</button>
  </form>

  <?php if (count($categories) > 0): ?>
    <ul class="list-group shadow-sm w-50">
      <?php foreach ($categories as $c): ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <?= htmlspecialchars($c['name']) ?>
          <!-- Bouton pour supprimer ou modifier ici si souhaité -->
        </li>
      <?php endforeach; ?>
    </ul>
  <?php else: ?>
    <p class="text-muted">Aucune catégorie pour le moment.</p>
  <?php endif; ?>
</div>
</body>
</html>
