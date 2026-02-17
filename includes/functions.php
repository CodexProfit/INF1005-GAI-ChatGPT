<?php

const USERS_FILE = __DIR__ . '/../data/users.csv';

function ensure_storage(): void
{
    $dirs = [
        __DIR__ . '/../data',
        __DIR__ . '/../uploads/profile',
        __DIR__ . '/../uploads/pets',
    ];

    foreach ($dirs as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
    }

    if (!file_exists(USERS_FILE)) {
        $fp = fopen(USERS_FILE, 'w');
        fputcsv($fp, ['username', 'password_hash', 'name', 'email', 'phone', 'profile_photo', 'pets_json']);
        fclose($fp);
    }
}

function load_users(): array
{
    ensure_storage();

    $users = [];
    if (($fp = fopen(USERS_FILE, 'r')) !== false) {
        $headers = fgetcsv($fp);
        while (($row = fgetcsv($fp)) !== false) {
            if (count($row) < 7) {
                continue;
            }
            $user = array_combine($headers, $row);
            $user['pets'] = json_decode($user['pets_json'] ?: '[]', true) ?: [];
            $users[$user['username']] = $user;
        }
        fclose($fp);
    }

    return $users;
}

function save_users(array $users): void
{
    ensure_storage();

    $fp = fopen(USERS_FILE, 'w');
    fputcsv($fp, ['username', 'password_hash', 'name', 'email', 'phone', 'profile_photo', 'pets_json']);

    foreach ($users as $user) {
        fputcsv($fp, [
            $user['username'],
            $user['password_hash'],
            $user['name'],
            $user['email'],
            $user['phone'],
            $user['profile_photo'],
            json_encode($user['pets'], JSON_UNESCAPED_SLASHES),
        ]);
    }

    fclose($fp);
}

function find_user(string $username): ?array
{
    $users = load_users();
    return $users[$username] ?? null;
}

function is_logged_in(): bool
{
    return !empty($_SESSION['user']);
}

function require_login(): void
{
    if (!is_logged_in()) {
        header('Location: /index.php');
        exit;
    }
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function nav_step(int $current): array
{
    $steps = [1, 2, 3, 4, 5];
    $prev = $current > 1 ? $current - 1 : null;
    $next = $current < 5 ? $current + 1 : null;
    return [$prev, $next, $steps];
}

function upload_image(array $file, string $subfolder): ?string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        return null;
    }

    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];
    $mime = mime_content_type($file['tmp_name']);

    if (!isset($allowed[$mime])) {
        return null;
    }

    $ext = $allowed[$mime];
    $filename = uniqid('img_', true) . '.' . $ext;
    $targetDir = __DIR__ . '/../uploads/' . $subfolder;

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $targetPath = $targetDir . '/' . $filename;
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return '/uploads/' . $subfolder . '/' . $filename;
    }

    return null;
}
