<?php
session_start();
require_once __DIR__ . '/includes/functions.php';
require_login();

$users = load_users();
$username = $_SESSION['user'];
$user = $users[$username] ?? null;

if (!$user) {
    session_destroy();
    header('Location: /index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user['name'] = trim($_POST['name'] ?? '');
    $user['email'] = trim($_POST['email'] ?? '');
    $user['phone'] = trim($_POST['phone'] ?? '');

    if (!empty($_POST['new_password'])) {
        if (strlen($_POST['new_password']) < 6) {
            $error = 'New password must be at least 6 characters.';
        } else {
            $user['password_hash'] = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        }
    }

    if (!$error && !empty($_FILES['profile_photo']['name'])) {
        $path = upload_image($_FILES['profile_photo'], 'profile');
        if (!$path) {
            $error = 'Invalid profile image file.';
        } else {
            $user['profile_photo'] = $path;
        }
    }

    $pet = $user['pets'][0] ?? ['name' => '', 'breed' => '', 'age' => '', 'photo' => ''];
    $pet['name'] = trim($_POST['pet_name'] ?? '');
    $pet['breed'] = trim($_POST['pet_breed'] ?? '');
    $pet['age'] = trim($_POST['pet_age'] ?? '');

    if (!$error && !empty($_FILES['pet_photo']['name'])) {
        $petPath = upload_image($_FILES['pet_photo'], 'pets');
        if (!$petPath) {
            $error = 'Invalid pet image file.';
        } else {
            $pet['photo'] = $petPath;
        }
    }

    if (!$error) {
        $user['pets'] = [$pet];
        $users[$username] = $user;
        save_users($users);
        header('Location: /dashboard.php');
        exit;
    }
}

$title = 'Edit Profile';
require __DIR__ . '/includes/header.php';
$pet = $user['pets'][0] ?? ['name' => '', 'breed' => '', 'age' => '', 'photo' => ''];
?>
<div class="card shadow-sm">
    <div class="card-body">
        <h1 class="h4">Edit Profile</h1>
        <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Username (read-only)</label>
                <input class="form-control" value="<?= e($username) ?>" readonly>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Full Name</label>
                    <input class="form-control" name="name" value="<?= e($user['name']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input class="form-control" type="email" name="email" value="<?= e($user['email']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Phone</label>
                    <input class="form-control" name="phone" value="<?= e($user['phone']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">New Password (optional)</label>
                    <input class="form-control" type="password" name="new_password">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Profile Photo</label>
                    <input class="form-control" type="file" name="profile_photo" accept="image/*">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Pet Photo</label>
                    <input class="form-control" type="file" name="pet_photo" accept="image/*">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Pet Name</label>
                    <input class="form-control" name="pet_name" value="<?= e($pet['name']) ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Pet Breed</label>
                    <input class="form-control" name="pet_breed" value="<?= e($pet['breed']) ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Pet Age</label>
                    <input class="form-control" name="pet_age" value="<?= e((string)$pet['age']) ?>" required>
                </div>
            </div>
            <div class="mt-3 d-flex gap-2">
                <button class="btn btn-primary">Save Changes</button>
                <a class="btn btn-outline-danger" href="/delete.php" data-confirm="Delete your profile permanently?">Delete Profile</a>
            </div>
        </form>
    </div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
