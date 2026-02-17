<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? e($title) : 'Pet Lovers Community'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
        <a class="navbar-brand" href="/dashboard.php">🐾 Pet Lovers Community</a>
        <div class="d-flex gap-2">
            <?php if (!empty($_SESSION['user'])): ?>
                <a class="btn btn-light btn-sm" href="/edit.php">Edit Profile</a>
                <a class="btn btn-outline-light btn-sm" href="/logout.php">Logout</a>
            <?php else: ?>
                <a class="btn btn-light btn-sm" href="/index.php">Login</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
<main class="container pb-5">
