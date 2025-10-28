<?php
session_start();
include 'config/db.php';

// ✅ ตรวจสอบการล็อกอิน
if (isset($_POST['login'])) {
  $username = trim($_POST['username']);
  $password = trim($_POST['password']);

  $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
  $stmt->execute([$username]);
  $admin = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($admin && password_verify($password, $admin['password'])) {
    $_SESSION['admin_id'] = $admin['admin_id'];
    $_SESSION['admin_name'] = $admin['name'];
    // ✅ ตรวจสอบว่าฐานข้อมูลเก็บ path หรือแค่ชื่อไฟล์
    if (!empty($admin['profile_image'])) {
      if (strpos($admin['profile_image'], 'uploads/') !== false) {
        // ถ้ามี path อยู่แล้ว เช่น uploads/admins/xxx.jpg
        $_SESSION['admin_image'] = $admin['profile_image'];
      } else {
        // ถ้ามีแค่ชื่อไฟล์ เช่น xxx.jpg
        $_SESSION['admin_image'] = 'uploads/admins/' . basename($admin['profile_image']);
      }
    } else {
      // ถ้าไม่มีรูป
      $_SESSION['admin_image'] = 'assets/images/default_user.png';
    }

    header("Location: index.php");
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
  <title>เข้าสู่ระบบ | PowPoint Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    * {
      font-family: 'Noto Sans Thai', sans-serif;
    }

    body {
      background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .login-card {
      background: white;
      border-radius: 16px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      width: 400px;
      animation: fadeIn 0.6s ease;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .login-header {
      background: #4caf50;
      color: white;
      padding: 1.5rem;
      text-align: center;
    }

    .login-header h4 {
      margin: 0;
      font-weight: 700;
    }

    .login-body {
      padding: 2rem;
    }

    .form-control {
      border-radius: 10px;
      border: 1px solid #ccc;
      padding: 10px 14px;
    }

    .form-control:focus {
      border-color: #4caf50;
      box-shadow: 0 0 0 0.15rem rgba(76, 175, 80, 0.25);
    }

    .btn-login {
      background: #43a047;
      color: white;
      border-radius: 10px;
      padding: 10px;
      transition: 0.3s;
    }

    .btn-login:hover {
      background: #388e3c;
    }

    .login-footer {
      text-align: center;
      padding-top: 1rem;
      font-size: 0.9rem;
      color: #666;
    }

    .login-footer a {
      text-decoration: none;
      color: #388e3c;
      font-weight: 600;
    }

    .paw-icon {
      font-size: 3rem;
      color: white;
      display: inline-block;
      background: rgba(255, 255, 255, 0.2);
      border-radius: 50%;
      width: 65px;
      height: 65px;
      line-height: 65px;
    }

    @media (max-width: 500px) {
      .login-card {
        width: 90%;
      }
    }
  </style>
</head>

<body>
  <div class="login-card">
    <div class="login-header">
      <div class="paw-icon mb-2"><i class="bi bi-heart-fill"></i></div>
      <h4>PowPoint Admin</h4>
      <p class="m-0 small text-white-50">ระบบจัดการคลินิกสัตว์เลี้ยง</p>
    </div>

    <div class="login-body">
      <form method="post">
        <div class="mb-3 text-start">
          <label class="form-label">ชื่อผู้ใช้</label>
          <input type="text" name="username" class="form-control" placeholder="กรอกชื่อผู้ใช้" required>
        </div>
        <div class="mb-3 text-start">
          <label class="form-label">รหัสผ่าน</label>
          <input type="password" name="password" class="form-control" placeholder="กรอกรหัสผ่าน" required>
        </div>

        <?php if (!empty($error)): ?>
          <div class="alert alert-danger text-center py-2"><?= $error ?></div>
        <?php endif; ?>

        <button type="submit" name="login" class="btn btn-login w-100 fw-semibold">เข้าสู่ระบบ</button>
      </form>

      <div class="login-footer">
        <a href="forgot_password.php">ลืมรหัสผ่าน?</a>
      </div>
    </div>
  </div>
</body>

</html>