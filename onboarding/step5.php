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
        header('Location: /onboarding/step4.php');
        exit;
    }

    $users = load_users();
    $username = $data['username'];

    $users[$username] = [
        'username' => $username,
        'password_hash' => $data['password_hash'],
        'name' => $data['name'] ?? '',
        'email' => $data['email'] ?? '',
        'phone' => $data['phone'] ?? '',
        'profile_photo' => $data['profile_photo'] ?? '',
        'pets' => $data['pets'] ?? [],
    ];

    save_users($users);
    $_SESSION['user'] = $username;
    unset($_SESSION['onboarding']);

    header('Location: /dashboard.php');
    exit;
}

$title = 'Onboarding: Step 5';
require __DIR__ . '/../includes/header.php';
?>
<div class="card card-step shadow-sm">
    <div class="card-body">
        <h1 class="h4">Step 5: Confirmation and Save</h1>
        <ul class="list-group mb-3">
            <li class="list-group-item"><strong>Username:</strong> <?= e($data['username']) ?></li>
            <li class="list-group-item"><strong>Name:</strong> <?= e($data['name'] ?? '') ?></li>
            <li class="list-group-item"><strong>Email:</strong> <?= e($data['email'] ?? '') ?></li>
            <li class="list-group-item"><strong>Phone:</strong> <?= e($data['phone'] ?? '') ?></li>
            <li class="list-group-item"><strong>Pet:</strong> <?= e(($data['pets'][0]['name'] ?? '') . ' (' . ($data['pets'][0]['breed'] ?? '') . ')') ?></li>
        </ul>
        <form method="post">
            <button class="btn btn-outline-secondary" name="prev" value="1">Previous</button>
            <button class="btn btn-success">Confirm & Save</button>
        </form>
    </div>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
