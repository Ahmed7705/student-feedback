<?php
require_once __DIR__ . '/config.php';

if (!empty($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (strlen($name) < 3) $errors[] = 'Full name must be at least 3 characters.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Please enter a valid email address.';
    if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';

    if (empty($errors)) {
        $stmtChk = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmtChk->execute([$email]);
        if ($stmtChk->fetch()) {
            $errors[] = 'Email is already registered.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmtIns = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmtIns->execute([$name, $email, $hash]);
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['user_name'] = $name;
            $_SESSION['flash_success'] = 'Account created.';
            header('Location: index.php');
            exit;
        }
    }
}

$page_title = 'Register';
require_once __DIR__ . '/header.php';
?>

<div class="row justify-content-center mt-3">
    <div class="col-md-5">
        <div class="card p-4 bg-white">
            <h5 class="fw-bold mb-3" style="color: var(--app-blue);">Create Account</h5>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger py-2 small">
                    <ul class="mb-0 ps-3">
                        <?php foreach ($errors as $err): ?>
                            <li><?= e($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="register.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">

                <div class="mb-3">
                    <label class="form-label small fw-bold">Full Name</label>
                    <input type="text" name="name" class="form-control form-control-sm" value="<?= e($_POST['name'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Email Address</label>
                    <input type="email" name="email" class="form-control form-control-sm" value="<?= e($_POST['email'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Password</label>
                    <input type="password" name="password" class="form-control form-control-sm" placeholder="Minimum 6 characters" required>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-2">
                    <a href="login.php" class="small text-decoration-none" style="color: var(--app-blue);">Already have an account?</a>
                    <button type="submit" class="btn btn-blue btn-sm px-4">Register</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
