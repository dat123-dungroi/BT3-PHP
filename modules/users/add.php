<?php
require_once __DIR__ . '/../../includes/auth_check.php';
if (!isAdmin()) { header('Location: /index.php'); exit; }
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/header.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = $_POST['role'] ?? 'sale';

    if ($username === '' || $password === '' || $fullname === '') {
        $error = 'Vui lòng nhập đầy đủ thông tin.';
    } else {
        $stmt = $pdo->prepare('INSERT INTO users (username, password, fullname, email, role) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT), $fullname, $email, $role]);
        header('Location: /modules/users/index.php');
        exit;
    }
}
?>
<div class="card shadow-sm">
  <div class="card-header">Thêm nhân viên Sale</div>
  <div class="card-body">
    <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <form method="post">
      <div class="mb-3"><label class="form-label">Tên đăng nhập</label><input type="text" name="username" class="form-control"></div>
      <div class="mb-3"><label class="form-label">Mật khẩu</label><input type="password" name="password" class="form-control"></div>
      <div class="mb-3"><label class="form-label">Họ tên</label><input type="text" name="fullname" class="form-control"></div>
      <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control"></div>
      <div class="mb-3"><label class="form-label">Vai trò</label><select name="role" class="form-select"><option value="staff">Sales</option><option value="admin">Admin</option></select></div>
      <button class="btn btn-primary">Lưu</button>
      <a href="/modules/users/index.php" class="btn btn-secondary">Hủy</a>
    </form>
  </div>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php';
