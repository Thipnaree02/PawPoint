<?php
session_start();
include '../Admin/config/connextdb.php';

if (isset($_SESSION['user_id'])) {
    header("Location: profile.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $connextdb->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role']; // เก็บ role ไว้ใน session ด้วย

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

    <!-- Header -->
    <header class="site-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-12 d-flex flex-wrap">
                    <p class="d-flex me-4 mb-0">
                        <i class="bi-geo-alt me-2"></i> มหาวิทยาลัยมหาสารคาม
                    </p>
                    <p class="d-flex mb-0">
                        <i class="bi-envelope me-2"></i>
                        <a href="mailto:65010914602@msu.ac.th">65010914602@msu.ac.th</a>
                    </p>
                </div>
            </div>
        </div>
    </header>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-light shadow-lg">
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
                    <li class="nav-item"><a class="nav-link" href="index.php#section_1">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#section_2">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#section_3">Causes</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#section_4">แพ็กเกจ</a></li>
                    <li class="nav-item"><a class="nav-link" href="member.php">สัตวแพทย์</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#section_6">ติดต่อ</a></li>

                    <li class="nav-item ms-3">
                        <a class="nav-link custom-btn custom-border-btn btn" href="signin.php">Sign In</a>
                    </li>

                    <li class="nav-item ms-3">
                        <a class="nav-link custom-btn custom-border-btn btn" href="signup.php">Sign Up</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ✅ Sign In Section -->
    <main>
    <section class="login-section">
        <div class="container">
            <div class="login-card">
                <img src="https://cdn-icons-png.flaticon.com/512/616/616408.png" alt="Pet Icon">
                <h4>เข้าสู่ระบบคลินิกสัตวแพทย์</h4>

                <!-- ✅ เริ่มต้นฟอร์มที่นี่ -->
                <form action="login_process.php" method="POST">
                    <div class="mb-3 text-start">
                        <label class="form-label">อีเมลหรือชื่อผู้ใช้</label>
                        <input type="text" name="username" class="form-control"
                            placeholder="กรอกอีเมลหรือชื่อผู้ใช้" required>
                    </div>

                    <div class="mb-3 text-start">
                        <label class="form-label">รหัสผ่าน</label>
                        <input type="password" name="password" class="form-control"
                            placeholder="กรอกรหัสผ่าน" required>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember" class="small">จดจำฉันไว้</label>
                        </div>
                        <a href="#" class="small text-primary">ลืมรหัสผ่าน?</a>
                    </div>

                    <button type="submit" class="btn btn-login w-100">เข้าสู่ระบบ</button>
                </form>
                <!-- ✅ ปิดฟอร์มที่นี่ -->
            </div>
        </div>
    </section>
</main>


    <!-- Footer -->
    <footer class="site-footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-12 mb-4">
                    <img src="images/logo.png" class="logo img-fluid" alt="">
                </div>
                <div class="col-lg-4 col-md-6 col-12 mb-4">
                    <h5 class="site-footer-title mb-3">Quick Links</h5>
                    <ul class="footer-menu">
                        <li class="footer-menu-item"><a href="#" class="footer-menu-link">Our Story</a></li>
                        <li class="footer-menu-item"><a href="#" class="footer-menu-link">Newsroom</a></li>
                        <li class="footer-menu-item"><a href="#" class="footer-menu-link">Causes</a></li>
                        <li class="footer-menu-item"><a href="#" class="footer-menu-link">Become a volunteer</a></li>
                        <li class="footer-menu-item"><a href="#" class="footer-menu-link">Partner with us</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-6 col-12 mx-auto">
                    <h5 class="site-footer-title mb-3">Contact Information</h5>
                    <p class="text-white d-flex mb-2"><i class="bi-telephone me-2"></i>120-240-9600</p>
                    <p class="text-white d-flex"><i class="bi-envelope me-2"></i>donate@charity.org</p>
                    <p class="text-white d-flex mt-3"><i class="bi-geo-alt me-2"></i>Akershusstranda 20, 0150 Oslo,
                        Norway</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- JS -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.sticky.js"></script>
    <script src="js/counter.js"></script>
    <script src="js/custom.js"></script>
</body>

</html>