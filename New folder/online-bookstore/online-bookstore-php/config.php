<?php
session_start();

if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(16));
}

function clean($val) {
    return htmlspecialchars((string)$val, ENT_QUOTES, 'UTF-8');
}

function check_csrf() {
    if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['token']) {
        die('Request token mismatch.');
    }
}

function must_login() {
    if (empty($_SESSION['account_id'])) {
        $_SESSION['msg_error'] = 'Please sign in first.';
        header('Location: login.php');
        exit;
    }
}

function is_admin() {
    return !empty($_SESSION['account_role']) && $_SESSION['account_role'] === 'admin';
}

function must_admin() {
    if (empty($_SESSION['account_id'])) {
        $_SESSION['msg_error'] = 'Please sign in first.';
        header('Location: login.php');
        exit;
    }
    if (!is_admin()) {
        $_SESSION['msg_error'] = 'Access restricted to administrators.';
        header('Location: index.php');
        exit;
    }
}

// Database configuration (MySQL configured for InfinityFree or local development)
$is_online = (strpos($_SERVER['HTTP_HOST'] ?? '', 'infinityfree') !== false || strpos($_SERVER['HTTP_HOST'] ?? '', '.rf.gd') !== false);

if ($is_online) {
    // InfinityFree MySQL credentials
    $db_host = getenv('DB_HOST') ?: 'sql110.infinityfree.com';
    $db_name = getenv('DB_NAME') ?: 'if0_43525561_bookstore';
    $db_user = getenv('DB_USER') ?: 'if0_43525561';
    $db_pass = getenv('DB_PASS') ?: 'AGgvxo1dHa8I7';
    $db_port = (int)(getenv('DB_PORT') ?: 3306);
} else {
    // Local development credentials
    $db_host = getenv('DB_HOST') ?: 'localhost';
    $db_name = getenv('DB_NAME') ?: 'bookstore_db';
    $db_user = getenv('DB_USER') ?: 'root';
    $db_pass = getenv('DB_PASS') ?: '';
    $db_port = (int)(getenv('DB_PORT') ?: 3306);
}

$db = null;
$is_mysql = false;

// Check if MySQL server is reachable
$sock = @fsockopen($db_host, $db_port, $errno, $errstr, 0.5);
if ($sock) {
    fclose($sock);
    try {
        $dsn = "mysql:host={$db_host};port={$db_port};dbname={$db_name};charset=utf8mb4";
        $db = new PDO($dsn, $db_user, $db_pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        $is_mysql = true;
    } catch (PDOException $e) {
        $db = null;
    }
}

// Fallback to SQLite for local development when MySQL daemon is offline
if (!$db) {
    $sqlite_file = __DIR__ . '/database.sqlite';
    $db = new PDO("sqlite:" . $sqlite_file, null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    $is_mysql = false;
}

// Create tables if they do not exist
if ($is_mysql) {
    $db->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(150) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        role VARCHAR(20) DEFAULT 'customer'
    )");
    $db->exec("CREATE TABLE IF NOT EXISTS books (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(150) NOT NULL,
        author VARCHAR(100) NOT NULL,
        category VARCHAR(50) NOT NULL,
        price DECIMAL(8,2) NOT NULL,
        quantity INT NOT NULL,
        image_path VARCHAR(255) DEFAULT NULL
    )");
} else {
    $db->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(150) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        role VARCHAR(20) DEFAULT 'customer'
    )");
    $db->exec("CREATE TABLE IF NOT EXISTS books (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title VARCHAR(150) NOT NULL,
        author VARCHAR(100) NOT NULL,
        category VARCHAR(50) NOT NULL,
        price DECIMAL(8,2) NOT NULL,
        quantity INTEGER NOT NULL,
        image_path VARCHAR(255) DEFAULT NULL
    )");
}

// Ensure uploads directory exists
$upload_dir = __DIR__ . '/uploads';
if (!is_dir($upload_dir)) {
    @mkdir($upload_dir, 0777, true);
}

// Seed default administrator if users table is empty
$user_count = (int)$db->query("SELECT COUNT(*) FROM users")->fetchColumn();
if ($user_count === 0) {
    $admin_pw = password_hash('Admin@2026', PASSWORD_DEFAULT);
    $insUser = $db->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'admin')");
    $insUser->execute(['Store Admin', 'admin@bookstore.com', $admin_pw]);
}

// Seed initial books only if both books and users are fresh (prevents bug if admin deletes all books)
$book_count = (int)$db->query("SELECT COUNT(*) FROM books")->fetchColumn();
if ($book_count === 0 && $user_count === 0) {
    $initialBooks = [
        ['Modern PHP Programming', 'Josh Lockhart', 'Programming', 48.00, 14],
        ['SQL & Relational Databases', 'Chris Date', 'Database', 62.00, 9],
        ['Web Application Architecture', 'Leon Shklar', 'Software Engineering', 54.00, 11],
        ['Network Security Essentials', 'William Stallings', 'Networks', 58.00, 7],
        ['Data Structures & Algorithms', 'Robert Sedgewick', 'Algorithms', 65.00, 12],
        ['Cloud Computing Principles', 'Thomas Erl', 'Cloud Computing', 52.00, 8],
    ];
    $insBook = $db->prepare("INSERT INTO books (title, author, category, price, quantity) VALUES (?, ?, ?, ?, ?)");
    foreach ($initialBooks as $row) {
        $insBook->execute($row);
    }
}

function book_photo($item) {
    if (!empty($item['image_path'])) {
        return clean($item['image_path']);
    }
    $c = strtolower($item['category'] ?? '');
    if (strpos($c, 'database') !== false) {
        return 'images/cover-db.jpg';
    }
    if (strpos($c, 'software') !== false) {
        return 'images/cover-se.jpg';
    }
    if (strpos($c, 'network') !== false) {
        return 'images/cover-net.jpg';
    }
    if (strpos($c, 'algo') !== false) {
        return 'images/cover-algo.jpg';
    }
    if (strpos($c, 'cloud') !== false) {
        return 'images/cover-cloud.jpg';
    }
    return 'images/cover-prog.jpg';
}
