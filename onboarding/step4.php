<?php
session_start();
require_once __DIR__ . '/../includes/functions.php';

if (empty($_SESSION['onboarding']['username'])) {
    header('Location: /onboarding/step1.php');
    exit;
}

$error = '';
$data = $_SESSION['onboarding'];
$pet = $data['pets'][0] ?? ['name' => '', 'breed' => '', 'age' => '', 'photo' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['prev'])) {
        header('Location: /onboarding/step3.php');
        exit;
    }

    $pet['name'] = trim($_POST['pet_name'] ?? '');
    $pet['breed'] = trim($_POST['pet_breed'] ?? '');
    $pet['age'] = trim($_POST['pet_age'] ?? '');

    if ($pet['name'] === '' || $pet['breed'] === '' || $pet['age'] === '') {
        $error = 'Please complete all pet fields.';
    }

    if (!$error && !empty($_FILES['pet_photo']['name'])) {
        $photoPath = upload_image($_FILES['pet_photo'], 'pets');
        if (!$photoPath) {
            $error = 'Invalid pet photo. Please upload JPG, PNG, GIF, or WEBP.';
        } else {
            $pet['photo'] = $photoPath;
        }
    }

    if (!$error) {
        $_SESSION['onboarding']['pets'] = [$pet];
        header('Location: /onboarding/step5.php');
        exit;
    }
}

$title = 'Onboarding: Step 4';
require __DIR__ . '/../includes/header.php';
?>
<div class="card card-step shadow-sm">
    <div class="card-body">
        <h1 class="h4">Step 4: Pet Info</h1>
        <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Pet Name</label>
                <input class="form-control" name="pet_name" value="<?= e($pet['name']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Breed</label>
                <input class="form-control" name="pet_breed" value="<?= e($pet['breed']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Age</label>
                <input class="form-control" name="pet_age" value="<?= e($pet['age']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Pet Photo (optional)</label>
                <input class="form-control" type="file" accept="image/*" name="pet_photo">
            </div>
            <?php if (!empty($pet['photo'])): ?><img src="<?= e($pet['photo']) ?>" class="pet-photo mb-3" alt="Pet"><?php endif; ?>
            <div>
                <button class="btn btn-outline-secondary" name="prev" value="1">Previous</button>
                <button class="btn btn-primary">Next</button>
            </div>
        </form>
    </div>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
