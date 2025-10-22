<?php
session_start();
include 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
  $stmt->execute([$username]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user && password_verify($password, $user['password'])) {
    $_SESSION['admin_id'] = $user['admin_id'];
    $_SESSION['fullname'] = $user['fullname'];
    $_SESSION['role'] = $user['role'];

    if ($user['role'] === 'Manager') {
      header("Location: admin.php");
    } else {
      header("Location: index.html");
    }
    exit;
  } else {
    $error = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
  }
}
?>
<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <title>เข้าสู่ระบบ - Elivet Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">
  <form method="post" class="p-4 bg-white shadow rounded" style="width:360px;">
    <h4 class="text-center mb-4">เข้าสู่ระบบ</h4>
    <?php if(isset($error)): ?>
      <div class="alert alert-danger py-2"><?=$error?></div>
    <?php endif; ?>
    <div class="mb-3">
      <label class="form-label">ชื่อผู้ใช้</label>
      <input type="text" name="username" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">รหัสผ่าน</label>
      <input type="password" name="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-success w-100">เข้าสู่ระบบ</button>
  </form>
</body>
</html>
