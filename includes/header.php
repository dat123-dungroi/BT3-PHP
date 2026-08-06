<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function navActive(string $target): string {
    return strpos($_SERVER['SCRIPT_NAME'], $target) !== false ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PTIT CRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="/index.php">PTIT CRM</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <?php if (!empty($_SESSION['user'])): ?>
                    <li class="nav-item"><a class="nav-link <?= navActive('dashboard') ?>" href="/index.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link <?= navActive('customers') ?>" href="/modules/customers/index.php">Khách hàng</a></li>
                    <li class="nav-item"><a class="nav-link <?= navActive('interactions') ?>" href="/modules/interactions/index.php">Tương tác</a></li>
                    <?php if (!empty($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin'): ?>
                        <li class="nav-item"><a class="nav-link <?= navActive('users') ?>" href="/modules/users/index.php">Nhân viên</a></li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav">
                <?php if (!empty($_SESSION['user'])): ?>
                    <li class="nav-item"><span class="navbar-text text-white me-3">Xin chào, <?= htmlspecialchars($_SESSION['user']['fullname']) ?></span></li>
                    <li class="nav-item"><a class="nav-link" href="/logout.php">Đăng xuất</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="/login.php">Đăng nhập</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<div class="container py-4">
