<?php
include 'config/db.php';
$message = "";

if (isset($_POST['reset_request'])) {
  $email = trim($_POST['email']);
  $stmtForgotPass = $conn->prepare("SELECT * FROM admins WHERE email = ?");
  $stmtForgotPass->execute([$email]);
  $user = $stmtForgotPass->fetch(PDO::FETCH_ASSOC);

  if ($user) {
    $token = bin2hex(random_bytes(16));
    $stmtForgotPass = $conn->prepare("UPDATE admins SET reset_token=?, reset_expires=DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email=?");
    $stmtForgotPass->execute([$token, $email]);
    $resetLink = "http://localhost/PowPoint/myadmin/reset_password.php?token=" . $token;
    $message = "✅ ลิงก์รีเซ็ตรหัสผ่านของคุณคือ:<br><a href='$resetLink'>$resetLink</a>";
  } else {
    $message = "❌ ไม่พบอีเมลนี้ในระบบ";
  }
}
?>
<!doctype html>
<html lang="th">
<head>
<meta charset="utf-8">
<title>ลืมรหัสผ่าน - Elivet Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height:100vh;">
  <div class="card shadow-sm p-4" style="width:380px;">
    <h4 class="mb-3 text-center">🔑 ลืมรหัสผ่าน</h4>
    <form method="post">
      <input type="email" name="email" class="form-control mb-3" placeholder="กรอกอีเมลของคุณ" required>
      <button type="submit" name="reset_request" class="btn btn-success w-100">ขอลิงก์รีเซ็ตรหัสผ่าน</button>
    </form>
    <?php if (!empty($message)): ?>
      <div class="alert alert-info mt-3"><?= $message ?></div>
    <?php endif; ?>
    <div class="text-center mt-2"><a href="login.php">ย้อนกลับเข้าสู่ระบบ</a></div>
  </div>
</body>
</html>
