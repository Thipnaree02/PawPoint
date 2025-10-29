<?php
session_start();
include '../myadmin/config/db.php';

// if (isset($_SESSION['user_id'])) {
//     header("Location: profile.php");
//     exit();
// }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['email'] = $user['email']; // ✅ เพิ่มบรรทัดนี้


        // ถ้าเป็นแอดมินไปหลังบ้าน, ถ้าเป็น staff ไปหน้า profile
        if ($user['role'] === 'admin') {
            header("Location: ../Admin/index.php");
        } else {
            header("Location: profile.php");
        }
        exit();
    } else {
        $error = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
    }
}
?>


<!doctype html>
<html lang="th">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PawPoint Clinic - Sign In</title>

    <!-- CSS FILES -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-icons.css" rel="stylesheet">
    <link href="css/templatemo-kind-heart-charity.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(to bottom, #ffffff, #fef6e4);
            font-family: 'Prompt', sans-serif;
        }

        .login-section {
            padding: 100px 0;
            position: relative;
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

        .login-card h4 {
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .form-control {
            border-radius: 10px;
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

                        <a href="mailto:65010914602@msu.ac.th">
                            65010914602@msu.ac.th
                        </a>
                    </p>
                </div>

                <div class="col-lg-3 col-12 ms-auto d-lg-block d-none">
                    <ul class="social-icon">

                        <li class="social-icon-item">
                            <a href="https://www.facebook.com/yong.thipnaree?locale=th_TH"
                                class="social-icon-link bi-facebook"></a>
                        </li>

                        <li class="social-icon-item">
                            <a href="https://www.instagram.com/thipnaree.ng?igsh=bWVpejEyd2toNWh2&utm_source=qr"
                                class="social-icon-link bi-instagram"></a>
                        </li>

                        <li class="social-icon-item">
                            <a href="https://www.youtube.com/@happythipnaree" class="social-icon-link bi-youtube"></a>
                        </li>

                    </ul>
                </div>

            </div>
        </div>
    </header>

    <?php include 'header_nav.php'; ?>

    <!-- ✅ Sign In Section -->
    <main>
        <section class="login-section">
            <div class="container">
                <div class="login-card">
                    <img src="https://cdn-icons-png.flaticon.com/512/616/616408.png" alt="Pet Icon">
                    <h4>เข้าสู่ระบบ PawPoint</h4>

                    <!-- ✅ เริ่มต้นฟอร์มที่นี่ -->
                    <form action="login_process.php" method="POST">
                        <div class="mb-3 text-start">
                            <label class="form-label">อีเมลหรือชื่อผู้ใช้</label>
                            <input type="text" name="username" class="form-control"
                                placeholder="กรอกอีเมลหรือชื่อผู้ใช้" required>
                        </div>

                        <div class="mb-3 text-start">
                            <label class="form-label">รหัสผ่าน</label>
                            <input type="password" name="password" class="form-control" placeholder="กรอกรหัสผ่าน"
                                required>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <input type="checkbox" id="remember" name="remember">
                                <label for="remember" class="small">จดจำฉันไว้</label>
                            </div>
                            <a href="#" class="small text-primary">ลืมรหัสผ่าน?</a>
                        </div>

                        <button type="submit" class="btn btn-orange w-100 py-2 mb-3">เข้าสู่ระบบ</button>

                        <!-- <button type="submit" class="btn btn-success w-100 py-2 mb-3">
                            <i class="bi bi-person-plus me-2"></i> เข้าสู่ระบบ
                        </button> -->

                        <p class="text-center mb-0">
                            ยังไม่มีบัญชีแล้ว? <a href="signup.php">สมัครสมาชิก</a>
                        </p>

                    </form>
                    <!-- ✅ ปิดฟอร์มที่นี่ -->
                </div>
            </div>
        </section>
    </main>

    <!-- JS -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.sticky.js"></script>
    <script src="js/counter.js"></script>
    <script src="js/custom.js"></script>
</body>

</html>