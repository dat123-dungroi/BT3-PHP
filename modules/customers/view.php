<?php
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/header.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT c.*, u.fullname AS sale_name FROM customers c LEFT JOIN users u ON u.id = c.sale_id WHERE c.id = ?');
$stmt->execute([$id]);
$customer = $stmt->fetch();
if (!$customer) {
    echo '<div class="alert alert-danger">Không tìm thấy khách hàng.</div>';
    require_once __DIR__ . '/../../includes/footer.php';
    exit;
}

$interactionStmt = $pdo->prepare('SELECT i.*, u.fullname AS sale_name FROM interactions i LEFT JOIN users u ON u.id = i.sale_id WHERE i.customer_id = ? ORDER BY i.created_at DESC');
$interactionStmt->execute([$id]);
$interactions = $interactionStmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = trim($_POST['type'] ?? '');
    $note = trim($_POST['note'] ?? '');
    if ($type !== '' && $note !== '') {
        $insert = $pdo->prepare('INSERT INTO interactions (customer_id, sale_id, type, note, created_at) VALUES (?, ?, ?, ?, ?)');
        $insert->execute([$id, $_SESSION['user']['id'], $type, $note, date('Y-m-d H:i:s')]);
        header('Location: /modules/customers/view.php?id=' . $id);
        exit;
    }
}
?>
<div class="card shadow-sm mb-4">
  <div class="card-body">
    <h3><?= htmlspecialchars($customer['fullname']) ?></h3>
    <p><strong>Điện thoại:</strong> <?= htmlspecialchars($customer['phone']) ?> | <strong>Email:</strong> <?= htmlspecialchars($customer['email']) ?> | <strong>Công ty:</strong> <?= htmlspecialchars($customer['company_name']) ?></p>
    <p><strong>Trạng thái:</strong> <?= htmlspecialchars($customer['status']) ?> | <strong>Sale phụ trách:</strong> <?= htmlspecialchars($customer['sale_name'] ?? 'Chưa phân công') ?></p>
  </div>
</div>
<div class="card shadow-sm mb-4">
  <div class="card-header">Thêm tương tác</div>
  <div class="card-body">
    <form method="post">
      <div class="mb-3"><label class="form-label">Loại</label><input type="text" name="type" class="form-control" placeholder="Ví dụ: Cuộc gọi"></div>
      <div class="mb-3"><label class="form-label">Ghi chú</label><textarea name="note" class="form-control" rows="3"></textarea></div>
      <button class="btn btn-primary">Lưu</button>
    </form>
  </div>
</div>
<div class="card shadow-sm">
  <div class="card-header">Lịch sử tương tác</div>
  <div class="card-body">
    <?php if ($interactions): foreach ($interactions as $item): ?>
      <div class="border rounded p-3 mb-2">
        <div class="d-flex justify-content-between"><strong><?= htmlspecialchars($item['type']) ?></strong><small><?= htmlspecialchars($item['created_at']) ?></small></div>
        <p class="mb-1 mt-2"><?= htmlspecialchars($item['note']) ?></p>
        <small>Sale: <?= htmlspecialchars($item['sale_name'] ?? '') ?></small>
      </div>
    <?php endforeach; else: ?><p class="text-muted">Chưa có ghi chú nào.</p><?php endif; ?>
  </div>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php';
