
<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
include 'includes/navbar.php';

$user_id = $_SESSION['user_id'];

// Récupération des tâches de l'utilisateur (ou des équipes)
$stmt = $pdo->prepare("
SELECT t.*, c.name AS category_name, u.email AS assigned_email
FROM todos t
LEFT JOIN categories c ON t.category_id = c.id
LEFT JOIN users u ON t.assigned_to = u.id
WHERE t.user_id = ?
ORDER BY t.created_at DESC
");
$stmt->execute([$user_id]);
$todos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <title>Dashboard</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link href='style.css' rel='stylesheet'>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card-custom {
            border-left: 5px solid #0d6efd;
            transition: 0.3s;
        }
        .card-custom:hover {
            transform: scale(1.02);
        }
        .badge-status {
            font-size: 0.9em;
        }
    </style>
</head>
<body>
<div class="container py-5">
    <h2 class="mb-4 text-center ">Tableau de bord - Mes Tâches</h2>

    <?php if (count($todos) === 0): ?>
        <div class="alert alert-warning text-center">Aucune tâche trouvée.</div>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php foreach ($todos as $todo): ?>
            <div class="col">
                <div class="card shadow-sm card-custom">
                    <div class="card-body">
                        <h5 class="card-title mb-1"><?= htmlspecialchars($todo['title']) ?></h5>
                        <span class="badge bg-info text-dark mb-2"><?= htmlspecialchars($todo['category_name']) ?></span>
                        <p class="mb-1"><strong>Assignée à :</strong> <?= $todo['assigned_email'] ?? '<em>Non assignée</em>' ?></p>
                        <p class="mb-1"><strong>Deadline :</strong> <?= $todo['deadline'] ?? '<em>Aucune</em>' ?></p>
                        <p>
                            <strong>Status :</strong> 
                            <span class="badge <?= $todo['is_done'] ? 'bg-success' : 'bg-secondary' ?> badge-status">
                                <?= $todo['is_done'] ? 'Terminée' : 'En attente' ?>
                            </span>
                        </p>
                        <div class="d-flex justify-content-between mt-3">
                            <?php if (!$todo['is_done']): ?>
                            <a href="mark_done.php?id=<?= $todo['id'] ?>" class="btn btn-outline-success btn-sm">✔ Terminer</a>
                            <?php endif; ?>
                            <a href="delete_task.php?id=<?= $todo['id'] ?>" class="btn btn-outline-danger btn-sm">🗑 Supprimer</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
