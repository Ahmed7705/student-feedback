<?php
$view_title = 'Catalog';
require_once __DIR__ . '/header.php';

$q_text = trim($_GET['search'] ?? '');
$q_cat = trim($_GET['category'] ?? '');

$query = "SELECT * FROM books WHERE 1=1";
$args = [];

if ($q_cat !== '') {
    $query .= " AND category = ?";
    $args[] = $q_cat;
}
if ($q_text !== '') {
    $query .= " AND (title LIKE ? OR author LIKE ?)";
    $args[] = "%$q_text%";
    $args[] = "%$q_text%";
}
$query .= " ORDER BY id DESC";

$st = $db->prepare($query);
$st->execute($args);
$list = $st->fetchAll();

$cat_list = ['Programming', 'Database', 'Software Engineering', 'Networks', 'Algorithms', 'Cloud Computing'];
?>

<div class="panel-box p-4 mb-4 text-center">
    <h3 class="fw-bold mb-2" style="color: var(--theme-main);">Welcome to Online Bookstore</h3>
    <p class="text-secondary mx-auto mb-0" style="max-width: 680px;">
        Explore our curated selection of computer science, software engineering, and technology books. Browse titles, search by category, and easily calculate your total order cost below.
    </p>
</div>

<div class="panel-box p-4 mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center pb-3 mb-3 border-bottom gap-3">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--theme-main);">Available Books</h4>
            <span class="text-muted small">Check books below to compute the total cost.</span>
        </div>
        <div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-2" style="background: rgba(92, 61, 46, 0.08); border: 1px solid var(--theme-line);">
            <span class="small fw-semibold text-secondary">Total Price:</span>
            <span id="totalBox" class="cost-label fw-bold">0.00 SAR</span>
        </div>
    </div>

    <form method="GET" action="index.php" class="row g-2 align-items-center">
        <div class="col-md-5">
            <input type="text" name="search" class="form-control" placeholder="Find by title or author..." value="<?= clean($q_text) ?>">
        </div>
        <div class="col-md-5">
            <select name="category" class="form-select">
                <option value="">All Categories</option>
                <?php foreach ($cat_list as $c): ?>
                    <option value="<?= clean($c) ?>" <?= $q_cat === $c ? 'selected' : '' ?>><?= clean($c) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2 d-flex gap-1">
            <button type="submit" class="btn btn-store flex-grow-1">Search</button>
            <a href="index.php" class="btn btn-outline-secondary">Clear</a>
        </div>
    </form>
</div>

<div class="row g-3">
    <?php if (count($list) > 0): ?>
        <?php foreach ($list as $item): ?>
            <div class="col-md-4">
                <div class="book-item h-100 d-flex flex-column overflow-hidden">
                    <img src="<?= book_photo($item) ?>" alt="<?= clean($item['title']) ?>" class="w-100 border-bottom" style="height: 165px; object-fit: cover;">

                    <div class="p-3 d-flex flex-column flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="small text-muted"><?= clean($item['category']) ?></span>
                            <span class="cost-label"><?= number_format((float)$item['price'], 2) ?> SAR</span>
                        </div>

                        <h6 class="fw-bold mb-1"><?= clean($item['title']) ?></h6>
                        <div class="text-secondary small mb-3">
                            By <?= clean($item['author']) ?> &middot; Stock: <?= clean($item['quantity']) ?>
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-2 mt-auto border-top small">
                            <div class="form-check mb-0">
                                <input type="checkbox" class="form-check-input pick-book" id="b_<?= clean($item['id']) ?>" data-cost="<?= number_format((float)$item['price'], 2, '.', '') ?>" onchange="updatePriceSum()">
                                <label class="form-check-label text-secondary" for="b_<?= clean($item['id']) ?>">Select</label>
                            </div>
                            <?php if (!empty($_SESSION['account_id']) && is_admin()): ?>
                                <div class="d-flex gap-1">
                                    <a href="edit.php?id=<?= clean($item['id']) ?>" class="btn btn-sm btn-outline-secondary py-0 px-2">Edit</a>
                                    <form action="delete.php" method="POST" class="d-inline" onsubmit="return confirm('Remove this book?');">
                                        <input type="hidden" name="token" value="<?= clean($_SESSION['token']) ?>">
                                        <input type="hidden" name="id" value="<?= clean($item['id']) ?>">
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
        <div class="col-12 text-center text-muted py-5 panel-box">
            No matching books found.
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
