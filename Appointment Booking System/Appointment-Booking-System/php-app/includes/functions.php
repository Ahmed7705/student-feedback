<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Verify CSRF token on POST
function verify_csrf(): void
{
    $token = $_POST['csrf_token'] ?? '';
    if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        http_response_code(403);
        die('Invalid request.');
    }
}

function set_flash(string $type, string $message): void
{
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message,
    ];
}

function get_flash(): ?array
{
    if (!isset($_SESSION['flash'])) {
        return null;
    }
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}

function get_time_slots(): array
{
    return [
        '09:00:00' => '09:00 AM',
        '10:00:00' => '10:00 AM',
        '11:00:00' => '11:00 AM',
        '13:00:00' => '01:00 PM',
        '14:00:00' => '02:00 PM',
        '15:00:00' => '03:00 PM',
    ];
}

function format_time(string $time): string
{
    $timestamp = strtotime($time);
    return $timestamp ? date('h:i A', $timestamp) : $time;
}

function format_date(string $date): string
{
    $timestamp = strtotime($date);
    return $timestamp ? date('D, M j, Y', $timestamp) : $date;
}

// Check if time slot is already taken
function is_slot_booked(PDO $conn, string $date, string $time, ?int $excludeId = null): bool
{
    if (strlen($time) === 5) {
        $time .= ':00';
    }

    if ($excludeId !== null) {
        $stmt = $conn->prepare(
            "SELECT id FROM appointments WHERE appointment_date = ? AND appointment_time = ? AND status = 'Booked' AND id != ? LIMIT 1"
        );
        $stmt->execute([$date, $time, $excludeId]);
    } else {
        $stmt = $conn->prepare(
            "SELECT id FROM appointments WHERE appointment_date = ? AND appointment_time = ? AND status = 'Booked' LIMIT 1"
        );
        $stmt->execute([$date, $time]);
    }

    return $stmt->fetch() !== false;
}

// Validate and save uploaded file
function handle_attachment_upload(array $file): array
{
    if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return ['path' => null, 'error' => null];
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['path' => null, 'error' => 'File upload failed.'];
    }

    if ($file['size'] > 2 * 1024 * 1024) {
        return ['path' => null, 'error' => 'File must be under 2 MB.'];
    }

    $allowedExt = ['pdf', 'png', 'jpg', 'jpeg', 'txt'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExt, true)) {
        return ['path' => null, 'error' => 'Allowed files: PDF, PNG, JPG, TXT.'];
    }

    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0755, true);
    }

    $safeName = 'doc_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $targetPath = UPLOAD_DIR . $safeName;

    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        return ['path' => null, 'error' => 'Could not save file.'];
    }

    return ['path' => $safeName, 'error' => null];
}

function log_booking_event(string $action, string $details): void
{
    $logDir = __DIR__ . '/../storage';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    $fp = fopen($logDir . '/bookings.log', 'a');
    if ($fp) {
        fwrite($fp, sprintf("[%s] %s: %s\n", date('Y-m-d H:i:s'), $action, $details));
        fclose($fp);
    }
}
