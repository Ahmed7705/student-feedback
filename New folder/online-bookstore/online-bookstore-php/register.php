<?php
require_once __DIR__ . '/config.php';

if (!empty($_SESSION['account_id'])) {
    header('Location: index.php');
    exit;
}

$err_list = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass = $_POST['password'] ?? '';

    if (strlen($name) < 3) $err_list[] = 'Name must be at least 3 characters.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $err_list[] = 'Enter a valid email.';
    if (strlen($pass) < 6) $err_list[] = 'Password must be at least 6 characters.';

    if (empty($err_list)) {
        $chk = $db->prepare("SELECT id FROM users WHERE email = ?");
        $chk->execute([$email]);
        if ($chk->fetch()) {
            $err_list[] = 'Email already exists.';
        } else {
            $pw = password_hash($pass, PASSWORD_DEFAULT);
            $ins = $db->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $ins->execute([$name, $email, $pw]);
            $_SESSION['account_id'] = $db->lastInsertId();
            $_SESSION['account_name'] = $name;
            $_SESSION['msg_ok'] = 'Registration complete.';
            header('Location: index.php');
            exit;
        }
    }
}

$view_title = 'Register';
require_once __DIR__ . '/header.php';
?>

<div class="row justify-content-center my-5">
    <div class="col-md-5 col-lg-5">
        <div class="panel-box p-4 p-md-5" style="min-height: 480px;">
            <div class="mb-4 pb-3 border-bottom">
                <h4 class="fw-bold mb-2" style="color: var(--theme-main);">Register Account</h4>
                <div class="text-secondary small">Create an account to manage your bookstore catalog</div>
            </div>

            <?php if (!empty($err_list)): ?>
                <div class="alert alert-danger py-2 small text-center mb-4">
                    <ul class="mb-0 ps-3">
                        <?php foreach ($err_list as $msg): ?>
                            <li><?= clean($msg) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="register.php" method="POST">
                <input type="hidden" name="token" value="<?= clean($_SESSION['token']) ?>">

                <div class="mb-3">
                    <label class="form-label small fw-bold mb-2">Name</label>
                    <input type="text" name="name" class="form-control py-2" value="<?= clean($_POST['name'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold mb-2">Email</label>
                    <input type="email" name="email" class="form-control py-2" value="<?= clean($_POST['email'] ?? '') ?>" required>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold mb-2">Password</label>
                    <input type="password" name="password" class="form-control py-2" placeholder="6+ characters" required>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-4 mt-4 border-top">
                    <a href="login.php" class="small text-decoration-none fw-semibold" style="color: var(--theme-main);">Sign In</a>
                    <button type="submit" class="btn btn-store px-4 py-2">Register</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
