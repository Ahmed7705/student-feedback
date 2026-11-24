<?php
require_once __DIR__ . '/config.php';
require_login();

// Fetch record
$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
$stmt->execute([$id]);
$book = $stmt->fetch();

if (!$book) {
    header('Location: manage.php');
    exit;
}

$categories = ['Programming', 'Database', 'Software Engineering', 'Networks'];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $quantity = trim($_POST['quantity'] ?? '');
    $image_path = $book['image_path'];

    if (strlen($title) < 2) $errors[] = 'Book title is required.';
    if (strlen($author) < 2) $errors[] = 'Author is required.';
    if ($category === '') $errors[] = 'Category is required.';
    if (!is_numeric($price) || (float)$price <= 0) $errors[] = 'Price must be a valid positive number.';
    if (!is_numeric($quantity) || (int)$quantity < 0) $errors[] = 'Quantity must be 0 or more.';

    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            $errors[] = 'Invalid image file.';
        } else {
            $filename = time() . '_' . basename($_FILES['image']['name']);
            $dest = __DIR__ . '/uploads/' . $filename;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                $image_path = 'uploads/' . $filename;
            }
        }
    }

    // Update record
    if (empty($errors)) {
        $stmtUpd = $pdo->prepare("UPDATE books SET title = ?, author = ?, category = ?, price = ?, quantity = ?, image_path = ? WHERE id = ?");
        $stmtUpd->execute([$title, $author, $category, (float)$price, (int)$quantity, $image_path, $id]);
        $_SESSION['flash_success'] = 'Book updated.';
        header('Location: manage.php');
        exit;
    }
}

$page_title = 'Edit Book';
require_once __DIR__ . '/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card p-4 bg-white">
            <h5 class="fw-bold mb-3" style="color: var(--app-blue);">Edit Book</h5>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger py-2 small">
                    <ul class="mb-0 ps-3">
                        <?php foreach ($errors as $err): ?>
                            <li><?= e($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="edit.php?id=<?= e($book['id']) ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
                <input type="hidden" name="id" value="<?= e($book['id']) ?>">

                <div class="mb-3">
                    <label class="form-label small fw-bold">Book Title *</label>
                    <input type="text" name="title" class="form-control form-control-sm" value="<?= e($book['title']) ?>" required>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Author *</label>
                        <input type="text" name="author" class="form-control form-control-sm" value="<?= e($book['author']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Category *</label>
                        <select name="category" class="form-select form-select-sm" required>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= e($cat) ?>" <?= $book['category'] === $cat ? 'selected' : '' ?>><?= e($cat) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Price (SAR) *</label>
                        <input type="number" step="0.01" min="0.01" name="price" class="form-control form-control-sm" value="<?= e($book['price']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Quantity *</label>
                        <input type="number" min="0" name="quantity" class="form-control form-control-sm" value="<?= e($book['quantity']) ?>" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold">Update Cover (Optional)</label>
                    <input type="file" name="image" class="form-control form-control-sm" accept="image/*">
                </div>

                <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                    <a href="manage.php" class="btn btn-outline-secondary btn-sm">Back</a>
                    <button type="submit" class="btn btn-blue btn-sm px-4">Update Book</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
