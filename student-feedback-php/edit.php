<?php
require_once __DIR__ . '/config.php';
require_login();

// Fetch record
$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM feedbacks WHERE id = ?");
$stmt->execute([$id]);
$feedback = $stmt->fetch();

if (!$feedback) {
    header('Location: index.php');
    exit;
}

$courses = $pdo->query("SELECT * FROM courses ORDER BY id")->fetchAll();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $course_id = trim($_POST['course_id'] ?? '');
    $student_name = trim($_POST['student_name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $rating = (int)($_POST['rating'] ?? 5);
    $title = trim($_POST['title'] ?? '');
    $comments = trim($_POST['comments'] ?? '');
    $is_anonymous = isset($_POST['is_anonymous']) ? 1 : 0;
    $image_path = $feedback['image_path'];

    if ($course_id === '') $errors[] = 'Please select a course.';
    if (strlen($student_name) < 3) $errors[] = 'Student name is required.';
    if (strlen($title) < 3) $errors[] = 'Title is required.';
    if (strlen($comments) < 5) $errors[] = 'Comments are required.';

    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            $errors[] = 'Invalid image file.';
        } else {
            $filename = time() . '_' . basename($_FILES['image']['name']);
            $dest = __DIR__ . '/uploads/' . $filename;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                $image_path = 'uploads/' . $filename;
            }
        }
    }

    // Update record
    if (empty($errors)) {
        $stmtUpd = $pdo->prepare("UPDATE feedbacks SET course_id = ?, student_name = ?, rating = ?, category = ?, title = ?, comments = ?, image_path = ?, is_anonymous = ? WHERE id = ?");
        $stmtUpd->execute([$course_id, $student_name, $rating, $category, $title, $comments, $image_path, $is_anonymous, $id]);
        $_SESSION['flash_success'] = 'Feedback updated.';
        header('Location: index.php');
        exit;
    }
}

$page_title = 'Edit Feedback';
require_once __DIR__ . '/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card p-4 bg-white">
            <h5 class="fw-bold mb-3" style="color: var(--app-blue);">Edit Course Feedback</h5>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger py-2 small">
                    <ul class="mb-0 ps-3">
                        <?php foreach ($errors as $err): ?>
                            <li><?= e($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="edit.php?id=<?= e($feedback['id']) ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
                <input type="hidden" name="id" value="<?= e($feedback['id']) ?>">

                <div class="mb-3">
                    <label class="form-label small fw-bold">Course</label>
                    <select name="course_id" class="form-select form-select-sm" required>
                        <?php foreach ($courses as $c): ?>
                            <option value="<?= e($c['id']) ?>" <?= $feedback['course_id'] == $c['id'] ? 'selected' : '' ?>>
                                <?= e($c['course_code']) ?> - <?= e($c['course_name']) ?> (<?= e($c['instructor_name']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Student Name</label>
                    <input type="text" name="student_name" class="form-control form-control-sm" value="<?= e($feedback['student_name']) ?>" required>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Category</label>
                        <select name="category" class="form-select form-select-sm" required>
                            <option value="Teaching Quality" <?= $feedback['category'] === 'Teaching Quality' ? 'selected' : '' ?>>Teaching Quality</option>
                            <option value="Course Content" <?= $feedback['category'] === 'Course Content' ? 'selected' : '' ?>>Course Content</option>
                            <option value="Lab & Practical" <?= $feedback['category'] === 'Lab & Practical' ? 'selected' : '' ?>>Lab & Practical</option>
                            <option value="Assessment & Grading" <?= $feedback['category'] === 'Assessment & Grading' ? 'selected' : '' ?>>Assessment & Grading</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Rating</label>
                        <select name="rating" class="form-select form-select-sm" required>
                            <option value="5" <?= $feedback['rating'] == 5 ? 'selected' : '' ?>>5 - Excellent (5/5)</option>
                            <option value="4" <?= $feedback['rating'] == 4 ? 'selected' : '' ?>>4 - Very Good (4/5)</option>
                            <option value="3" <?= $feedback['rating'] == 3 ? 'selected' : '' ?>>3 - Good (3/5)</option>
                            <option value="2" <?= $feedback['rating'] == 2 ? 'selected' : '' ?>>2 - Fair (2/5)</option>
                            <option value="1" <?= $feedback['rating'] == 1 ? 'selected' : '' ?>>1 - Poor (1/5)</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Feedback Title</label>
                    <input type="text" name="title" class="form-control form-control-sm" value="<?= e($feedback['title']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Comments</label>
                    <textarea name="comments" rows="3" class="form-control form-control-sm" required><?= e($feedback['comments']) ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Update Image (Optional)</label>
                    <input type="file" name="image" class="form-control form-control-sm" accept="image/*">
                </div>

                <div class="form-check mb-4">
                    <input type="checkbox" name="is_anonymous" class="form-check-input" id="anon" value="1" <?= $feedback['is_anonymous'] ? 'checked' : '' ?>>
                    <label class="form-check-label small" for="anon">Submit Anonymously</label>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                    <a href="index.php" class="btn btn-outline-secondary btn-sm">Back</a>
                    <button type="submit" class="btn btn-blue btn-sm px-4">Update Feedback</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
