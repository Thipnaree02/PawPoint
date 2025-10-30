<?php
session_start();
include '../myadmin/config/db.php'; // ใช้การเชื่อมต่อ PDO เช่น $conn

// ถ้ามี session อยู่แล้วให้กลับหน้า index
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // ตรวจสอบรหัสผ่านตรงกันไหม
    if ($password !== $confirm_password) {
        $error = "รหัสผ่านไม่ตรงกัน";
    } else {
        // ตรวจว่ามีชื่อผู้ใช้หรืออีเมลนี้อยู่แล้วหรือยัง
        $check = $conn->prepare("SELECT user_id FROM users WHERE username = ? OR email = ?");
        $check->execute([$username, $email]);

        if ($check->rowCount() > 0) {
            $error = "ชื่อผู้ใช้หรืออีเมลนี้ถูกใช้แล้ว";
        } else {
            // เข้ารหัสรหัสผ่าน
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            // สมัครสมาชิกใหม่
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            if ($stmt->execute([$username, $email, $hashed])) {
                // ✅ ใช้ session flag แทนการ echo script
                $_SESSION['signup_success'] = true;
                header("Location: signup.php");
                exit();
            } else {
                $error = "เกิดข้อผิดพลาดในการสมัครสมาชิก";
            }
        }
    }
}
?>

<!doctype html>
<html lang="th">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PawPoint Clinic - Sign Up</title>

    <!-- CSS FILES -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-icons.css" rel="stylesheet">
    <link href="css/templatemo-kind-heart-charity.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background: linear-gradient(to bottom, #ffffff, #fef6e4);
            font-family: 'Prompt', sans-serif;
        }

        .login-section {
            padding: 100px 0;
        }

        .login-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 2.5rem;
            max-width: 420px;
            margin: 0 auto;
            text-align: center;
        }

        .login-card img {
            width: 80px;
            margin-bottom: 1rem;
        }

        .btn-login {
            background-color: #ff914d;
            border: none;
            color: white;
            border-radius: 10px;
            font-weight: 600;
            padding: 0.6rem;
            transition: 0.3s;
        }

        .btn-login:hover {
            background-color: #ff7b1a;
        }

        .google-btn {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 0.6rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 500;
            margin-top: 10px;
            transition: 0.3s;
        }

        .google-btn:hover {
            background-color: #f3f3f3;
        }

        .google-btn img {
            width: 22px;
            margin-right: 10px;
        }

        .footer-text {
            margin-top: 1rem;
            font-size: 0.9rem;
            color: #888;
        }
    </style>
</head>

<body id="section_1">

    <!-- Header -->
    <header class="site-header">
        <div class="container">
            <div class="row">

                <div class="col-lg-8 col-12 d-flex flex-wrap">
                    <p class="d-flex me-4 mb-0">
                        <i class="bi-geo-alt me-2"></i>
                        มหาวิทยาลัยมหาสารคาม
                    </p>

                    <p class="d-flex mb-0">
                        <i class="bi-envelope me-2"></i>
                        <a href="mailto:65010914602@msu.ac.th">65010914602@msu.ac.th</a>
                    </p>
                </div>

                <div class="col-lg-3 col-12 ms-auto d-lg-block d-none">
                    <ul class="social-icon">
                        <li class="social-icon-item">
                            <a href="https://www.facebook.com/yong.thipnaree?locale=th_TH" class="social-icon-link bi-facebook"></a>
                        </li>
                        <li class="social-icon-item">
                            <a href="https://www.instagram.com/thipnaree.ng?igsh=bWVpejEyd2toNWh2&utm_source=qr" class="social-icon-link bi-instagram"></a>
                        </li>
                        <li class="social-icon-item">
                            <a href="https://www.youtube.com/@happythipnaree" class="social-icon-link bi-youtube"></a>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </header>

    <nav class="navbar navbar-expand-lg bg-light shadow-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="images/logo.png" class="logo img-fluid" alt="">
                <span>PawPoint</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link click-scroll" href="index.php#section_1">หน้าหลัก</a></li>
                    <li class="nav-item"><a class="nav-link smoothscroll" href="index.php#section_2">เมนูบริการ</a></li>
                    <li class="nav-item"><a class="nav-link click-scroll" href="index.php#section_3">บุคลากร</a></li>
                    <li class="nav-item"><a class="nav-link click-scroll" href="index.php#section_4">แพ็กเกจ</a></li>
                    <li class="nav-item"><a class="nav-link click-scroll" href="index.php#section_6">ติดต่อ</a></li>
                    <li class="nav-item ms-3"><a class="nav-link custom-btn custom-border-btn btn" href="signin.php">Sign In</a></li>
                    <li class="nav-item ms-3"><a class="nav-link custom-btn custom-border-btn btn" href="signup.php">Sign Up</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ✅ Sign Up Section -->
    <main>
        <section class="login-section">
            <div class="container-fluid">
                <div class="login-card">
                    <img src="https://cdn-icons-png.flaticon.com/512/616/616408.png" alt="Pet Icon">
                    <h3 class="mt-3">สมัครสมาชิก</h3>

                    <!-- ฟอร์มสมัคร -->
                    <form id="signupForm" method="POST">
                        <div class="mb-3 text-start">
                            <label class="form-label">ชื่อผู้ใช้ (Username)</label>
                            <input type="text" name="username" class="form-control" placeholder="เช่น Thipnaree" required>
                        </div>

                        <div class="mb-3 text-start">
                            <label class="form-label">อีเมล (Email)</label>
                            <input type="email" name="email" class="form-control" placeholder="เช่น yourname@gmail.com" required>
                        </div>

                        <div class="mb-3 text-start">
                            <label class="form-label">รหัสผ่าน (Password)</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="mb-4 text-start">
                            <label class="form-label">ยืนยันรหัสผ่าน</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>

                        <button type="submit" class="btn-login w-100 py-2 mb-3">
                            <i class="bi bi-person-plus me-2"></i> สร้างบัญชี
                        </button>

                        <p class="text-center mb-0">
                            มีบัญชีแล้ว? <a href="signin.php">เข้าสู่ระบบ</a>
                        </p>
                    </form>

                    <hr class="my-4">

                    <!-- Sign in with Google -->
                    <a href="#" class="google-btn w-100">
                        <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google logo">
                        Sign in with Google
                    </a>

                    <div class="footer-text">
                        © 2025 PawPoint Veterinary Clinic
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- ✅ Loading Popup ก่อนส่งฟอร์ม -->
    <script>
    document.getElementById('signupForm').addEventListener('submit', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'กำลังสมัครสมาชิก...',
            text: 'กรุณารอสักครู่ 🐾',
            allowOutsideClick: false,
            allowEscapeKey: false,
            background: '#fffaf4',
            didOpen: () => { Swal.showLoading(); }
        });

        setTimeout(() => { e.target.submit(); }, 1200);
    });
    </script>

    <!-- ✅ แสดง SweetAlert หลังสมัครสำเร็จ -->
    <?php if (isset($_SESSION['signup_success'])): ?>
    <script>
    Swal.fire({
        icon: 'success',
        title: 'สมัครสมาชิกสำเร็จ!',
        text: 'ยินดีต้อนรับสู่ PawPoint 🐾',
        confirmButtonColor: '#ff914d',
        confirmButtonText: 'ไปหน้าเข้าสู่ระบบ'
    }).then(() => {
        window.location.href = 'signin.php';
    });
    </script>
    <?php unset($_SESSION['signup_success']); endif; ?>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
