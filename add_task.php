
<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
include 'includes/navbar.php';

$user_id = $_SESSION['user_id'];
$message = "";

// Récupérer les catégories
$stmt = $pdo->prepare("SELECT * FROM categories WHERE user_id = ?");
$stmt->execute([$user_id]);
$categories = $stmt->fetchAll();

// Récupérer les membres d’équipe
$stmt = $pdo->prepare("SELECT u.id, u.email FROM team_members tm 
                       JOIN users u ON tm.user_id = u.id 
                       WHERE tm.team_id IN 
                         (SELECT team_id FROM team_members WHERE user_id = ?)");
$stmt->execute([$user_id]);
$team_members = $stmt->fetchAll();

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category_id = $_POST['category_id'];
    $assigned_to = !empty($_POST['assigned_to']) ? $_POST['assigned_to'] : null;
    $deadline = $_POST['deadline'];

    if ($title && $category_id && $deadline) {
        $stmt = $pdo->prepare("INSERT INTO todos (user_id, category_id, title, description, assigned_to, deadline, created_at)
                               VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$user_id, $category_id, $title, $description, $assigned_to, $deadline]);
        $message = "✅ Tâche ajoutée avec succès.";
    } else {
        $message = "❌ Merci de remplir tous les champs obligatoires.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Ajouter une tâche</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href='style.css' rel='stylesheet'>
</head>
<body>
<div class="container py-4 d-flex justify-content-center">
  <div class="col-md-6">
    <h3 class="mb-4 text-center">Ajouter une tâche</h3>
    <?php if ($message): ?><div class="alert alert-info"><?= $message ?></div><?php endif; ?>

    <form method="POST" class="p-4 border rounded shadow-sm bg-light">
      <input type="text" name="title" class="form-control mb-3" placeholder="Titre de la tâche" required>
      <textarea name="description" class="form-control mb-3" placeholder="Description (optionnelle)"></textarea>

      <select name="category_id" class="form-select mb-3" required>
        <option value="">-- Choisir une catégorie --</option>
        <?php foreach ($categories as $cat): ?>
          <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
        <?php endforeach; ?>
      </select>

      <select name="assigned_to" class="form-select mb-3">
        <option value="">-- Assigner à un membre --</option>
        <?php foreach ($team_members as $member): ?>
          <option value="<?= $member['id'] ?>"><?= htmlspecialchars($member['email']) ?></option>
        <?php endforeach; ?>
      </select>

      <input type="date" name="deadline" class="form-control mb-3" required>

      <button class="btn btn-primary w-100">Ajouter la tâche</button>
    </form>
  </div>
</div>
</body>
</html>
