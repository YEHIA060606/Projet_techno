<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=taches.csv');

$output = fopen('php://output', 'w');
fputcsv($output, ['Titre', 'Description', 'Date Limite', 'Fait ?']);

$stmt = $pdo->prepare("SELECT title, description, deadline, is_done FROM todos WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, [
        $row['title'],
        $row['description'],
        $row['deadline'],
        $row['is_done'] ? 'Oui' : 'Non'
    ]);
}
fclose($output);
exit;
?>
