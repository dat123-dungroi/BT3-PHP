<?php
require_once __DIR__ . '/../../includes/auth_check.php';
if (!isAdmin()) { header('Location: /index.php'); exit; }
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/header.php';

$users = $pdo->query('SELECT id, username, fullname, email, role FROM users ORDER BY id')->fetchAll();
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Danh sách nhân viên</h3>
  <a href="/modules/users/add.php" class="btn btn-success">Thêm nhân viên</a>
</div>
<div class="card shadow-sm">
  <div class="card-body">
    <table class="table table-bordered">
      <thead>
        <tr><th>#</th><th>Tên đăng nhập</th><th>Họ tên</th><th>Email</th><th>Vai trò</th></tr>
      </thead>
      <tbody>
        <?php foreach ($users as $user): ?>
          <tr>
            <td><?= (int)$user['id'] ?></td>
            <td><?= htmlspecialchars($user['username']) ?></td>
            <td><?= htmlspecialchars($user['fullname']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><span class="badge bg-primary"><?= htmlspecialchars($user['role']) ?></span></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php';
