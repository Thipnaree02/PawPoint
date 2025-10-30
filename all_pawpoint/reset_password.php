<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../myadmin/config/db.php'; // ✅ ตรวจ path ให้ถูกต้อง

$alert = ""; // สำหรับส่ง SweetAlert หลัง HTML โหลด

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (empty($username) || empty($new_password) || empty($confirm_password)) {
        $alert = "missing";
    } elseif ($new_password !== $confirm_password) {
        $alert = "mismatch";
    } else {
        // ตรวจสอบว่ามีผู้ใช้นี้ในฐานข้อมูลไหม
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // เข้ารหัสรหัสผ่านใหม่
            $hashed = password_hash($new_password, PASSWORD_DEFAULT);

            // อัปเดตรหัสผ่านใหม่ในฐานข้อมูล
            $update = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
            $update->execute([$hashed, $user['user_id']]);

            $alert = "success"; // ✅ สำเร็จ
        } else {
            $alert = "notfound";
        }
    }
}
?>

<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <title>ลืมรหัสผ่าน - PawPoint</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background: linear-gradient(to bottom, #ffffff, #fef6e4);
            font-family: 'Prompt', sans-serif;
        }

        .reset-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            max-width: 420px;
            margin: 5rem auto;
            text-align: center;
        }

        .btn-orange {
            background-color: #ff914d;
            border: none;
            color: white;
            border-radius: 10px;
            font-weight: 600;
            padding: 0.6rem;
            transition: 0.3s;
        }

        .btn-orange:hover {
            background-color: #ff7b1a;
        }

        a {
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="reset-card">
        <img src="https://cdn-icons-png.flaticon.com/512/616/616408.png" alt="Pet Icon" width="70">
        <h4 class="mt-2 mb-3">รีเซ็ตรหัสผ่าน</h4>

        <form method="POST">
            <div class="mb-3 text-start">
                <label class="form-label">ชื่อผู้ใช้หรืออีเมล</label>
                <input type="text" name="username" class="form-control" required placeholder="กรอกชื่อผู้ใช้หรืออีเมล">
            </div>

            <div class="mb-3 text-start">
                <label class="form-label">รหัสผ่านใหม่</label>
                <input type="password" name="new_password" class="form-control" required placeholder="รหัสผ่านใหม่">
            </div>

            <div class="mb-3 text-start">
                <label class="form-label">ยืนยันรหัสผ่านใหม่</label>
                <input type="password" name="confirm_password" class="form-control" required placeholder="ยืนยันรหัสผ่านใหม่">
            </div>

            <button type="submit" class="btn-orange w-100 mt-2">บันทึกรหัสผ่านใหม่</button>
            <p class="mt-3"><a href="signin.php">← กลับไปหน้าเข้าสู่ระบบ</a></p>
        </form>
    </div>

    <!-- 🔔 SweetAlert แสดงหลังโหลด HTML -->
    <script>
        const alertType = "<?= $alert ?>";
        if (alertType === "missing") {
            Swal.fire({
                icon: 'warning',
                title: 'กรอกข้อมูลไม่ครบ!',
                text: 'กรุณากรอกทุกช่องให้ครบก่อนดำเนินการ',
                confirmButtonColor: '#ff914d'
            });
        } else if (alertType === "mismatch") {
            Swal.fire({
                icon: 'error',
                title: 'รหัสผ่านไม่ตรงกัน!',
                text: 'กรุณากรอกให้ตรงกันทั้งสองช่อง',
                confirmButtonColor: '#ff914d'
            });
        } else if (alertType === "notfound") {
            Swal.fire({
                icon: 'error',
                title: 'ไม่พบบัญชีผู้ใช้!',
                text: 'กรุณาตรวจสอบชื่อผู้ใช้หรืออีเมลอีกครั้ง',
                confirmButtonColor: '#ff914d'
            });
        } else if (alertType === "success") {
            Swal.fire({
                icon: 'success',
                title: 'เปลี่ยนรหัสผ่านสำเร็จ!',
                text: 'ระบบจะพาคุณกลับไปหน้าเข้าสู่ระบบ',
                confirmButtonColor: '#ff914d',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                window.location.href = 'signin.php';
            });
        }
    </script>
</body>
</html>
