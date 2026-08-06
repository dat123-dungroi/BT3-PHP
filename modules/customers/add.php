<?php
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/header.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $companyName = trim($_POST['company_name'] ?? '');
    $status = $_POST['status'] ?? 'Mới tiếp cận';
    $saleId = $_SESSION['user']['id'] ?? null;

    if ($fullname === '') {
        $error = 'Tên khách hàng không được để trống.';
    } elseif ($saleId === null) {
        $error = 'Người dùng chưa đăng nhập.';
    } else {
        $stmt = $pdo->prepare('INSERT INTO customers (fullname, email, phone, company_name, status, sale_id) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([$fullname, $email, $phone, $companyName, $status, $saleId]);
        header('Location: index.php');
        exit;
    }
}
?>
<div class="row">
    <div class="col-12">
        <h2>Thêm mới khách hàng</h2>
    </div>
</div>
<?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<form method="post">
    <div class="mb-3">
        <label class="form-label">Tên khách hàng</label>
        <input type="text" name="fullname" class="form-control" value="<?= htmlspecialchars($_POST['fullname'] ?? '') ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Điện thoại</label>
        <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Công ty</label>
        <input type="text" name="company_name" class="form-control" value="<?= htmlspecialchars($_POST['company_name'] ?? '') ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Trạng thái</label>
        <select name="status" class="form-select">
            <?php foreach (['Mới tiếp cận' => 'Mới tiếp cận', 'Đang tư vấn' => 'Đang tư vấn', 'Đã gửi báo giá' => 'Đã gửi báo giá', 'Chốt hợp đồng' => 'Chốt hợp đồng', 'Thất bại' => 'Thất bại'] as $key => $label): ?>
                <option value="<?= $key ?>" <?= (($_POST['status'] ?? '') === $key) ? 'selected' : '' ?>><?= $label ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Lưu</button>
    <a href="index.php" class="btn btn-secondary">Hủy</a>
</form>
<?php require_once __DIR__ . '/../../includes/footer.php';
