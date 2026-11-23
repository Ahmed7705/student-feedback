<?php
$page_title = 'Course Reviews';
require_once __DIR__ . '/header.php';

// Fetch courses
$courses = $pdo->query("SELECT * FROM courses ORDER BY id")->fetchAll();

// Search filter
$search = trim($_GET['search'] ?? '');
$course_id = trim($_GET['course_id'] ?? '');

$sql = "SELECT f.*, c.course_code, c.course_name, c.instructor_name
        FROM feedbacks f
        JOIN courses c ON f.course_id = c.id
        WHERE 1=1";
$params = [];

if ($course_id !== '') {
    $sql .= " AND f.course_id = ?";
    $params[] = $course_id;
}
if ($search !== '') {
    $sql .= " AND (f.title LIKE ? OR f.comments LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}
$sql .= " ORDER BY f.id DESC";

// Execute query
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$feedbacks = $stmt->fetchAll();
?>

<div class="card p-3 mb-4">
    <form method="GET" action="index.php" class="row g-2 align-items-center">
        <div class="col-md-5">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search feedback..." value="<?= e($search) ?>">
        </div>
        <div class="col-md-5">
            <select name="course_id" class="form-select form-select-sm">
                <option value="">All Courses</option>
                <?php foreach ($courses as $c): ?>
                    <option value="<?= e($c['id']) ?>" <?= $course_id == $c['id'] ? 'selected' : '' ?>>
                        <?= e($c['course_code']) ?> - <?= e($c['course_name']) ?>
                    </option>
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
    <?php if (count($feedbacks) > 0): ?>
        <?php foreach ($feedbacks as $fb): ?>
            <div class="col-md-6">
                <div class="card h-100 d-flex flex-column overflow-hidden">
                    <img src="<?= get_feedback_image($fb) ?>" alt="<?= e($fb['course_name']) ?>" class="card-img-top border-bottom" style="height: 150px; object-fit: cover;">

                    <div class="p-3 d-flex flex-column flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold" style="color: var(--app-blue); font-size: 0.9rem;"><?= e($fb['course_code']) ?></span>
                            <span class="text-secondary small fw-semibold">Rating: <?= e($fb['rating']) ?>/5</span>
                        </div>

                        <h6 class="fw-bold mb-1"><?= e($fb['title']) ?></h6>
                        <div class="text-muted small mb-2">
                            Instructor: <strong><?= e($fb['instructor_name']) ?></strong> &middot; <?= e($fb['category']) ?>
                        </div>
                        <p class="text-secondary small mb-3 flex-grow-1"><?= e($fb['comments']) ?></p>

                        <div class="d-flex justify-content-between align-items-center pt-2 border-top small text-muted">
                            <div>
                                Student: <strong><?= $fb['is_anonymous'] ? 'Anonymous' : e($fb['student_name']) ?></strong>
                            </div>
                            <?php if (!empty($_SESSION['user_id'])): ?>
                                <div class="d-flex gap-1">
                                    <a href="edit.php?id=<?= e($fb['id']) ?>" class="btn btn-sm btn-outline-secondary py-0 px-2">Edit</a>
                                    <form action="delete.php" method="POST" class="d-inline" onsubmit="return confirm('Delete this feedback?');">
                                        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
                                        <input type="hidden" name="id" value="<?= e($fb['id']) ?>">
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
            No course reviews found.
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
