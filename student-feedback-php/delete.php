<?php
require_once __DIR__ . '/config.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $id = (int)($_POST['id'] ?? 0);
    $stmt = $pdo->prepare("DELETE FROM feedbacks WHERE id = ?");
    $stmt->execute([$id]);
    $_SESSION['flash_success'] = 'Feedback deleted.';
}

header('Location: index.php');
exit;
