<?php
$view_title = 'Manage Books';
require_once __DIR__ . '/header.php';

$list = $db->query("SELECT * FROM books ORDER BY id DESC")->fetchAll();
?>

<div class="panel-box p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0" style="color: var(--theme-main);">Book Inventory</h5>
        <a href="create.php" class="btn btn-store btn-sm px-3">+ New Book</a>
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
                <?php if (count($list) > 0): ?>
                    <?php foreach ($list as $item): ?>
                        <tr>
                            <td class="fw-semibold"><?= clean($item['title']) ?></td>
                            <td class="text-secondary small"><?= clean($item['author']) ?></td>
                            <td class="text-secondary small"><?= clean($item['category']) ?></td>
                            <td class="cost-label"><?= number_format((float)$item['price'], 2) ?> SAR</td>
                            <td class="small"><?= clean($item['quantity']) ?></td>
                            <td class="text-end">
                                <?php if (!empty($_SESSION['account_id'])): ?>
                                    <a href="edit.php?id=<?= clean($item['id']) ?>" class="btn btn-sm btn-outline-secondary py-0 px-2">Edit</a>
                                    <form action="delete.php" method="POST" class="d-inline" onsubmit="return confirm('Remove this book?');">
                                        <input type="hidden" name="token" value="<?= clean($_SESSION['token']) ?>">
                                        <input type="hidden" name="id" value="<?= clean($item['id']) ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2">Delete</button>
                                    </form>
                                <?php else: ?>
                                    <a href="login.php" class="small text-decoration-none" style="color: var(--theme-main);">Sign in to edit</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No books in inventory.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
