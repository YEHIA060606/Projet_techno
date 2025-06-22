<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

$user_id = $_SESSION['user_id'];
$data = [];

$stmt = $pdo->prepare("SELECT c.name AS category, COUNT(t.id) AS total FROM todos t
    LEFT JOIN categories c ON t.category_id = c.id
    WHERE t.user_id = ?
    GROUP BY c.name");
$stmt->execute([$user_id]);

$totalTasks = 0;
while ($row = $stmt->fetch()) {
    $totalTasks += $row['total'];
    $data[] = $row;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Statistiques</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</head>
<body class="bg-light">

<?php include 'includes/navbar.php'; ?>

<div class="container py-5">
    <h3 class="mb-4 text-center"><i class="bi bi-pie-chart-fill me-2"></i>Statistiques des tâches par catégorie</h3>

    <?php if ($totalTasks === 0): ?>
        <div class="alert alert-info text-center"><i class="bi bi-info-circle"></i> Aucune tâche enregistrée pour générer des statistiques.</div>
    <?php else: ?>
        <div class="row align-items-center justify-content-center">
            <!-- Graphique -->
            <div class="col-md-6 mb-4">
                <div id="chartContainer" style="height: 370px; width: 100%;"></div>
            </div>

            <!-- Légende -->
            <div class="col-md-5">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Répartition (%)</h5>
                        <?php
                        $colors = ['#2ecc71', '#3498db', '#f1c40f', '#e67e22', '#9b59b6', '#e74c3c'];
                        $i = 0;
                        foreach ($data as $row):
                            $pourcentage = round(($row['total'] / $totalTasks) * 100);
                            $color = $colors[$i % count($colors)];
                        ?>
                            <div class="d-flex align-items-center mb-2">
                                <div style="width: 16px; height: 16px; background-color: <?= $color ?>; margin-right: 10px;"></div>
                                <?= htmlspecialchars($row['category']) ?> : <strong><?= $pourcentage ?>%</strong>
                            </div>
                        <?php $i++; endforeach; ?>
                        <hr>
                        <div class="text-muted">Total : <?= $totalTasks ?> tâche<?= $totalTasks > 1 ? 's' : '' ?></div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
window.onload = function () {
    var chart = new CanvasJS.Chart("chartContainer", {
        animationEnabled: true,
        theme: "light2",
        title: { text: "Répartition des tâches" },
        data: [{
            type: "pie",
            indexLabel: "{label} - {y}",
            yValueFormatString: "#,##0",
            dataPoints: [
                <?php foreach ($data as $row): ?>
                { y: <?= $row['total'] ?>, label: "<?= addslashes($row['category']) ?>" },
                <?php endforeach; ?>
            ]
        }]
    });
    chart.render();
}
</script>

</body>
</html>
