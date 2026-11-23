<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

require_admin();

$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
if ($id <= 0) {
    set_flash('error', 'Invalid appointment ID.');
    header('Location: manage.php');
    exit;
}

$stmt = $conn->prepare(
    'SELECT id, customer_name, email, appointment_date, appointment_time, status FROM appointments WHERE id = ?'
);
$stmt->execute([$id]);
$appointment = $stmt->fetch();

if (!$appointment) {
    set_flash('error', 'Appointment not found.');
    header('Location: manage.php');
    exit;
}

$errors = [];
$allowedStatuses = ['Booked', 'Completed', 'Cancelled'];
$slots = get_time_slots();

$customerName = $appointment['customer_name'];
$email = $appointment['email'];
$appointmentDate = $appointment['appointment_date'];
$appointmentTime = substr($appointment['appointment_time'], 0, 5);
$status = $appointment['status'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $customerName = trim($_POST['customer_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $appointmentDate = trim($_POST['appointment_date'] ?? '');
    $appointmentTime = trim($_POST['appointment_time'] ?? '');
    $status = trim($_POST['status'] ?? 'Booked');

    if ($customerName === '') {
        $errors[] = 'Name is required.';
    }
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid email is required.';
    }
    if ($appointmentDate === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $appointmentDate)) {
        $errors[] = 'Valid date is required.';
    }
    if ($appointmentTime === '' || !preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $appointmentTime)) {
        $errors[] = 'Valid time is required.';
    }
    if (!in_array($status, $allowedStatuses, true)) {
        $errors[] = 'Invalid status.';
    }

    $normalizedTime = strlen($appointmentTime) === 5 ? $appointmentTime . ':00' : $appointmentTime;

    if (empty($errors) && $status === 'Booked' && is_slot_booked($conn, $appointmentDate, $normalizedTime, $id)) {
        $errors[] = 'Selected slot is already booked.';
    }

    if (empty($errors)) {
        $stmt = $conn->prepare(
            'UPDATE appointments
             SET customer_name = ?, email = ?, appointment_date = ?, appointment_time = ?, status = ?
             WHERE id = ?'
        );
        $stmt->execute([$customerName, $email, $appointmentDate, $normalizedTime, $status, $id]);

        log_booking_event('UPDATED', "Appointment #$id -> $appointmentDate $normalizedTime ($status)");
        set_flash('success', 'Appointment updated.');
        header('Location: manage.php');
        exit;
    }
}

$pageTitle = 'Edit Appointment';
include __DIR__ . '/includes/header.php';
?>

<h1 class="page-title">Edit Appointment #<?= (int) $id ?></h1>
<p class="page-subtitle">Update details or change appointment status.</p>

<div class="card form-card">
    <?php if (!empty($errors)): ?>
        <div class="alert alert-error" role="alert">
            <ul class="error-list">
                <?php foreach ($errors as $error): ?>
                    <li><?= e($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="edit.php?id=<?= (int) $id ?>" novalidate>
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <input type="hidden" name="id" value="<?= (int) $id ?>">

        <div class="form-group">
            <label for="customer_name">Name</label>
            <input type="text" id="customer_name" name="customer_name" value="<?= e($customerName) ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= e($email) ?>" required>
        </div>

        <div class="form-group">
            <label for="appointment_date">Appointment Date</label>
            <input type="date" id="appointment_date" name="appointment_date" value="<?= e($appointmentDate) ?>" required>
        </div>

        <div class="form-group">
            <label for="appointment_time">Time</label>
            <input type="time" id="appointment_time" name="appointment_time" value="<?= e(substr($appointmentTime, 0, 5)) ?>" list="standard-slots" required>
            <datalist id="standard-slots">
                <?php foreach ($slots as $dbTime => $label): ?>
                    <option value="<?= e(substr($dbTime, 0, 5)) ?>"><?= e($label) ?></option>
                <?php endforeach; ?>
            </datalist>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status" required>
                <?php foreach ($allowedStatuses as $st): ?>
                    <option value="<?= e($st) ?>" <?= $status === $st ? 'selected' : '' ?>><?= e($st) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="manage.php" class="btn btn-secondary">Back</a>
        </div>
    </form>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
