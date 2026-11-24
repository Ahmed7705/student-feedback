<?php
require_once __DIR__ . '/config.php';
must_login();

$book_id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$st = $db->prepare("SELECT * FROM books WHERE id = ?");
$st->execute([$book_id]);
$record = $st->fetch();

if (!$record) {
    header('Location: manage.php');
    exit;
}

$cat_list = ['Programming', 'Database', 'Software Engineering', 'Networks', 'Algorithms', 'Cloud Computing'];
$err_list = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();

    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $quantity = trim($_POST['quantity'] ?? '');
    $photo_file = $record['image_path'];

    if (strlen($title) < 2) $err_list[] = 'Enter book title.';
    if (strlen($author) < 2) $err_list[] = 'Enter author name.';
    if ($category === '') $err_list[] = 'Choose a category.';
    if (!is_numeric($price) || (float)$price <= 0) $err_list[] = 'Enter a valid price greater than zero.';
    if (!is_numeric($quantity) || (int)$quantity < 0) $err_list[] = 'Enter a valid quantity.';

    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            $err_list[] = 'Unsupported image format.';
        } else {
            $saved_name = time() . '_' . basename($_FILES['image']['name']);
            $target = __DIR__ . '/uploads/' . $saved_name;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $photo_file = 'uploads/' . $saved_name;
            }
        }
    }

    if (empty($err_list)) {
        $upd = $db->prepare("UPDATE books SET title = ?, author = ?, category = ?, price = ?, quantity = ?, image_path = ? WHERE id = ?");
        $upd->execute([$title, $author, $category, (float)$price, (int)$quantity, $photo_file, $book_id]);
        $_SESSION['msg_ok'] = 'Book details updated.';
        header('Location: manage.php');
        exit;
    }
}

$view_title = 'Edit Book';
require_once __DIR__ . '/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="panel-box p-4">
            <h5 class="fw-bold mb-3" style="color: var(--theme-main);">Update Book</h5>

            <?php if (!empty($err_list)): ?>
                <div class="alert alert-danger py-2 small text-center">
                    <ul class="mb-0 ps-3">
                        <?php foreach ($err_list as $msg): ?>
                            <li><?= clean($msg) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="edit.php?id=<?= clean($record['id']) ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="token" value="<?= clean($_SESSION['token']) ?>">
                <input type="hidden" name="id" value="<?= clean($record['id']) ?>">

                <div class="mb-3">
                    <label class="form-label small fw-bold">Book Title *</label>
                    <input type="text" name="title" class="form-control form-control-sm" value="<?= clean($record['title']) ?>" required>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Author *</label>
                        <input type="text" name="author" class="form-control form-control-sm" value="<?= clean($record['author']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Category *</label>
                        <select name="category" class="form-select form-select-sm" required>
                            <?php foreach ($cat_list as $c): ?>
                                <option value="<?= clean($c) ?>" <?= $record['category'] === $c ? 'selected' : '' ?>><?= clean($c) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Price (SAR) *</label>
                        <input type="number" step="0.01" min="0.01" name="price" class="form-control form-control-sm" value="<?= clean($record['price']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Quantity *</label>
                        <input type="number" min="0" name="quantity" class="form-control form-control-sm" value="<?= clean($record['quantity']) ?>" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold">Replace Cover (Optional)</label>
                    <input type="file" name="image" class="form-control form-control-sm" accept="image/*">
                </div>

                <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                    <a href="manage.php" class="btn btn-outline-secondary btn-sm">Cancel</a>
                    <button type="submit" class="btn btn-store btn-sm px-4">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
