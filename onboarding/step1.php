<?php
session_start();
require_once __DIR__ . '/../includes/functions.php';

$errors = [];
$data = $_SESSION['onboarding'] ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || !preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
        $errors[] = 'Username must be 3-20 characters and contain only letters, numbers, or underscores.';
    }

    if (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters long.';
    }

    if (find_user($username)) {
        $errors[] = 'Username already exists. Please choose another one.';
    }

    if (!$errors) {
        $_SESSION['onboarding']['username'] = $username;
        $_SESSION['onboarding']['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
        header('Location: /onboarding/step2.php');
        exit;
    }
}

$title = 'Onboarding: Step 1';
require __DIR__ . '/../includes/header.php';
?>
<div class="card card-step shadow-sm">
    <div class="card-body">
        <h1 class="h4">Step 1: Username and Password</h1>
        <?php foreach ($errors as $error): ?>
            <div class="alert alert-danger"><?= e($error) ?></div>
        <?php endforeach; ?>
        <form method="post">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input class="form-control" name="username" value="<?= e($data['username'] ?? '') ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Initial Password</label>
                <input class="form-control" type="password" name="password" required>
            </div>
            <button class="btn btn-primary">Next</button>
        </form>
    </div>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
