<?php
require_once __DIR__ . '/config.php';
session_unset();
session_destroy();
session_start();
$_SESSION['msg_ok'] = 'Signed out.';
header('Location: index.php');
exit;
