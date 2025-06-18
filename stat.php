<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
include 'includes/navbar.php';

$user_id = $_SESSION['user_id'];
$data = [];

$stmt = $pdo->prepare("SELECT c.name AS category, COUNT(t.id) AS total FROM todos t
    LEFT JOIN categories c ON t.category_id = c.id
    WHERE t.user_id = ?
    GROUP BY c.name");
$stmt->execute([$user_id]);
while ($row = $stmt->fetch()) {
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
</head>
<body class='container py-4'>
    <h3 class='mb-4'>Statistiques des tâches par catégorie</h3>
    <div id='chartContainer' style='height: 370px; width: 100%;'></div>
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
                    { y: <?= $row['total'] ?>, label: "<?= $row['category'] ?>" },
                    <?php endforeach; ?>
                ]
            }]
        });
        chart.render();
    }
    </script>
</body>
</html>