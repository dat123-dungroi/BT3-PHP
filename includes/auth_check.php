<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['user'])) {
    header('Location: /login.php');
    exit;
}

function isAdmin(): bool {
    return !empty($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin';
}
