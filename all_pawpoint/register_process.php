<?php
session_start();
include '../myadmin/config/db.php'; // ✅ เชื่อมฐานข้อมูลแบบ PDO

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // ✅ รับค่าจากฟอร์ม และล้างช่องว่าง
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // ✅ ตรวจสอบช่องว่าง
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        echo "<script>
            alert('กรุณากรอกข้อมูลให้ครบทุกช่อง');
            window.location.href = 'signup.php';
        </script>";
        exit();
    }

    // ✅ ตรวจสอบรูปแบบอีเมล
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>
            alert('รูปแบบอีเมลไม่ถูกต้อง!');
            window.location.href = 'signup.php';
        </script>";
        exit();
    }

    // ✅ ตรวจสอบว่ารหัสผ่านตรงกันหรือไม่
    if ($password !== $confirm_password) {
        echo "<script>
            alert('รหัสผ่านไม่ตรงกัน!');
            window.location.href = 'signup.php';
        </script>";
        exit();
    }

    try {
        // ✅ ตรวจสอบว่าชื่อผู้ใช้หรืออีเมลมีในระบบแล้วหรือยัง
        $check = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $check->execute([$username, $email]);

        if ($check->rowCount() > 0) {
            echo "<script>
                alert('ชื่อผู้ใช้หรืออีเมลนี้ถูกใช้แล้ว กรุณาใช้ข้อมูลอื่น');
                window.location.href = 'signup.php';
            </script>";
            exit();
        }

        // ✅ เข้ารหัสรหัสผ่าน (เพื่อความปลอดภัย)
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // ✅ บันทึกข้อมูลลูกค้า (ไม่มี avatar, เก็บ role เป็น 'user')
        $stmt = $conn->prepare("
            INSERT INTO users (username, password, email, role, created_at)
            VALUES (?, ?, ?, 'user', NOW())
        ");

        if ($stmt->execute([$username, $hashedPassword, $email])) {
            echo "<script>
                alert('สมัครสมาชิกสำเร็จ! กรุณาเข้าสู่ระบบ');
                window.location.href = 'signin.php';
            </script>";
            exit();
        } else {
            echo "<script>
                alert('เกิดข้อผิดพลาดในการสมัครสมาชิก กรุณาลองใหม่อีกครั้ง');
                window.location.href = 'signup.php';
            </script>";
        }

    } catch (PDOException $e) {
        echo "<script>alert('เกิดข้อผิดพลาดจากระบบฐานข้อมูล: " . $e->getMessage() . "');</script>";
    }
} else {
    // ถ้าไม่ใช่การส่งแบบ POST กลับไปหน้าสมัคร
    header("Location: signup.php");
    exit();
}
?>
