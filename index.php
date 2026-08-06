<?php
require_once __DIR__ . '/includes/auth_check.php';
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/header.php';

if ($_SESSION['user']['role'] === 'admin') {
    require __DIR__ . '/modules/dashboard/admin_dashboard.php';
} else {
    require __DIR__ . '/modules/dashboard/sale_dashboard.php';
}

require_once __DIR__ . '/includes/footer.php';
