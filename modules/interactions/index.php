<?php
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/header.php';

$stmt = $pdo->query('SELECT i.*, c.fullname AS customer_name, u.fullname AS user_name FROM interactions i JOIN customers c ON i.customer_id = c.id LEFT JOIN users u ON u.id = i.sale_id ORDER BY i.created_at DESC');
$interactions = $stmt->fetchAll();
?>
<div class="row mb-3">
    <div class="col-md-8">
        <h2>Danh sách tương tác</h2>
    </div>
    <div class="col-md-4 text-md-end">
        <a href="add.php" class="btn btn-success">Thêm tương tác</a>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Khách hàng</th>
                <th>Loại</th>
                <th>Ngày tương tác</th>
                <th>Người thực hiện</th>
                <th>Ghi chú</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!$interactions): ?>
                <tr><td colspan="6" class="text-center">Chưa có tương tác nào.</td></tr>
            <?php else: ?>
                <?php foreach ($interactions as $interaction): ?>
                    <tr>
                        <td><?= $interaction['id'] ?></td>
                        <td><?= htmlspecialchars($interaction['customer_name']) ?></td>
                        <td><?= htmlspecialchars($interaction['type']) ?></td>
                        <td><?= htmlspecialchars($interaction['created_at']) ?></td>
                        <td><?= htmlspecialchars($interaction['user_name'] ?? '') ?></td>
                        <td><?= nl2br(htmlspecialchars($interaction['note'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php';
