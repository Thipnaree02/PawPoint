<?php
session_start();
include '../Admin/config/connextdb.php'; // เชื่อมฐานข้อมูลแบบ PDO

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = trim($_POST['username']); // ใช้ตัวเดียว รับได้ทั้งชื่อหรืออีเมล
    $password = trim($_POST['password']);

    try {
        // ✅ ค้นหาจากทั้ง username และ email
        $stmt = $connextdb->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$login, $login]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // ✅ ตรวจสอบว่าพบรหัสผู้ใช้และรหัสผ่านถูกต้อง
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            // ✅ แยกหน้า admin/staff
            if ($user['role'] === 'admin') {
                header("Location: ../Admin/index.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            echo "<script>
                alert('ชื่อผู้ใช้หรืออีเมล หรือรหัสผ่านไม่ถูกต้อง');
                window.location.href = 'signin.php';
            </script>";
            exit();
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header("Location: signin.php");
    exit();
}
?>