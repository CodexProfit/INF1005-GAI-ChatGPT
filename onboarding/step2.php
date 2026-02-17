<?php
session_start();
require_once __DIR__ . '/../includes/functions.php';

if (empty($_SESSION['onboarding']['username'])) {
    header('Location: /onboarding/step1.php');
    exit;
}

$data = $_SESSION['onboarding'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['prev'])) {
        header('Location: /onboarding/step1.php');
        exit;
    }

    $_SESSION['onboarding']['name'] = trim($_POST['name'] ?? '');
    $_SESSION['onboarding']['email'] = trim($_POST['email'] ?? '');
    $_SESSION['onboarding']['phone'] = trim($_POST['phone'] ?? '');

    header('Location: /onboarding/step3.php');
    exit;
}

$title = 'Onboarding: Step 2';
require __DIR__ . '/../includes/header.php';
?>
<div class="card card-step shadow-sm">
    <div class="card-body">
        <h1 class="h4">Step 2: Personal Info</h1>
        <form method="post">
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input class="form-control" name="name" value="<?= e($data['name'] ?? '') ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input class="form-control" type="email" name="email" value="<?= e($data['email'] ?? '') ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input class="form-control" name="phone" value="<?= e($data['phone'] ?? '') ?>" required>
            </div>
            <button class="btn btn-outline-secondary" name="prev" value="1">Previous</button>
            <button class="btn btn-primary">Next</button>
        </form>
    </div>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
