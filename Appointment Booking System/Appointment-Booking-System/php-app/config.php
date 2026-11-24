<?php
$isLocal = in_array($_SERVER['HTTP_HOST'] ?? 'localhost', ['localhost', '127.0.0.1', '127.0.0.1:9007', 'localhost:9007'], true)
    || str_starts_with($_SERVER['HTTP_HOST'] ?? '', '127.0.0.1:')
    || str_starts_with($_SERVER['HTTP_HOST'] ?? '', 'localhost:');

define('DB_HOST', $isLocal ? 'localhost' : 'sql207.infinityfree.com');
define('DB_USER', $isLocal ? 'root' : 'if0_43505732');
define('DB_PASS', $isLocal ? '' : (getenv('DB_PASS') ?: 'YOUR_VPANEL_PASSWORD'));
define('DB_NAME', $isLocal ? 'appointment_system' : 'if0_43505732_advisingappointments');
define('SQLITE_PATH', __DIR__ . '/storage/database.sqlite');

define('SITE_TITLE', 'Academic Advising Appointments');
define('UPLOAD_DIR', __DIR__ . '/uploads/');

$pdoOptions = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

function seed_initial_data(PDO $pdo): void
{
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

function init_mysql_schema(PDO $pdo): void
{
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(120) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS appointments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            customer_name VARCHAR(100) NOT NULL,
            email VARCHAR(120) NOT NULL,
            appointment_date DATE NOT NULL,
            appointment_time TIME NOT NULL,
            status ENUM('Booked', 'Completed', 'Cancelled') NOT NULL DEFAULT 'Booked',
            attachment VARCHAR(255) DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");

    seed_initial_data($pdo);
}

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

    seed_initial_data($pdo);
}

// Database connection (MySQL on InfinityFree, SQLite fallback locally)
$conn = null;

try {
    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', DB_HOST, DB_NAME);
    $conn = new PDO($dsn, DB_USER, DB_PASS, $pdoOptions);
    init_mysql_schema($conn);
} catch (PDOException $e) {
    if (!$isLocal) {
        die('Database connection failed.');
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
