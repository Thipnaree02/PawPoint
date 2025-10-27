<?php
session_start();
include '../myadmin/config/db.php'; // ✅ เชื่อมฐานข้อมูลแบบ PDO

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = trim($_POST['username']); // ใช้รับได้ทั้งชื่อผู้ใช้หรืออีเมล
    $password = trim($_POST['password']);

    try {
        // ✅ ค้นหาผู้ใช้จากทั้ง username และ email
        $stmt = $connextdb->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$login, $login]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // ✅ ตรวจสอบชื่อผู้ใช้และรหัสผ่าน
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            // ✅ แยกหน้า admin / user
            if ($user['role'] === 'admin') {
                header("Location: ../Admin/index.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            // ❌ ถ้า login ไม่ผ่าน → แสดง SweetAlert2
            echo "
            <!DOCTYPE html>
            <html lang='th'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>เข้าสู่ระบบไม่สำเร็จ</title>
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                <style>
                    body {
                        font-family: 'Prompt', sans-serif;
                        background-color: #f9f9f9;
                    }
                </style>
            </head>
            <body>
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'เข้าสู่ระบบไม่สำเร็จ',
                        text: 'ชื่อผู้ใช้หรืออีเมล หรือรหัสผ่านไม่ถูกต้อง',
                        confirmButtonText: 'ลองอีกครั้ง',
                        confirmButtonColor: '#ff6b6b',
                        backdrop: 'rgba(0,0,0,0.4)',
                        showClass: {
                            popup: 'animate__animated animate__shakeX'
                        }
                    }).then(() => {
                        window.location.href = 'signin.php';
                    });
                });
                </script>
            </body>
            </html>";
            exit();
        }

    } catch (PDOException $e) {
        echo "
        <script>
        Swal.fire({
            icon: 'error',
            title: 'เกิดข้อผิดพลาดในระบบ',
            text: '" . addslashes($e->getMessage()) . "',
            confirmButtonText: 'ตกลง'
        }).then(() => {
            window.location.href = 'signin.php';
        });
        </script>";
    }
} else {
    header("Location: signin.php");
    exit();
}
?>
