<?php
require_once __DIR__ . '/config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
}
session_unset();
session_destroy();
session_start();
$_SESSION['msg_ok'] = 'Signed out.';
header('Location: index.php');
exit;
