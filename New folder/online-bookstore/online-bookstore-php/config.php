<?php
// Start session
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
}

function e($str) {
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}

// CSRF check
function verify_csrf() {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token.');
    }
}

function require_login() {
    if (empty($_SESSION['user_id'])) {
        $_SESSION['flash_error'] = 'Please login first.';
        header('Location: login.php');
        exit;
    }
}

// Database connection
$is_online = (strpos($_SERVER['HTTP_HOST'] ?? '', 'infinityfree') !== false);
$db_host = 'sql207.infinityfree.com';
$db_name = 'if0_43505732_bookstore';
$db_user = 'if0_43505732';
$db_pass = getenv('DB_PASS') ?: '';

try {
    if ($is_online) {
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
    } else {
        $pdo = new PDO("sqlite:" . __DIR__ . "/database.sqlite");
    }
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
    die("Database error: " . e($ex->getMessage()));
}

// Create tables
if ($is_online) {
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(150) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL
    )");
    $pdo->exec("CREATE TABLE IF NOT EXISTS books (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(150) NOT NULL,
        author VARCHAR(100) NOT NULL,
        category VARCHAR(50) NOT NULL,
        price DECIMAL(8,2) NOT NULL,
        quantity INT NOT NULL,
        image_path VARCHAR(255) DEFAULT NULL
    )");
} else {
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(150) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL
    )");
    $pdo->exec("CREATE TABLE IF NOT EXISTS books (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title VARCHAR(150) NOT NULL,
        author VARCHAR(100) NOT NULL,
        category VARCHAR(50) NOT NULL,
        price DECIMAL(8,2) NOT NULL,
        quantity INTEGER NOT NULL,
        image_path VARCHAR(255) DEFAULT NULL
    )");
}

if ($pdo->query("SELECT COUNT(*) FROM books")->fetchColumn() == 0) {
    $hash = password_hash('123456', PASSWORD_DEFAULT);
    $stmtUser = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmtUser->execute(['Admin User', 'admin@bookstore.com', $hash]);

    $booksData = [
        ['PHP & MySQL Web Development', 'Luke Welling', 'Programming', 45.00, 12],
        ['Database System Concepts', 'Abraham Silberschatz', 'Database', 60.00, 8],
        ['Clean Code Handbook', 'Robert Martin', 'Software Engineering', 50.00, 15],
        ['Computer Networking', 'James Kurose', 'Networks', 55.00, 10],
    ];
    $stmtBook = $pdo->prepare("INSERT INTO books (title, author, category, price, quantity) VALUES (?, ?, ?, ?, ?)");
    foreach ($booksData as $b) {
        $stmtBook->execute($b);
    }
}

function get_book_image($b) {
    if (!empty($b['image_path'])) {
        return e($b['image_path']);
    }
    $cat = strtolower($b['category'] ?? '');
    if (strpos($cat, 'database') !== false) {
        return 'images/book-db.jpg';
    }
    if (strpos($cat, 'software') !== false) {
        return 'images/book-oop.jpg';
    }
    if (strpos($cat, 'network') !== false) {
        return 'images/book-net.jpg';
    }
    return 'images/book-web.jpg';
}
