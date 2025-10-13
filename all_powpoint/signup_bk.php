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
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
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

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                data-bs-target="#navbarNav" aria-controls="navbarNav" 
                aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php#section_1">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#section_2">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#section_3">Causes</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#section_4">แพ็กเกจ</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#section_5">สัตวแพทย์</a></li>
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

  <body>
    <div class="signup-box">
      <div class="text-center mb-4">
        <img src="images/logo.png" alt="PawPoint Logo" class="img-fluid" style="width: 80px;">
        <h3 class="mt-3">Create PowPoint Account</h3>
        <p class="text-muted">สมัครสมาชิกเพื่อใช้บริการคลินิกสัตวแพทย์ออนไลน์</p>
      </div>

      <form action="register_process.php" method="POST">
        <div class="mb-3">
          <label class="form-label">ชื่อผู้ใช้ (Username)</label>
          <input type="text" name="username" class="form-control" placeholder="เช่น Thipnaree" required>
        </div>

        <div class="mb-3">
          <label class="form-label">อีเมล (Email)</label>
          <input type="email" name="email" class="form-control" placeholder="เช่น yourname@gmail.com" required>
        </div>

        <div class="mb-3">
          <label class="form-label">รหัสผ่าน (Password)</label>
          <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-4">
          <label class="form-label">ยืนยันรหัสผ่าน</label>
          <input type="password" name="confirm_password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-green w-100 py-2 mb-3">
          <i class="bi bi-person-plus me-2"></i> สร้างบัญชี
        </button>

        <a href="google_login.php" class="btn google-btn w-100 py-2 mb-3">
          <i class="bi bi-google me-2"></i> สมัครด้วย Google
        </a>

        <p class="text-center mb-0">
          มีบัญชีแล้ว? <a href="signin.php">เข้าสู่ระบบ</a>
        </p>
      </form>
    </div>

    <!-- JS -->
    <script src="js/bootstrap.bundle.min.js"></script>
  </body>
</html>
