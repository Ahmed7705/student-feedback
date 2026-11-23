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
$use_mysql = false;
$db_host = 'localhost';
$db_name = 'student_feedback';
$db_user = 'root';
$db_pass = '';

try {
    if ($use_mysql) {
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
$pdo->exec("
    CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(150) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL
    );
    CREATE TABLE IF NOT EXISTS courses (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        course_code VARCHAR(20) UNIQUE NOT NULL,
        course_name VARCHAR(150) NOT NULL,
        instructor_name VARCHAR(100) NOT NULL
    );
    CREATE TABLE IF NOT EXISTS feedbacks (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        course_id INTEGER NOT NULL,
        student_name VARCHAR(100) NOT NULL,
        student_id VARCHAR(30) NOT NULL,
        rating INTEGER NOT NULL,
        category VARCHAR(50) NOT NULL,
        title VARCHAR(150) NOT NULL,
        comments TEXT NOT NULL,
        image_path VARCHAR(255) DEFAULT NULL,
        is_anonymous INTEGER DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );
");

if ($pdo->query("SELECT COUNT(*) FROM courses")->fetchColumn() == 0) {
    $hash = password_hash('123456', PASSWORD_DEFAULT);
    $stmtUser = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmtUser->execute(['Maryam Jafry', 'maryam@jazanu.edu.sa', $hash]);

    $coursesData = [
        ['COMP-315', 'Web Programming', 'Dr. Khalid Al-Zahrani'],
        ['COMP-212', 'Object-Oriented Programming', 'Dr. Sara Al-Qahtani'],
        ['COMP-241', 'Database Systems', 'Dr. Noura Al-Shehri'],
        ['COMP-331', 'Computer Networks', 'Dr. Omar Al-Harbi'],
    ];
    $stmtCourse = $pdo->prepare("INSERT INTO courses (course_code, course_name, instructor_name) VALUES (?, ?, ?)");
    foreach ($coursesData as $c) {
        $stmtCourse->execute($c);
    }

    $feedbacksData = [
        [1, 'Maryam Jafry', '202500647', 5, 'Teaching Quality', 'Good web lectures', 'Clear explanation of HTML, PHP, and database labs.', 0],
        [1, 'Khadija Hussein', '202514175', 5, 'Course Content', 'Useful course content', 'Bootstrap and PHP tasks were helpful and easy to follow.', 0],
        [3, 'Mayaz Ahmed', '202502521', 4, 'Lab & Practical', 'Helpful SQL labs', 'Good practice on MySQL tables and queries in the lab.', 0],
    ];
    $stmtFb = $pdo->prepare("INSERT INTO feedbacks (course_id, student_name, student_id, rating, category, title, comments, is_anonymous) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($feedbacksData as $f) {
        $stmtFb->execute($f);
    }
}

function get_feedback_image($fb) {
    if (!empty($fb['image_path'])) {
        return e($fb['image_path']);
    }
    $cat = strtolower($fb['category'] ?? '');
    $code = $fb['course_code'] ?? '';
    if (strpos($cat, 'content') !== false) {
        return 'images/course-web-stacks.jpg';
    }
    if ($code === 'COMP-241') {
        return 'images/course-database.jpg';
    }
    if ($code === 'COMP-212') {
        return 'images/course-oop.jpg';
    }
    if ($code === 'COMP-331') {
        return 'images/course-networks.jpg';
    }
    return 'images/course-web-dev.jpg';
}
