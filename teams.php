<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
include 'includes/navbar.php';

$user_id = $_SESSION['user_id'];
$message = "";

// Créer une équipe
if (isset($_POST['team_name'])) {
    $team_name = trim($_POST['team_name']);
    if ($team_name) {
        $stmt = $pdo->prepare("INSERT INTO teams (name, owner_id) VALUES (?, ?)");
        $stmt->execute([$team_name, $user_id]);
        $team_id = $pdo->lastInsertId();
        $pdo->prepare("INSERT INTO team_members (team_id, user_id) VALUES (?, ?)")->execute([$team_id, $user_id]);
        $message = "✅ Équipe créée.";
    }
}

// Ajouter un membre
if (isset($_POST['add_user_email'], $_POST['team_id'])) {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([trim($_POST['add_user_email'])]);
    if ($user = $stmt->fetch()) {
        $stmt = $pdo->prepare("INSERT IGNORE INTO team_members (team_id, user_id) VALUES (?, ?)");
        $stmt->execute([$_POST['team_id'], $user['id']]);
        $message = "👤 Membre ajouté.";
    } else {
        $message = "❌ Utilisateur introuvable.";
    }
}

// Supprimer une équipe
if (isset($_POST['delete_team_id'])) {
    $team_id = $_POST['delete_team_id'];

    // Vérifie que l'équipe appartient à l'utilisateur connecté
    $stmt = $pdo->prepare("SELECT * FROM teams WHERE id = ? AND owner_id = ?");
    $stmt->execute([$team_id, $user_id]);
    if ($stmt->fetch()) {
        // Supprimer les membres de l'équipe
        $pdo->prepare("DELETE FROM team_members WHERE team_id = ?")->execute([$team_id]);
        // Supprimer l'équipe
        $pdo->prepare("DELETE FROM teams WHERE id = ?")->execute([$team_id]);
        $message = "🗑️ Équipe supprimée.";
    } else {
        $message = "❌ Vous n'avez pas le droit de supprimer cette équipe.";
    }
}

// Récupérer les équipes
$stmt = $pdo->prepare("SELECT * FROM teams WHERE owner_id = ?");
$stmt->execute([$user_id]);
$teams = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Équipes</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href='style.css' rel='stylesheet'>
  <style>
    .team-board {
        display: flex;
        flex-wrap: nowrap;
        overflow-x: auto;
        gap: 20px;
        padding-top: 20px;
        padding-bottom: 20px;
    }
    .team-card {
        min-width: 300px;
        flex: 0 0 auto;
    }
  </style>
</head>
<body> 
<div class="container py-4">
  <h3 class="mb-4">Gestion des équipes</h3>
  <?php if ($message): ?><div class="alert alert-info"><?= $message ?></div><?php endif; ?>

  <form method="POST" class="input-group mb-4 w-50">
      <input name="team_name" class="form-control" placeholder="Nom de l'équipe" required>
      <button class="btn btn-success">Créer</button>
  </form>

  <div class="team-board">
  <?php foreach ($teams as $team): ?>
    <div class="card team-card">
      <div class="card-header bg-primary text-white"><?= htmlspecialchars($team['name']) ?></div>
      <div class="card-body">
        <h6>Membres :</h6>
        <ul>
          <?php
          $stmt = $pdo->prepare("SELECT u.email FROM team_members tm JOIN users u ON tm.user_id = u.id WHERE tm.team_id = ?");
          $stmt->execute([$team['id']]);
          foreach ($stmt as $member) echo "<li>{$member['email']}</li>";
          ?>
        </ul>
        <form method="POST" class="input-group mt-3">
          <input type="hidden" name="team_id" value="<?= $team['id'] ?>">
          <input name="add_user_email" class="form-control" placeholder="Email utilisateur">
          <button class="btn btn-outline-primary">Ajouter</button>
        </form>

        <!-- Formulaire de suppression -->
        <form method="POST" class="mt-2">
          <input type="hidden" name="delete_team_id" value="<?= $team['id'] ?>">
          <button class="btn btn-danger w-100" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette équipe ?')">Supprimer</button>
        </form>
      </div>
    </div>
  <?php endforeach; ?>
  </div>
</div>
</body>
</html>

