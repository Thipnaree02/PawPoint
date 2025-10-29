<?php

session_start();

// ถ้ายังไม่มี session แสดงว่ายังไม่ล็อกอิน
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

include 'config/db.php';
$token = $_GET['token'] ?? '';
$stmtResetPass = $conn->prepare("SELECT * FROM admins WHERE reset_token=? AND reset_expires > NOW()");
$stmtResetPass->execute([$token]);
$user = $stmtResetPass->fetch(PDO::FETCH_ASSOC);
if (!$user) die("ลิงก์นี้หมดอายุหรือไม่ถูกต้อง");

if (isset($_POST['reset_password'])) {
  $new = $_POST['new_password'];
  $confirm = $_POST['confirm_password'];
  if ($new === $confirm) {
    $hashed = password_hash($new, PASSWORD_DEFAULT);
    $stmtResetPass = $conn->prepare("UPDATE admins SET password=?, reset_token=NULL, reset_expires=NULL WHERE admin_id=?");
    $stmtResetPass->execute([$hashed, $user['admin_id']]);
    echo "<script>alert('เปลี่ยนรหัสผ่านสำเร็จ!');window.location='login.php';</script>";
    exit;
  } else {
    $error = "รหัสผ่านไม่ตรงกัน";
  }
}
?>
<!doctype html>
<html lang="th">
<head>
<meta charset="utf-8">
<title>รีเซ็ตรหัสผ่าน - Elivet Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height:100vh;">
  <div class="card shadow-sm p-4" style="width:380px;">
    <h4 class="mb-3 text-center">🔐 ตั้งรหัสผ่านใหม่</h4>
    <form method="post">
      <input type="password" name="new_password" class="form-control mb-2" placeholder="รหัสผ่านใหม่" required>
      <input type="password" name="confirm_password" class="form-control mb-3" placeholder="ยืนยันรหัสผ่านใหม่" required>
      <button type="submit" name="reset_password" class="btn btn-success w-100">รีเซ็ตรหัสผ่าน</button>
    </form>
    <?php if (!empty($error)): ?><div class="alert alert-danger mt-3"><?= $error ?></div><?php endif; ?>
  </div>
</body>
</html>
