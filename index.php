<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil - ToDo App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='style.css' rel='stylesheet'>
</head>
<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">ToDoApp</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav" aria-controls="navbarNav"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <?php if (isset($_SESSION['role'])): ?>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="logout.php">Déconnexion</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Connexion</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Inscription</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Contenu principal -->
<div class="container mt-5 text-center">
    <h1 class="display-4 mb-4">Bienvenue sur ToDo App</h1>
    <p class="lead">Organisez vos tâches facilement avec notre application !</p>
    <div class="mt-4">
        <a href="register.php" class="btn btn-success btn-lg mx-2">Créer un compte</a>
        <a href="login.php" class="btn btn-outline-primary btn-lg mx-2">Se connecter</a>
    </div>
</div>

</body>
</html>
