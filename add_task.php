<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
include 'includes/navbar.php';

$user_id = $_SESSION['user_id'];
$message = "";

// Récupérer les catégories de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM categories WHERE user_id = ?");
$stmt->execute([$user_id]);
$categories = $stmt->fetchAll();

// Récupérer les membres de l’équipe
$stmt = $pdo->prepare("SELECT u.id, u.email FROM team_members tm 
                       JOIN users u ON tm.user_id = u.id 
                       WHERE tm.team_id IN 
                         (SELECT team_id FROM team_members WHERE user_id = ?)");
$stmt->execute([$user_id]);
$team_members = $stmt->fetchAll();

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = htmlspecialchars(trim($_POST['title']));
    $description = htmlspecialchars(trim($_POST['description']));
    $category_id = intval($_POST['category_id']);
    $assigned_to = !empty($_POST['assigned_to']) ? intval($_POST['assigned_to']) : null;
    $deadline = $_POST['deadline'];

    if (!empty($title) && !empty($category_id) && !empty($deadline)) {
        $stmt = $pdo->prepare("INSERT INTO todos (user_id, category_id, title, description, assigned_to, deadline, created_at)
                               VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$user_id, $category_id, $title, $description, $assigned_to, $deadline]);
        $message = "<div class='alert alert-success'><i class='bi bi-check-circle'></i> Tâche ajoutée avec succès !</div>";
    } else {
        $message = "<div class='alert alert-danger'><i class='bi bi-exclamation-triangle'></i> Merci de remplir tous les champs obligatoires.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Ajouter une tâche</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link href="style.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-7">
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
          <h4 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Nouvelle Tâche</h4>
        </div>
        <div class="card-body bg-light">
          <?= $message ?>
          <form method="POST">
            <div class="mb-3">
              <label class="form-label">Titre <span class="text-danger">*</span></label>
              <input type="text" name="title" class="form-control" placeholder="Titre de la tâche" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Description</label>
              <textarea name="description" class="form-control" rows="3" placeholder="Description de la tâche..."></textarea>
            </div>

            <div class="mb-3">
              <label class="form-label">Catégorie <span class="text-danger">*</span></label>
              <select name="category_id" class="form-select" required>
                <option value="">-- Choisir une catégorie --</option>
                <?php foreach ($categories as $cat): ?>
                  <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Assigner à</label>
              <select name="assigned_to" class="form-select">
                <option value="">-- Aucun / Moi-même --</option>
                <?php foreach ($team_members as $member): ?>
                  <option value="<?= $member['id'] ?>"><?= htmlspecialchars($member['email']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Date limite <span class="text-danger">*</span></label>
              <input type="date" name="deadline" class="form-control" required>
            </div>

            <div class="d-grid">
              <button class="btn btn-success"><i class="bi bi-check-circle me-1"></i> Ajouter la tâche</button>
            </div>
          </form>
        </div>
      </div>
      <div class="text-muted text-end mt-2" style="font-size: 0.9em;">* Champs obligatoires</div>
    </div>
  </div>
</div>
</body>
</html>