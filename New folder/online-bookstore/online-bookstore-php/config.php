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

$remote = (strpos($_SERVER['HTTP_HOST'] ?? '', 'infinityfree') !== false);
$host = 'sql207.infinityfree.com';
$dbname = 'if0_43505732_bookstore';
$user = 'if0_43505732';
$pass = getenv('DB_PASS') ?: '';

try {
    if ($remote) {
        $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    } else {
        $db = new PDO("sqlite:" . __DIR__ . "/database.sqlite");
    }
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $err) {
    die("Connection error: " . clean($err->getMessage()));
}

if ($remote) {
    $db->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(150) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL
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
        password VARCHAR(255) NOT NULL
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

if ($db->query("SELECT COUNT(*) FROM books")->fetchColumn() == 0) {
    $pw = password_hash('123456', PASSWORD_DEFAULT);
    $insUser = $db->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $insUser->execute(['Store Admin', 'admin@bookstore.com', $pw]);

    $initialBooks = [
        ['Modern PHP Programming', 'Josh Lockhart', 'Programming', 48.00, 14],
        ['SQL & Relational Databases', 'Chris Date', 'Database', 62.00, 9],
        ['Web Application Architecture', 'Leon Shklar', 'Software Engineering', 54.00, 11],
        ['Network Security Essentials', 'William Stallings', 'Networks', 58.00, 7],
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
    return 'images/cover-prog.jpg';
}
