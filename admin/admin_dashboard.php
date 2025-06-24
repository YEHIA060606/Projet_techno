<?php
session_start();

if (!isset($_SESSION['logged_in'])) {
  header('Location: login.php');
  exit;
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Tableau de bord</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <h1>Tableau de bord</h1>
    <p>Bienvenue, <?php echo $_SESSION['username']; ?> !</p>
    <a href="../logout.php">Se déconnecter</a>
  </div>
</body>
</html>