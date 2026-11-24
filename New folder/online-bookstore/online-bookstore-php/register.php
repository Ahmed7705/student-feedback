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

<div class="row justify-content-center mt-3">
    <div class="col-md-5">
        <div class="panel-box p-4">
            <h5 class="fw-bold mb-3" style="color: var(--theme-teal);">Register Account</h5>

            <?php if (!empty($err_list)): ?>
                <div class="alert alert-danger py-2 small">
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
                    <label class="form-label small fw-bold">Name</label>
                    <input type="text" name="name" class="form-control form-control-sm" value="<?= clean($_POST['name'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Email</label>
                    <input type="email" name="email" class="form-control form-control-sm" value="<?= clean($_POST['email'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Password</label>
                    <input type="password" name="password" class="form-control form-control-sm" placeholder="6+ characters" required>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-2">
                    <a href="login.php" class="small text-decoration-none" style="color: var(--theme-teal);">Sign In</a>
                    <button type="submit" class="btn btn-store btn-sm px-4">Register</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
