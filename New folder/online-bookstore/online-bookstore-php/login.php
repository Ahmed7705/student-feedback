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

<div class="row justify-content-center mt-3">
    <div class="col-md-5">
        <div class="panel-box p-4">
            <h5 class="fw-bold mb-3" style="color: var(--theme-teal);">Sign In</h5>

            <?php if ($err_msg): ?>
                <div class="alert alert-danger py-2 small"><?= clean($err_msg) ?></div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <input type="hidden" name="token" value="<?= clean($_SESSION['token']) ?>">

                <div class="mb-3">
                    <label class="form-label small fw-bold">Email</label>
                    <input type="email" name="email" class="form-control form-control-sm" value="<?= clean($_POST['email'] ?? 'admin@bookstore.com') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Password</label>
                    <input type="password" name="password" class="form-control form-control-sm" placeholder="Password (e.g. 123456)" required>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-2">
                    <a href="register.php" class="small text-decoration-none" style="color: var(--theme-teal);">New account</a>
                    <button type="submit" class="btn btn-store btn-sm px-4">Sign In</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
