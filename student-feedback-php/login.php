<?php
require_once __DIR__ . '/config.php';

if (!empty($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$error = $_SESSION['flash_error'] ?? '';
unset($_SESSION['flash_error']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Check user password
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Save session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['flash_success'] = 'Logged in successfully.';
        header('Location: index.php');
        exit;
    } else {
        $error = 'Invalid email or password.';
    }
}

$page_title = 'Login';
require_once __DIR__ . '/header.php';
?>

<div class="row justify-content-center mt-3">
    <div class="col-md-5">
        <div class="card p-4 bg-white">
            <h5 class="fw-bold mb-3" style="color: var(--app-blue);">Student Login</h5>

            <?php if ($error): ?>
                <div class="alert alert-danger py-2 small"><?= e($error) ?></div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">

                <div class="mb-3">
                    <label class="form-label small fw-bold">Email Address</label>
                    <input type="email" name="email" class="form-control form-control-sm" value="<?= e($_POST['email'] ?? 'maryam@jazanu.edu.sa') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Password</label>
                    <input type="password" name="password" class="form-control form-control-sm" placeholder="Enter password (e.g. 123456)" required>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-2">
                    <a href="register.php" class="small text-decoration-none" style="color: var(--app-blue);">Create Account</a>
                    <button type="submit" class="btn btn-blue btn-sm px-4">Login</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
