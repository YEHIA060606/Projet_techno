<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="dashboard.php">ToDoApp</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="dashboard.php">Accueil</a></li>
        <li class="nav-item"><a class="nav-link" href="add_task.php">Ajouter une tâche</a></li>
        <li class="nav-item"><a class="nav-link" href="categories.php">Catégories</a></li>
        <li class="nav-item"><a class="nav-link" href="teams.php">Équipes</a></li>
        <li class="nav-item"><a class="nav-link" href="stats.php">Statistiques</a></li>
      </ul>
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Déconnexion</a></li>
      </ul>
    </div>
  </div>
</nav>
