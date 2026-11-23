<?php
define('DB_DRIVER', 'auto');
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'appointment_system');
define('SQLITE_PATH', __DIR__ . '/storage/database.sqlite');

define('SITE_TITLE', 'Academic Advising Appointments');
define('UPLOAD_DIR', __DIR__ . '/uploads/');

$pdoOptions = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

function init_sqlite_schema(PDO $pdo): void
{
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(120) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            role VARCHAR(20) NOT NULL DEFAULT 'user',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");

    $cols = $pdo->query("PRAGMA table_info(users)")->fetchAll();
    $hasRole = false;
    foreach ($cols as $col) {
        if ($col['name'] === 'role') {
            $hasRole = true;
            break;
        }
    }
    if (!$hasRole) {
        $pdo->exec("ALTER TABLE users ADD COLUMN role VARCHAR(20) NOT NULL DEFAULT 'user'");
        $pdo->exec("UPDATE users SET role = 'admin' WHERE email = 'admin@jazanu.edu.sa'");
    }

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS appointments (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            customer_name VARCHAR(100) NOT NULL,
            email VARCHAR(120) NOT NULL,
            appointment_date DATE NOT NULL,
            appointment_time TIME NOT NULL,
            status VARCHAR(20) NOT NULL DEFAULT 'Booked',
            attachment VARCHAR(255) DEFAULT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");

    $userCount = (int) $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
    if ($userCount === 0) {
        $stmt = $pdo->prepare('INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)');
        $stmt->execute([
            'Academic Advisor',
            'admin@jazanu.edu.sa',
            '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'admin',
        ]);
    }

    $apptCount = (int) $pdo->query('SELECT COUNT(*) FROM appointments')->fetchColumn();
    if ($apptCount === 0) {
        $stmt = $pdo->prepare(
            'INSERT INTO appointments (customer_name, email, appointment_date, appointment_time, status)
             VALUES (?, ?, ?, ?, ?)'
        );
        $today = date('Y-m-d');
        $tomorrow = date('Y-m-d', strtotime('+1 day'));
        $dayAfter = date('Y-m-d', strtotime('+2 day'));

        $samples = [
            ['Ahmed Al-Qahtani', 'ahmed@student.jazanu.edu.sa', $tomorrow, '09:00:00', 'Booked'],
            ['Sara Al-Harbi', 'sara@student.jazanu.edu.sa', $tomorrow, '10:00:00', 'Booked'],
            ['Khalid Al-Zahrani', 'khalid@student.jazanu.edu.sa', $dayAfter, '11:00:00', 'Booked'],
            ['Noura Al-Ghamdi', 'noura@student.jazanu.edu.sa', $today, '09:00:00', 'Completed'],
            ['Faisal Al-Otaibi', 'faisal@student.jazanu.edu.sa', $dayAfter, '13:00:00', 'Cancelled'],
        ];
        foreach ($samples as $row) {
            $stmt->execute($row);
        }
    }
}

$conn = null;

// Database connection (MySQL on server, SQLite fallback locally)
if (DB_DRIVER === 'mysql' || DB_DRIVER === 'auto') {
    try {
        $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', DB_HOST, DB_NAME);
        $conn = new PDO($dsn, DB_USER, DB_PASS, $pdoOptions);
    } catch (PDOException $e) {
        if (DB_DRIVER === 'mysql') {
            die('Database connection failed.');
        }
    }
}

if ($conn === null) {
    $storageDir = dirname(SQLITE_PATH);
    if (!is_dir($storageDir)) {
        mkdir($storageDir, 0755, true);
    }
    $conn = new PDO('sqlite:' . SQLITE_PATH, null, null, $pdoOptions);
    init_sqlite_schema($conn);
}
