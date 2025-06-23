<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

if (isset($_POST['task_id'])) {
    $stmt = $pdo->prepare("DELETE FROM todos WHERE id = ? AND user_id = ?");
    $stmt->execute([$_POST['task_id'], $_SESSION['user_id']]);
}

header("Location: dashboard.php");
exit;
