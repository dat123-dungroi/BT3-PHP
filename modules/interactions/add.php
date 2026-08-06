<?php
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/header.php';

$error = '';
$customers = $pdo->query('SELECT id, fullname FROM customers ORDER BY fullname ASC')->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id = (int)($_POST['customer_id'] ?? 0);
    $type = $_POST['type'] ?? 'Cuộc gọi';
    $note = trim($_POST['note'] ?? '');
    $sale_id = $_SESSION['user']['id'] ?? null;

    if ($customer_id <= 0 || $note === '' || $sale_id === null) {
        $error = 'Vui lòng chọn khách hàng và nhập ghi chú.';
    } else {
        $stmt = $pdo->prepare('INSERT INTO interactions (customer_id, sale_id, type, note, created_at) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$customer_id, $sale_id, $type, $note, date('Y-m-d H:i:s')]);
        header('Location: index.php');
        exit;
    }
}
?>
<div class="row">
    <div class="col-12">
        <h2>Thêm tương tác mới</h2>
    </div>
</div>
<?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<form method="post">
    <div class="mb-3">
        <label class="form-label">Khách hàng</label>
        <select name="customer_id" class="form-select">
            <option value="">-- Chọn khách hàng --</option>
            <?php foreach ($customers as $customer): ?>
                <option value="<?= $customer['id'] ?>" <?= ((int)($_POST['customer_id'] ?? 0) === $customer['id'] ? 'selected' : '') ?>><?= htmlspecialchars($customer['fullname']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Loại tương tác</label>
        <select name="type" class="form-select">
            <?php foreach (['Cuộc gọi' => 'Cuộc gọi', 'Email' => 'Email', 'Gặp trực tiếp' => 'Gặp trực tiếp', 'Ghi chú' => 'Ghi chú'] as $key => $label): ?>
                <option value="<?= $key ?>" <?= (($_POST['type'] ?? '') === $key ? 'selected' : '') ?>><?= $label ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Ghi chú</label>
        <textarea name="note" class="form-control" rows="4"><?= htmlspecialchars($_POST['note'] ?? '') ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Lưu</button>
    <a href="index.php" class="btn btn-secondary">Hủy</a>
</form>
<?php require_once __DIR__ . '/../../includes/footer.php';
