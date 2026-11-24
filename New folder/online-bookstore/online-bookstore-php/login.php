<?php
require_once __DIR__ . '/config.php';

if (!empty($_SESSION['account_id'])) {
    header('Location: index.php');
    exit;
}

$err_msg = $_SESSION['msg_error'] ?? '';
unset($_SESSION['msg_error']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $email = trim($_POST['email'] ?? '');
    $pass = $_POST['password'] ?? '';

    $st = $db->prepare("SELECT * FROM users WHERE email = ?");
    $st->execute([$email]);
    $acc = $st->fetch();

    if ($acc && password_verify($pass, $acc['password'])) {
        $_SESSION['account_id'] = $acc['id'];
        $_SESSION['account_name'] = $acc['name'];
        $_SESSION['account_role'] = $acc['role'] ?? 'customer';
        $_SESSION['msg_ok'] = 'Signed in.';
        header('Location: index.php');
        exit;
    } else {
        $err_msg = 'Incorrect email or password.';
    }
}

$view_title = 'Sign In';
require_once __DIR__ . '/header.php';
?>

<div class="row justify-content-center my-5">
    <div class="col-md-5 col-lg-5">
        <div class="panel-box p-4 p-md-5" style="min-height: 480px;">
            <div class="mb-4 pb-3 border-bottom">
                <h4 class="fw-bold mb-2" style="color: var(--theme-main);">Sign In</h4>
                <div class="text-secondary small">Enter your credentials to access your account</div>
            </div>

            <?php if ($err_msg): ?>
                <div class="alert alert-danger py-2 small text-center mb-4"><?= clean($err_msg) ?></div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <input type="hidden" name="token" value="<?= clean($_SESSION['token']) ?>">

                <div class="mb-4">
                    <label class="form-label small fw-bold mb-2">Email</label>
                    <input type="email" name="email" class="form-control py-2" value="<?= clean($_POST['email'] ?? 'admin@bookstore.com') ?>" required>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold mb-2">Password</label>
                    <input type="password" name="password" class="form-control py-2" placeholder="Password (e.g. 123456)" required>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-4 mt-4 border-top">
                    <a href="register.php" class="small text-decoration-none fw-semibold" style="color: var(--theme-main);">Create an account</a>
                    <button type="submit" class="btn btn-store px-4 py-2">Sign In</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
