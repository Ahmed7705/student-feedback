<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

require_login();

$id = (int) ($_GET['id'] ?? 0);
if ($id <= 0) {
    http_response_code(404);
    exit('Not found.');
}

$stmt = $conn->prepare('SELECT email, attachment FROM appointments WHERE id = ? LIMIT 1');
$stmt->execute([$id]);
$row = $stmt->fetch();

if (!$row || empty($row['attachment'])) {
    http_response_code(404);
    exit('File not found.');
}

// Only admin or booking owner can view file
if (!is_admin() && strcasecmp($row['email'], current_user_email()) !== 0) {
    http_response_code(403);
    exit('Forbidden.');
}

$filename = basename($row['attachment']);
$fullPath = UPLOAD_DIR . $filename;

if (!is_file($fullPath)) {
    http_response_code(404);
    exit('File not found.');
}

$mime = mime_content_type($fullPath) ?: 'application/octet-stream';
header('Content-Type: ' . $mime);
header('Content-Disposition: inline; filename="' . $filename . '"');
header('Content-Length: ' . filesize($fullPath));
readfile($fullPath);
exit;
