<?php
session_start();
require_once __DIR__ . '/../includes/functions.php';

if (empty($_SESSION['onboarding']['username'])) {
    header('Location: /onboarding/step1.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['prev'])) {
        header('Location: /onboarding/step2.php');
        exit;
    }

    if (!empty($_FILES['profile_photo']['name'])) {
        $path = upload_image($_FILES['profile_photo'], 'profile');
        if (!$path) {
            $error = 'Invalid image file. Please upload JPG, PNG, GIF, or WEBP.';
        } else {
            $_SESSION['onboarding']['profile_photo'] = $path;
        }
    }

    if (!$error) {
        header('Location: /onboarding/step4.php');
        exit;
    }
}

$data = $_SESSION['onboarding'];
$title = 'Onboarding: Step 3';
require __DIR__ . '/../includes/header.php';
?>
<div class="card card-step shadow-sm">
    <div class="card-body">
        <h1 class="h4">Step 3: Profile Photo</h1>
        <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Upload profile photo (optional)</label>
                <input class="form-control" type="file" accept="image/*" name="profile_photo">
            </div>
            <?php if (!empty($data['profile_photo'])): ?>
                <img src="<?= e($data['profile_photo']) ?>" alt="Profile" class="profile-photo mb-3">
            <?php endif; ?>
            <div>
                <button class="btn btn-outline-secondary" name="prev" value="1">Previous</button>
                <button class="btn btn-primary">Next</button>
            </div>
        </form>
    </div>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
