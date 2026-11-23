<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

session_unset();
session_destroy();

session_start();
set_flash('success', 'Signed out.');
header('Location: index.php');
exit;
