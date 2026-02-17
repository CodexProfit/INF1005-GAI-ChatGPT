<?php
session_start();
require_once __DIR__ . '/includes/functions.php';
require_login();

$users = load_users();
$username = $_SESSION['user'];

if (isset($users[$username])) {
    unset($users[$username]);
    save_users($users);
}

session_destroy();
header('Location: /index.php');
exit;
