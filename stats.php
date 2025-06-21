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
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <title>Statistiques</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
    <script src='https://canvasjs.com/assets/script/canvasjs.min.js'></script>
    <link href='style.css' rel='stylesheet'>
</head>
<body class='bg-light'>

<?php include 'includes/navbar.php'; ?>

<div class='container py-4'>
    <h3 class='mb-4'>Statistiques des tâches par catégorie</h3>

    <!-- Zone graphique + légende -->
    <div style="display: flex; gap: 50px; align-items: center; justify-content: center;">

        <!-- Graphique -->
        <div id='chartContainer' style='height: 370px; width: 50%;'></div>

        <!-- Légende dynamique -->
        <div style='font-size: 18px;'>
            <?php
            // Palette de couleurs (à synchroniser avec CanvasJS si nécessaire)
            $colors = ['#2ecc71', '#3498db', '#f1c40f', '#e67e22', '#9b59b6', '#e74c3c'];
            $i = 0;
            foreach ($data as $row):
                $pourcentage = $totalTasks > 0 ? round(($row['total'] / $totalTasks) * 100) : 0;
                $color = $colors[$i % count($colors)];
            ?>
                <div style="display: flex; align-items: center; margin-bottom: 10px;">
                    <div style="width: 20px; height: 20px; background-color: <?= $color ?>; margin-right: 10px;"></div>
                    <?= htmlspecialchars($row['category']) ?> : <?= $pourcentage ?>%
                </div>
            <?php $i++; endforeach; ?>
        </div>
    </div>
</div>

<script>
window.onload = function () {
    var chart = new CanvasJS.Chart('chartContainer', {
        animationEnabled: true,
        theme: 'light2',
        title: { text: 'Répartition des tâches' },
        data: [{
            type: 'pie',
            indexLabel: '{label} - {y}',
            yValueFormatString: '#,##0',
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