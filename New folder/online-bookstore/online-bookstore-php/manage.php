<?php
$view_title = 'Manage Books';
require_once __DIR__ . '/header.php';

$list = $db->query("SELECT * FROM books ORDER BY id DESC")->fetchAll();
?>

<div class="panel-box p-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center pb-3 mb-4 border-bottom gap-3">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--theme-main);">Book Inventory</h4>
            <div class="text-secondary small">Overview of all catalog titles, stock levels, and store pricing</div>
        </div>
        <div>
            <a href="create.php" class="btn btn-store px-3 py-2">+ New Book</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead style="background: rgba(92, 61, 46, 0.05); border-bottom: 2px solid var(--theme-line);">
                <tr>
                    <th class="py-3 px-3">Title</th>
                    <th class="py-3 px-3">Author</th>
                    <th class="py-3 px-3">Category</th>
                    <th class="py-3 px-3">Price</th>
                    <th class="py-3 px-3">Quantity</th>
                    <th class="py-3 px-3 text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($list) > 0): ?>
                    <?php foreach ($list as $item): ?>
                        <tr>
                            <td class="py-3 px-3">
                                <div class="fw-bold" style="color: var(--theme-ink);"><?= clean($item['title']) ?></div>
                            </td>
                            <td class="py-3 px-3 text-secondary small"><?= clean($item['author']) ?></td>
                            <td class="py-3 px-3">
                                <span class="badge" style="background: rgba(92, 61, 46, 0.08); color: var(--theme-main); font-weight: 500; font-size: 0.8rem; border: 1px solid var(--theme-line);"><?= clean($item['category']) ?></span>
                            </td>
                            <td class="py-3 px-3">
                                <span class="cost-label fw-bold"><?= number_format((float)$item['price'], 2) ?> SAR</span>
                            </td>
                            <td class="py-3 px-3">
                                <span class="fw-semibold"><?= clean($item['quantity']) ?></span> <span class="text-secondary small">in stock</span>
                            </td>
                            <td class="py-3 px-3 text-end">
                                <?php if (!empty($_SESSION['account_id']) && is_admin()): ?>
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="edit.php?id=<?= clean($item['id']) ?>" class="btn btn-sm btn-outline-secondary px-3">Edit</a>
                                        <form action="delete.php" method="POST" class="d-inline" onsubmit="return confirm('Remove this book?');">
                                            <input type="hidden" name="token" value="<?= clean($_SESSION['token']) ?>">
                                            <input type="hidden" name="id" value="<?= clean($item['id']) ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger px-3">Delete</button>
                                        </form>
                                    </div>
                                <?php else: ?>
                                    <a href="login.php" class="small text-decoration-none fw-semibold" style="color: var(--theme-main);">Sign in to edit</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">No books in inventory.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
