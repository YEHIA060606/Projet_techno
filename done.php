<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

if (isset($_POST['task_id'])) {
    $stmt = $pdo->prepare("UPDATE todos SET is_done = 1 WHERE id = ? AND user_id = ?");
    $stmt->execute([$_POST['task_id'], $_SESSION['user_id']]);
}
header("Location: dashboard.php");
exit;
