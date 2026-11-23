<?php
require_once __DIR__ . '/config.php';
// Require login
require_login();

$courses = $pdo->query("SELECT * FROM courses ORDER BY id")->fetchAll();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $course_id = trim($_POST['course_id'] ?? '');
    $student_name = trim($_POST['student_name'] ?? '');
    $student_id = trim($_POST['student_id'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $rating = (int)($_POST['rating'] ?? 5);
    $title = trim($_POST['title'] ?? '');
    $comments = trim($_POST['comments'] ?? '');
    $is_anonymous = isset($_POST['is_anonymous']) ? 1 : 0;
    $image_path = null;

    // Validate form
    if ($course_id === '') $errors[] = 'Please select a course.';
    if (strlen($student_name) < 3) $errors[] = 'Student name is required.';
    if (strlen($student_id) < 5) $errors[] = 'Student ID is required.';
    if ($rating < 1 || $rating > 5) $errors[] = 'Rating must be between 1 and 5.';
    if (strlen($title) < 3) $errors[] = 'Title is required.';
    if (strlen($comments) < 5) $errors[] = 'Comments are required.';

    // Upload image
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

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO feedbacks (course_id, student_name, student_id, rating, category, title, comments, image_path, is_anonymous) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$course_id, $student_name, $student_id, $rating, $category, $title, $comments, $image_path, $is_anonymous]);
        $_SESSION['flash_success'] = 'Feedback added.';
        header('Location: index.php');
        exit;
    }
}

$page_title = 'Submit Feedback';
require_once __DIR__ . '/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card p-4 bg-white">
            <h5 class="fw-bold mb-3" style="color: var(--app-blue);">Submit Course Evaluation</h5>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger py-2 small">
                    <ul class="mb-0 ps-3">
                        <?php foreach ($errors as $err): ?>
                            <li><?= e($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="create.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">

                <div class="mb-3">
                    <label class="form-label small fw-bold">Select Course *</label>
                    <select name="course_id" class="form-select form-select-sm" required>
                        <option value="">-- Choose Course --</option>
                        <?php foreach ($courses as $c): ?>
                            <option value="<?= e($c['id']) ?>" <?= ($_POST['course_id'] ?? '') == $c['id'] ? 'selected' : '' ?>>
                                <?= e($c['course_code']) ?> - <?= e($c['course_name']) ?> (<?= e($c['instructor_name']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Student Name *</label>
                        <input type="text" name="student_name" class="form-control form-control-sm" value="<?= e($_POST['student_name'] ?? ($_SESSION['user_name'] ?? '')) ?>" placeholder="e.g. Maryam Jafry" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Student ID *</label>
                        <input type="text" name="student_id" class="form-control form-control-sm" value="<?= e($_POST['student_id'] ?? '') ?>" placeholder="e.g. 202500647" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Category *</label>
                        <select name="category" class="form-select form-select-sm" required>
                            <option value="Teaching Quality">Teaching Quality</option>
                            <option value="Course Content">Course Content</option>
                            <option value="Lab & Practical">Lab & Practical</option>
                            <option value="Assessment & Grading">Assessment & Grading</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Rating (1 to 5) *</label>
                        <select name="rating" class="form-select form-select-sm" required>
                            <option value="5">5 - Excellent (5/5)</option>
                            <option value="4">4 - Very Good (4/5)</option>
                            <option value="3">3 - Good (3/5)</option>
                            <option value="2">2 - Fair (2/5)</option>
                            <option value="1">1 - Poor (1/5)</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Feedback Title *</label>
                    <input type="text" name="title" class="form-control form-control-sm" value="<?= e($_POST['title'] ?? '') ?>" placeholder="Short summary" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Comments *</label>
                    <textarea name="comments" rows="3" class="form-control form-control-sm" placeholder="Detailed feedback..." required><?= e($_POST['comments'] ?? '') ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Attachment / Image (Optional)</label>
                    <input type="file" name="image" class="form-control form-control-sm" accept="image/*">
                </div>

                <div class="form-check mb-4">
                    <input type="checkbox" name="is_anonymous" class="form-check-input" id="anon" value="1">
                    <label class="form-check-label small" for="anon">Submit Anonymously (Hide student name)</label>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                    <a href="index.php" class="btn btn-outline-secondary btn-sm">Cancel</a>
                    <button type="submit" class="btn btn-blue btn-sm px-4">Submit Feedback</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
