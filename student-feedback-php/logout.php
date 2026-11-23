<?php
require_once __DIR__ . '/config.php';
session_unset();
session_destroy();
session_start();
$_SESSION['flash_success'] = 'Logged out successfully.';
header('Location: index.php');
exit;
