<?php
session_start();
require_once 'includes/db.php';
$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // Récupérer l'utilisateur avec son rôle
    $stmt = $pdo->prepare("SELECT id, password_hash, role FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user["password_hash"])) {
        // Stocker les infos dans la session
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["role"] = $user["role"];

        // Rediriger selon le rôle
        if ($user["role"] === 'admin') {
            header('Location: admin_panel.php');
            exit;
        } else {
            header('Location: index.php');
            exit;
        }
    } else {
        $errors[] = "Email ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Connexion</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href='style.css' rel='stylesheet'>
</head>
<body class="bg-light">
  <div class="container mt-5">
    <div class="card shadow mx-auto" style="max-width: 500px;">
      <div class="card-body">
        <h3 class="card-title mb-4 text-center text-primary">Connexion</h3>
        <?php if (!empty($errors)): ?>
          <div class="alert alert-danger"><?= implode('<br>', $errors) ?></div>
        <?php endif; ?>
        <form method="POST">
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Mot de passe</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <button class="btn btn-primary w-100">Se connecter</button>
        </form>
        <p class="text-center mt-3">Pas de compte ? <a href="register.php">S'inscrire</a></p>
      </div>
    </div>
  </div>
</body>
</html>
