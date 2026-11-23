<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$pageTitle = 'Book Appointment';
$slots = get_time_slots();
$errors = [];

$customerName = is_logged_in() ? current_user_name() : '';
$email = is_logged_in() ? current_user_email() : '';
$appointmentDate = $_GET['date'] ?? date('Y-m-d');
$appointmentTime = $_GET['time'] ?? '09:00';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $customerName = trim($_POST['customer_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $appointmentDate = trim($_POST['appointment_date'] ?? '');
    $appointmentTime = trim($_POST['appointment_time'] ?? '');

    if ($customerName === '') {
        $errors[] = 'Name is required.';
    }
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid email is required.';
    }
    if ($appointmentDate === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $appointmentDate)) {
        $errors[] = 'Valid date is required.';
    } elseif ($appointmentDate < date('Y-m-d')) {
        $errors[] = 'Past dates are not allowed.';
    }
    if ($appointmentTime === '' || !preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $appointmentTime)) {
        $errors[] = 'Valid time is required.';
    }

    // Prevent double booking
    $normalizedTime = strlen($appointmentTime) === 5 ? $appointmentTime . ':00' : $appointmentTime;
    if (empty($errors) && is_slot_booked($conn, $appointmentDate, $normalizedTime)) {
        $errors[] = 'Selected date and time is already booked. Choose another slot.';
    }

    $attachmentPath = null;
    if (empty($errors) && isset($_FILES['attachment'])) {
        $upload = handle_attachment_upload($_FILES['attachment']);
        if ($upload['error'] !== null) {
            $errors[] = $upload['error'];
        } else {
            $attachmentPath = $upload['path'];
        }
    }

    if (empty($errors)) {
        $status = 'Booked';
        $stmt = $conn->prepare(
            'INSERT INTO appointments (customer_name, email, appointment_date, appointment_time, status, attachment)
             VALUES (?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([$customerName, $email, $appointmentDate, $normalizedTime, $status, $attachmentPath]);

        log_booking_event('BOOKED', "$customerName ($email) on $appointmentDate at $normalizedTime");
        set_flash('success', 'Appointment booked.');
        header('Location: index.php');
        exit;
    }
}

include __DIR__ . '/includes/header.php';
?>

<h1 class="page-title">Book Appointment</h1>
<p class="page-subtitle">Select a date and time to reserve your slot.</p>

<div class="card form-card">
    <?php if (!empty($errors)): ?>
        <div class="alert alert-error" role="alert">
            <strong>Please fix the following:</strong>
            <ul class="error-list">
                <?php foreach ($errors as $error): ?>
                    <li><?= e($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="book.php" enctype="multipart/form-data" novalidate>
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

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
            <input type="date" id="appointment_date" name="appointment_date" value="<?= e($appointmentDate) ?>" min="<?= e(date('Y-m-d')) ?>" required>
        </div>

        <div class="form-group">
            <label for="appointment_time">Time</label>
            <input type="time" id="appointment_time" name="appointment_time" value="<?= e(substr($appointmentTime, 0, 5)) ?>" list="standard-slots" required>
            <datalist id="standard-slots">
                <?php foreach ($slots as $dbTime => $label): ?>
                    <option value="<?= e(substr($dbTime, 0, 5)) ?>"><?= e($label) ?></option>
                <?php endforeach; ?>
            </datalist>
            <span class="form-hint">Standard slots: 09:00, 10:00, 11:00, 13:00, 14:00, 15:00.</span>
        </div>

        <div class="form-group">
            <label for="attachment">Attachment (Optional)</label>
            <input type="file" id="attachment" name="attachment" accept=".pdf,.png,.jpg,.jpeg,.txt">
            <span class="form-hint">PDF, PNG, JPG, or TXT (max 2 MB).</span>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Submit</button>
            <button type="reset" class="btn btn-secondary">Reset</button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
