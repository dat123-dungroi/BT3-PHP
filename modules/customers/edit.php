<?php
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare('SELECT * FROM customers WHERE id = ?');
$stmt->execute([$id]);
$customer = $stmt->fetch();
if (!$customer) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $companyName = trim($_POST['company_name'] ?? '');
    $status = $_POST['status'] ?? 'Mới tiếp cận';

    if ($fullname === '') {
        $error = 'Tên khách hàng không được để trống.';
    } else {
        $update = $pdo->prepare('UPDATE customers SET fullname = ?, email = ?, phone = ?, company_name = ?, status = ? WHERE id = ?');
        $update->execute([$fullname, $email, $phone, $companyName, $status, $id]);
        header('Location: index.php');
        exit;
    }
}
?>
<div class="row">
    <div class="col-12">
        <h2>Sửa thông tin khách hàng</h2>
    </div>
</div>
<?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<form method="post">
    <div class="mb-3">
        <label class="form-label">Tên khách hàng</label>
        <input type="text" name="fullname" class="form-control" value="<?= htmlspecialchars($_POST['fullname'] ?? $customer['fullname']) ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($_POST['email'] ?? $customer['email']) ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Điện thoại</label>
        <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($_POST['phone'] ?? $customer['phone']) ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Công ty</label>
        <input type="text" name="company_name" class="form-control" value="<?= htmlspecialchars($_POST['company_name'] ?? $customer['company_name']) ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Trạng thái</label>
        <select name="status" class="form-select">
            <?php foreach (['Mới tiếp cận' => 'Mới tiếp cận', 'Đang tư vấn' => 'Đang tư vấn', 'Đã gửi báo giá' => 'Đã gửi báo giá', 'Chốt hợp đồng' => 'Chốt hợp đồng', 'Thất bại' => 'Thất bại'] as $key => $label): ?>
                <option value="<?= $key ?>" <?= ((($_POST['status'] ?? $customer['status']) === $key) ? 'selected' : '') ?>><?= $label ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Cập nhật</button>
    <a href="index.php" class="btn btn-secondary">Hủy</a>
</form>
<?php require_once __DIR__ . '/../../includes/footer.php';
