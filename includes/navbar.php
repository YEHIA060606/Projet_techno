<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
  <div class="container">
    <!-- Logo avec redirection selon le rôle -->
    <a class="navbar-brand fw-bold" 
       href="<?= isset($_SESSION['role']) && $_SESSION['role'] === 'admin' ? 'admin_panel.php' : 'index.php' ?>">
        ToDoApp<?= isset($_SESSION['role']) && $_SESSION['role'] === 'admin' ? ' - Admin' : '' ?>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
          <!-- Liens Admin -->
          <li class="nav-item"><a class="nav-link" href="/Projet_techno/admin_panel.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="/Projet_techno/admin/admin_users.php">Utilisateurs</a></li>
          <li class="nav-item"><a class="nav-link" href="/Projet_techno/admin/admin_tasks.php">Tâches</a></li>
          <li class="nav-item"><a class="nav-link" href="/Projet_techno/admin/admin_teams.php">Équipes</a></li>
          <li class="nav-item"><a class="nav-link" href="/Projet_techno/stats.php">Statistiques</a></li>
        <?php elseif (isset($_SESSION['role'])): ?>
          <!-- Liens Utilisateur -->
          <li class="nav-item"><a class="nav-link" href="/Projet_techno/dashboard.php">Accueil</a></li>
          <li class="nav-item"><a class="nav-link" href="/Projet_techno/add_task.php">Ajouter une tâche</a></li>
          <li class="nav-item"><a class="nav-link" href="/Projet_techno/categories.php">Catégories</a></li>
          <li class="nav-item"><a class="nav-link" href="/Projet_techno/teams.php">Équipes</a></li>
          <li class="nav-item"><a class="nav-link" href="/Projet_techno/stats.php">Statistiques</a></li>
        <?php endif; ?>
      </ul>

      <!-- Bouton de déconnexion -->
<ul class="navbar-nav ms-auto">
  <li class="nav-item">
    <a class="nav-link text-danger" 
       href="<?= (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') ? '/Projet_techno/logout.php' : '/Projet_techno/logout.php' ?>">
      Déconnexion
    </a>
  </li>
</ul>
    </div>
  </div>
</nav> 