<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
  <div class="container">
    <!-- Logo dynamique selon le rôle -->
    <a class="navbar-brand fw-bold" 
       href="<?= (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') ? '/Projet_techno/admin_panel.php' : '/Projet_techno/dashboard.php' ?>">
        ToDoApp<?= (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') ? ' - Admin' : '' ?>
    </a>

    <!-- Bouton responsive -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Liens dynamiques -->
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
          <!-- Liens pour l'admin -->
          <li class="nav-item"><a class="nav-link" href="/Projet_techno/admin_panel.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="/Projet_techno/admin/admin_users.php">Utilisateurs</a></li>
          <li class="nav-item"><a class="nav-link" href="/Projet_techno/admin/admin_tasks.php">Tâches</a></li>
          <li class="nav-item"><a class="nav-link" href="/Projet_techno/admin/admin_teams.php">Équipes</a></li>
          <li class="nav-item"><a class="nav-link" href="/Projet_techno/stats.php">Statistiques</a></li>

        <?php elseif (isset($_SESSION['role'])): ?>
          <!-- Liens pour utilisateur -->
          <li class="nav-item"><a class="nav-link" href="/Projet_techno/dashboard.php">Accueil</a></li>
          <li class="nav-item"><a class="nav-link" href="/Projet_techno/add_task.php">Ajouter une tâche</a></li>
          <li class="nav-item"><a class="nav-link" href="/Projet_techno/categories.php">Catégories</a></li>
          <li class="nav-item"><a class="nav-link" href="/Projet_techno/teams.php">Équipes</a></li>
          <li class="nav-item"><a class="nav-link" href="/Projet_techno/stats.php">Statistiques</a></li>
        <?php endif; ?>
      </ul>

      <!-- Déconnexion -->
      <ul class="navbar-nav ms-auto">
        <?php if (isset($_SESSION['role'])): ?>
          <li class="nav-item">
            <a class="nav-link text-danger" href="/Projet_techno/logout.php">Déconnexion</a>
          </li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="/Projet_techno/login.php">Connexion</a></li>
          <li class="nav-item"><a class="nav-link" href="/Projet_techno/register.php">Inscription</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
