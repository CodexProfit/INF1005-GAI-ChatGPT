<?php
session_start();
require_once __DIR__ . '/includes/functions.php';

if (is_logged_in()) {
    header('Location: /dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $user = find_user($username);
    if (!$user || !password_verify($password, $user['password_hash'])) {
        $error = 'Invalid username or password.';
    } else {
        $_SESSION['user'] = $username;
        header('Location: /dashboard.php');
        exit;
    }
}

$title = 'Login';
require __DIR__ . '/includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="h4 mb-3">Login</h1>
                <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input class="form-control" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input class="form-control" type="password" name="password" required>
                    </div>
                    <button class="btn btn-primary">Log In</button>
                    <a class="btn btn-link" href="/onboarding/step1.php">New user? Start onboarding</a>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
