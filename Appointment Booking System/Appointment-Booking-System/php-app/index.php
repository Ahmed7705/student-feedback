<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$pageTitle = 'Home';
$slots = get_time_slots();

$days = [];
for ($i = 0; $i < 3; $i++) {
    $days[] = date('Y-m-d', strtotime("+$i day"));
}

$bookedMap = [];
$startDate = $days[0];
$endDate = $days[count($days) - 1];

$stmt = $conn->prepare(
    "SELECT appointment_date, appointment_time
     FROM appointments
     WHERE appointment_date BETWEEN ? AND ? AND status = 'Booked'"
);
$stmt->execute([$startDate, $endDate]);
while ($row = $stmt->fetch()) {
    $bookedMap[$row['appointment_date']][$row['appointment_time']] = true;
}

include __DIR__ . '/includes/header.php';
?>

<section class="hero">
    <div>
        <h1>Academic Advising</h1>
        <p>Schedule and manage one-on-one advising sessions online.</p>
    </div>
    <a href="book.php" class="btn btn-primary">Book Appointment</a>
</section>

<div class="section-header">
    <h2>Available Dates &amp; Times</h2>
    <div class="legend">
        <span class="badge badge-available">Available</span>
        <span class="badge badge-booked">Booked</span>
    </div>
</div>

<div class="days-grid">
    <?php foreach ($days as $day): ?>
        <div class="day-card">
            <div class="day-title"><?= e(format_date($day)) ?></div>
            <ul class="slot-list">
                <?php foreach ($slots as $dbTime => $label): ?>
                    <?php $isBooked = isset($bookedMap[$day][$dbTime]); ?>
                    <li class="slot-item <?= $isBooked ? 'booked' : 'available' ?>">
                        <span><?= e($label) ?></span>
                        <?php if ($isBooked): ?>
                            <span class="slot-tag">Booked</span>
                        <?php else: ?>
                            <a class="slot-link" href="book.php?date=<?= e($day) ?>&amp;time=<?= e(substr($dbTime, 0, 5)) ?>">Book</a>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endforeach; ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
