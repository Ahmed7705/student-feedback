<?php
$page_title = 'Catalog';
require_once __DIR__ . '/header.php';

// Filter books
$search = trim($_GET['search'] ?? '');
$category = trim($_GET['category'] ?? '');

$sql = "SELECT * FROM books WHERE 1=1";
$params = [];

if ($category !== '') {
    $sql .= " AND category = ?";
    $params[] = $category;
}
if ($search !== '') {
    $sql .= " AND (title LIKE ? OR author LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}
$sql .= " ORDER BY id DESC";

// Execute query
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$books = $stmt->fetchAll();

$categories = ['Programming', 'Database', 'Software Engineering', 'Networks'];
?>

<div class="card p-3 mb-3">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
        <div>
            <h5 class="fw-bold mb-0" style="color: var(--app-blue);">Book Catalog</h5>
            <span class="text-muted small">Browse books and select items to calculate total price.</span>
        </div>
        <div class="small fw-bold">
            Selected Total: <span id="selectedTotal" class="price-text">0.00 SAR</span>
        </div>
    </div>
</div>

<div class="card p-3 mb-4">
    <form method="GET" action="index.php" class="row g-2 align-items-center">
        <div class="col-md-5">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search title or author..." value="<?= e($search) ?>">
        </div>
        <div class="col-md-5">
            <select name="category" class="form-select form-select-sm">
                <option value="">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= e($cat) ?>" <?= $category === $cat ? 'selected' : '' ?>><?= e($cat) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2 d-flex gap-1">
            <button type="submit" class="btn btn-blue btn-sm flex-grow-1">Filter</button>
            <a href="index.php" class="btn btn-outline-secondary btn-sm">Reset</a>
        </div>
    </form>
</div>

<div class="row g-3">
    <?php if (count($books) > 0): ?>
        <?php foreach ($books as $b): ?>
            <div class="col-md-6">
                <div class="card h-100 d-flex flex-column overflow-hidden">
                    <img src="<?= get_book_image($b) ?>" alt="<?= e($b['title']) ?>" class="card-img-top border-bottom" style="height: 150px; object-fit: cover;">

                    <div class="p-3 d-flex flex-column flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="small fw-semibold text-secondary"><?= e($b['category']) ?></span>
                            <span class="price-text"><?= number_format((float)$b['price'], 2) ?> SAR</span>
                        </div>

                        <h6 class="fw-bold mb-1"><?= e($b['title']) ?></h6>
                        <div class="text-muted small mb-2">
                            Author: <strong><?= e($b['author']) ?></strong> &middot; Quantity: <strong><?= e($b['quantity']) ?></strong>
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-2 mt-auto border-top small">
                            <div class="form-check mb-0">
                                <input type="checkbox" class="form-check-input book-check" id="chk_<?= e($b['id']) ?>" data-price="<?= number_format((float)$b['price'], 2, '.', '') ?>" onchange="calcTotal()">
                                <label class="form-check-label text-secondary" for="chk_<?= e($b['id']) ?>">Select Book</label>
                            </div>
                            <?php if (!empty($_SESSION['user_id'])): ?>
                                <div class="d-flex gap-1">
                                    <a href="edit.php?id=<?= e($b['id']) ?>" class="btn btn-sm btn-outline-secondary py-0 px-2">Edit</a>
                                    <form action="delete.php" method="POST" class="d-inline" onsubmit="return confirm('Delete this book?');">
                                        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
                                        <input type="hidden" name="id" value="<?= e($b['id']) ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2">Delete</button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12 text-center text-muted py-5 bg-white rounded border">
            No books found.
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
