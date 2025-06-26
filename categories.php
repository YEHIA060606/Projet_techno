<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
include 'includes/navbar.php';

// Ajouter une catégorie
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name']) && empty($_POST['edit_id'])) {
    $stmt = $pdo->prepare("INSERT INTO categories (user_id, name) VALUES (?, ?)");
    $stmt->execute([$_SESSION['user_id'], $_POST['name']]);
}

// Modifier une catégorie
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'], $_POST['new_name'])) {
    $stmt = $pdo->prepare("UPDATE categories SET name = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$_POST['new_name'], $_POST['edit_id'], $_SESSION['user_id']]);
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
        <span id="name-display-<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></span>

        <div class="d-flex">
          <!-- Formulaire de modification caché -->
          <form method="POST" class="d-flex me-2" id="edit-form-<?= $c['id'] ?>" style="display: none;">
            <input type="hidden" name="edit_id" value="<?= $c['id'] ?>">
            <input type="text" name="new_name" class="form-control form-control-sm me-1" value="<?= htmlspecialchars($c['name']) ?>" required>
            <button class="btn btn-sm btn-success">Valider </button>
          </form>

          <!-- Bouton Modifier -->
          <button class="btn btn-sm btn-outline-secondary me-2" onclick="toggleEdit(<?= $c['id'] ?>)">Modifier</button>

          <!-- Formulaire de suppression -->
          <form method="POST" onsubmit="return confirm('Supprimer cette catégorie ?')" style="margin: 0;">
            <input type="hidden" name="delete_category_id" value="<?= $c['id'] ?>">
            <button class="btn btn-sm btn-danger">Supprimer</button>
          </form>
        </div>
      </li>
    <?php endforeach; ?>
  </ul>
</div>

<script>
function toggleEdit(id) {
  const form = document.getElementById('edit-form-' + id);
  const nameDisplay = document.getElementById('name-display-' + id);
  if (form.style.display === 'none') {
    form.style.display = 'flex';
    nameDisplay.style.display = 'none';
  } else {
    form.style.display = 'none';
    nameDisplay.style.display = 'inline';
  }
}
</script>
