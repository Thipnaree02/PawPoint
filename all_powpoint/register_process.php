<?php
session_start();
include '../Admin/config/connextdb.php'; // เชื่อมฐานข้อมูลแบบ PDO

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']); // ✅ รับค่าอีเมลจากฟอร์ม
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // ✅ ตรวจสอบว่ารหัสผ่านตรงกันหรือไม่
    if ($password !== $confirm_password) {
        echo "<script>
            alert('รหัสผ่านไม่ตรงกัน!');
            window.location.href = 'signup.php';
        </script>";
        exit();
    }

    try {
        // ✅ ตรวจสอบว่ามีชื่อผู้ใช้หรืออีเมลนี้ในระบบหรือยัง
        $check = $connextdb->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $check->execute([$username, $email]);

        if ($check->rowCount() > 0) {
            echo "<script>
                alert('ชื่อผู้ใช้หรืออีเมลนี้ถูกใช้แล้ว กรุณาใช้ข้อมูลอื่น');
                window.location.href = 'signup.php';
            </script>";
            exit();
        }

        // ✅ เข้ารหัสรหัสผ่านก่อนบันทึก (ปลอดภัยกว่า)
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // ✅ บันทึกข้อมูลลงฐานข้อมูล
        $stmt = $connextdb->prepare("
            INSERT INTO users (username, password, email, role)
            VALUES (?, ?, ?, 'staff')
        ");

        if ($stmt->execute([$username, $hashedPassword, $email])) {
            echo "<script>
                alert('สมัครสมาชิกสำเร็จ! กรุณาเข้าสู่ระบบ');
                window.location.href = 'signin.php';
            </script>";
            exit();
        } else {
            echo "<script>
                alert('เกิดข้อผิดพลาดในการสมัครสมาชิก');
                window.location.href = 'signup.php';
            </script>";
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header("Location: signup.php");
    exit();
}
?>
