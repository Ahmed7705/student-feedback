<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function is_logged_in(): bool
{
    return isset($_SESSION['user_id']);
}

function is_admin(): bool
{
    return is_logged_in() && (($_SESSION['user_role'] ?? 'user') === 'admin');
}

function current_user_name(): string
{
    return $_SESSION['user_name'] ?? '';
}

function current_user_email(): string
{
    return $_SESSION['user_email'] ?? '';
}

function require_login(): void
{
    if (!is_logged_in()) {
        set_flash('error', 'Please sign in first.');
        header('Location: login.php');
        exit;
    }
}

function require_admin(): void
{
    require_login();
    if (!is_admin()) {
        http_response_code(403);
        set_flash('error', 'Admin access required.');
        header('Location: manage.php');
        exit;
    }
}
