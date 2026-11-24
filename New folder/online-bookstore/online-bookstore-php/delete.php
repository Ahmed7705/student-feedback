<?php
require_once __DIR__ . '/config.php';
must_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $book_id = (int)($_POST['id'] ?? 0);
    $del = $db->prepare("DELETE FROM books WHERE id = ?");
    $del->execute([$book_id]);
    $_SESSION['msg_ok'] = 'Book removed.';
}

header('Location: manage.php');
exit;
