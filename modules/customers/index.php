<?php
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/header.php';

$keyword = trim($_GET['keyword'] ?? '');
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 8;
$offset = ($page - 1) * $perPage;
$params = [];
$where = '';
if (!isAdmin()) {
    $where = 'WHERE sale_id = :user_id';
    $params[':user_id'] = $_SESSION['user']['id'];
}
if ($keyword !== '') {
    $keywordPart = ' (fullname REGEXP :k1 OR email REGEXP :k2 OR phone REGEXP :k3 OR company_name REGEXP :k4)';
    if ($where === '') {
        $where = 'WHERE' . $keywordPart;
    } else {
        $where .= ' AND' . $keywordPart;
    }
    $params[':k1'] = "[[:<:]]" . $keyword . "[[:>:]]";
    $params[':k2'] = "[[:<:]]" . $keyword . "[[:>:]]";
    $params[':k3'] = "[[:<:]]" . $keyword . "[[:>:]]";
    $params[':k4'] = "[[:<:]]" . $keyword . "[[:>:]]";
}
$totalStmt = $pdo->prepare("SELECT COUNT(*) FROM customers $where");
$totalStmt->execute($params);
$totalRows = $totalStmt->fetchColumn();
$totalPages = max(1, ceil($totalRows / $perPage));

$sql = "SELECT * FROM customers $where ORDER BY created_at DESC LIMIT $offset, $perPage";
$listStmt = $pdo->prepare($sql);
foreach ($params as $key => $value) {
    $listStmt->bindValue($key, $value, PDO::PARAM_STR);
}
$listStmt->execute();
$customers = $listStmt->fetchAll();
?>
<div class="row mb-3">
    <div class="col-md-8">
        <h2>Danh sách khách hàng</h2>
    </div>
    <div class="col-md-4 text-md-end">
        <a href="add.php" class="btn btn-success">Thêm khách hàng</a>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-12">
        <form method="get" class="row g-2">
            <div class="col-md-9">
                <input type="text" name="keyword" class="form-control" placeholder="Tìm theo tên, email, điện thoại, công ty" value="<?= htmlspecialchars($keyword) ?>">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">Tìm kiếm</button>
            </div>
        </form>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Tên</th>
                <th>Email</th>
                <th>Điện thoại</th>
                <th>Công ty</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!$customers): ?>
                <tr><td colspan="8" class="text-center">Không tìm thấy khách hàng.</td></tr>
            <?php else: ?>
                <?php foreach ($customers as $customer): ?>
                    <tr>
                        <td><?= $customer['id'] ?></td>
                        <td><?= htmlspecialchars($customer['fullname']) ?></td>
                        <td><?= htmlspecialchars($customer['email']) ?></td>
                        <td><?= htmlspecialchars($customer['phone']) ?></td>
                        <td><?= htmlspecialchars($customer['company_name']) ?></td>
                        <td><?= htmlspecialchars($customer['status']) ?></td>
                        <td><?= htmlspecialchars($customer['created_at']) ?></td>
                        <td>
                            <a href="view.php?id=<?= $customer['id'] ?>" class="btn btn-sm btn-info">Chi tiết</a>
                            <a href="edit.php?id=<?= $customer['id'] ?>" class="btn btn-sm btn-primary">Sửa</a>
                            <a href="delete.php?id=<?= $customer['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa khách hàng này?');">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<nav>
    <ul class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>&keyword=<?= urlencode($keyword) ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>
<?php require_once __DIR__ . '/../../includes/footer.php';
