<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

require_login();

$admin = is_admin();
$userEmail = current_user_email();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? '';
    $id = (int) ($_POST['id'] ?? 0);

    if ($id > 0 && $action === 'cancel') {
        if ($admin) {
            $stmt = $conn->prepare("UPDATE appointments SET status = 'Cancelled' WHERE id = ?");
            $stmt->execute([$id]);
        } else {
            $stmt = $conn->prepare("UPDATE appointments SET status = 'Cancelled' WHERE id = ? AND LOWER(email) = LOWER(?)");
            $stmt->execute([$id, $userEmail]);
        }

        if ($stmt->rowCount() > 0) {
            log_booking_event('CANCELLED', "Appointment #$id");
            set_flash('success', 'Appointment cancelled.');
        } else {
            set_flash('error', 'Action not permitted.');
        }
    } elseif ($id > 0 && $action === 'delete') {
        if (!$admin) {
            http_response_code(403);
            set_flash('error', 'Admin access required.');
            header('Location: manage.php');
            exit;
        }
        $stmt = $conn->prepare('DELETE FROM appointments WHERE id = ?');
        $stmt->execute([$id]);

        log_booking_event('DELETED', "Appointment #$id");
        set_flash('success', 'Appointment deleted.');
    }

    header('Location: manage.php');
    exit;
}

$filterDate = trim($_GET['date'] ?? '');
$filterStatus = trim($_GET['status'] ?? '');
$allowedStatuses = ['Booked', 'Completed', 'Cancelled'];

$sql = 'SELECT id, customer_name, email, appointment_date, appointment_time, status, attachment FROM appointments WHERE 1=1';
$params = [];

// Regular users only see their own bookings
if (!$admin) {
    $sql .= ' AND LOWER(email) = LOWER(?)';
    $params[] = $userEmail;
}

if ($filterDate !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $filterDate)) {
    $sql .= ' AND appointment_date = ?';
    $params[] = $filterDate;
}

if ($filterStatus !== '' && in_array($filterStatus, $allowedStatuses, true)) {
    $sql .= ' AND status = ?';
    $params[] = $filterStatus;
}

$sql .= ' ORDER BY appointment_date ASC, appointment_time ASC';

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$appointments = $stmt->fetchAll();

$pageTitle = 'Manage Appointments';
include __DIR__ . '/includes/header.php';
?>

<div class="section-header">
    <div>
        <h1 class="page-title"><?= $admin ? 'Manage Appointments' : 'My Appointments' ?></h1>
        <p class="page-subtitle"><?= $admin ? 'Filter, edit, cancel, or remove bookings.' : 'View or cancel your bookings.' ?></p>
    </div>
    <div>
        <?php if ($admin): ?>
            <a href="export.php" class="btn btn-secondary btn-sm">Export CSV</a>
        <?php endif; ?>
        <a href="book.php" class="btn btn-primary btn-sm">New Booking</a>
    </div>
</div>

<form method="GET" action="manage.php" class="card filter-bar">
    <div class="form-group">
        <label for="filter_date">Date</label>
        <input type="date" id="filter_date" name="date" value="<?= e($filterDate) ?>">
    </div>

    <div class="form-group">
        <label for="filter_status">Status</label>
        <select id="filter_status" name="status">
            <option value="">All Statuses</option>
            <?php foreach ($allowedStatuses as $st): ?>
                <option value="<?= e($st) ?>" <?= $filterStatus === $st ? 'selected' : '' ?>><?= e($st) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Filter</button>
    <a href="manage.php" class="btn btn-secondary">Clear</a>
</form>

<div class="table-wrap">
    <?php if (empty($appointments)): ?>
        <div class="empty-state">No appointments found.</div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>File</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments as $row): ?>
                    <?php $badgeClass = 'badge-' . strtolower($row['status']); ?>
                    <tr>
                        <td>#<?= (int) $row['id'] ?></td>
                        <td><?= e($row['customer_name']) ?></td>
                        <td><?= e($row['email']) ?></td>
                        <td><?= e($row['appointment_date']) ?></td>
                        <td><?= e(format_time($row['appointment_time'])) ?></td>
                        <td><span class="badge <?= e($badgeClass) ?>"><?= e($row['status']) ?></span></td>
                        <td>
                            <?php if (!empty($row['attachment'])): ?>
                                <a href="download.php?id=<?= (int) $row['id'] ?>" target="_blank" rel="noopener">View</a>
                            <?php else: ?>
                                &mdash;
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="actions-cell">
                                <?php if ($admin): ?>
                                    <a href="edit.php?id=<?= (int) $row['id'] ?>" class="btn btn-secondary btn-sm">Edit</a>
                                <?php endif; ?>

                                <?php if ($row['status'] !== 'Cancelled'): ?>
                                    <form method="POST" action="manage.php" class="inline-form js-confirm-cancel">
                                        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                        <input type="hidden" name="action" value="cancel">
                                        <input type="hidden" name="id" value="<?= (int) $row['id'] ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Cancel</button>
                                    </form>
                                <?php endif; ?>

                                <?php if ($admin): ?>
                                    <form method="POST" action="manage.php" class="inline-form js-confirm-delete">
                                        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= (int) $row['id'] ?>">
                                        <button type="submit" class="btn btn-secondary btn-sm">Delete</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
