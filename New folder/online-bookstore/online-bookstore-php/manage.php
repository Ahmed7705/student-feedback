<?php
$page_title = 'Manage Books';
require_once __DIR__ . '/header.php';

$books = $pdo->query("SELECT * FROM books ORDER BY id DESC")->fetchAll();
?>

<div class="card p-4 bg-white">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0" style="color: var(--app-blue);">Manage Books</h5>
        <a href="create.php" class="btn btn-blue btn-sm px-3">+ Add Book</a>
    </div>

    <div class="table-responsive">
        <table class="table table-sm align-middle mb-0">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($books) > 0): ?>
                    <?php foreach ($books as $b): ?>
                        <tr>
                            <td class="fw-semibold"><?= e($b['title']) ?></td>
                            <td class="text-secondary small"><?= e($b['author']) ?></td>
                            <td class="text-secondary small"><?= e($b['category']) ?></td>
                            <td class="price-text"><?= number_format((float)$b['price'], 2) ?> SAR</td>
                            <td class="small"><?= e($b['quantity']) ?></td>
                            <td class="text-end">
                                <?php if (!empty($_SESSION['user_id'])): ?>
                                    <a href="edit.php?id=<?= e($b['id']) ?>" class="btn btn-sm btn-outline-secondary py-0 px-2">Edit</a>
                                    <form action="delete.php" method="POST" class="d-inline" onsubmit="return confirm('Delete this book?');">
                                        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
                                        <input type="hidden" name="id" value="<?= e($b['id']) ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2">Delete</button>
                                    </form>
                                <?php else: ?>
                                    <a href="login.php" class="small text-decoration-none" style="color: var(--app-blue);">Login to edit</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No books found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
