<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

require_admin();

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="appointments_' . date('Y-m-d') . '.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['ID', 'Name', 'Email', 'Date', 'Time', 'Status']);

$stmt = $conn->prepare(
    'SELECT id, customer_name, email, appointment_date, appointment_time, status
     FROM appointments
     ORDER BY appointment_date ASC, appointment_time ASC'
);
$stmt->execute();

while ($row = $stmt->fetch()) {
    fputcsv($output, [
        $row['id'],
        $row['customer_name'],
        $row['email'],
        $row['appointment_date'],
        $row['appointment_time'],
        $row['status'],
    ]);
}

fclose($output);
exit;
