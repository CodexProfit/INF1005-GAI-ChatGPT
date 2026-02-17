<?php
session_start();
require_once __DIR__ . '/includes/functions.php';
require_login();

$users = load_users();
$current = $_SESSION['user'];
$title = 'Community Profiles';
require __DIR__ . '/includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Community Member Profiles</h1>
    <span class="badge text-bg-primary">Logged in as <?= e($current) ?></span>
</div>
<div class="row g-3">
    <?php foreach ($users as $user): ?>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <?php if (!empty($user['profile_photo'])): ?>
                            <img src="<?= e($user['profile_photo']) ?>" class="profile-photo" alt="Profile">
                        <?php else: ?>
                            <div class="profile-photo bg-light d-flex align-items-center justify-content-center">🐾</div>
                        <?php endif; ?>
                        <div>
                            <h2 class="h5 mb-0"><?= e($user['name'] ?: $user['username']) ?></h2>
                            <small class="text-muted">@<?= e($user['username']) ?></small>
                        </div>
                    </div>
                    <p class="mb-1"><strong>Email:</strong> <?= e($user['email']) ?></p>
                    <p class="mb-2"><strong>Phone:</strong> <?= e($user['phone']) ?></p>
                    <?php foreach ($user['pets'] as $pet): ?>
                        <div class="border rounded p-2 mb-2">
                            <p class="mb-1"><strong>Pet:</strong> <?= e($pet['name'] ?? '') ?></p>
                            <p class="mb-1"><strong>Breed:</strong> <?= e($pet['breed'] ?? '') ?></p>
                            <p class="mb-1"><strong>Age:</strong> <?= e((string)($pet['age'] ?? '')) ?></p>
                            <?php if (!empty($pet['photo'])): ?>
                                <img src="<?= e($pet['photo']) ?>" class="pet-photo" alt="Pet photo">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
