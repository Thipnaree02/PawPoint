<!-- เช็คไฟล์โปรไฟล์สมาชิกว่าเข้าระบบไหม -->
<!-- <?php
if (!file_exists('images/thipnaree.jpg')) {
    echo "<script>alert('ไม่พบไฟล์รูปภาพ!');</script>";
}
?> -->


<?php
session_start();
?>

<header>
    <nav class="navbar navbar-expand-lg bg-light shadow-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="images/logo.png" class="logo img-fluid" alt="Logo">
                <span>PawPoint</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php#top">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#section_2">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#section_3">Causes</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#section_4">แพ็กเกจ</a></li>
                    <li class="nav-item"><a class="nav-link" href="member-doctor.php">สัตวแพทย์</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#section_6">ติดต่อ</a></li>

                    <?php if (isset($_SESSION['user_id'])): ?>

                        <style>
                            /* 🌿 สไตล์กล่องโปรไฟล์แบบยาว */
                            .dropdown-menu.profile-menu {
                                width: 340px;
                                /* ✅ ไม่กว้างเกินไป */
                                min-height: 480px;
                                /* ✅ เพิ่มความสูงให้ยาวลง */
                                border: none;
                                border-radius: 28px;
                                box-shadow: 0 10px 50px rgba(0, 0, 0, 0.15);
                                padding: 0;
                                overflow: hidden;
                                backdrop-filter: blur(10px);
                                background: rgba(255, 255, 255, 0.92);
                                transition: all 0.3s ease;
                            }

                            /* ✅ ส่วนหัวรูปและชื่อ */
                            .profile-menu .profile-header {
                                background: linear-gradient(135deg, #6ee7b7 0%, #3b82f6 100%);
                                color: #fff;
                                text-align: center;
                                padding: 60px 25px 50px 25px;
                                /* ✅ เพิ่ม padding บน–ล่าง ให้ยาวลง */
                            }

                            /* ✅ รูปโปรไฟล์ใหญ่ และมีช่องว่างรอบ ๆ */
                            .profile-menu .profile-header img {
                                border-radius: 50%;
                                border: 5px solid #fff;
                                box-shadow: 0 0 15px rgba(255, 255, 255, 0.4);
                                width: 110px;
                                height: 110px;
                                object-fit: cover;
                                margin-bottom: 20px;
                            }

                            /* ✅ ชื่อ + อีเมล ใหญ่ขึ้นนิด ดูบาลานซ์ */
                            .profile-menu .profile-header h6 {
                                font-size: 1.3rem;
                                font-weight: 700;
                                margin-bottom: 8px;
                            }

                            .profile-menu .profile-header small {
                                font-size: 1rem;
                                opacity: 0.9;
                            }

                            /* ✅ ส่วนล่างของกล่อง (รายการ) */
                            .profile-menu .dropdown-item {
                                text-align: left;
                                font-weight: 500;
                                color: #333;
                                padding: 18px 30px;
                                /* ✅ เพิ่ม padding แนวตั้ง ให้ยาวขึ้น */
                                font-size: 17px;
                                display: flex;
                                align-items: center;
                                gap: 10px;
                                transition: background 0.2s ease;
                            }

                            .profile-menu .dropdown-item:hover {
                                background-color: #f8f9fa;
                            }

                            .profile-menu .dropdown-item.text-danger:hover {
                                background-color: #ffe6e6;
                                color: #c82333;
                            }

                            /* ✅ เอฟเฟกต์ตอนเปิดเมนู */
                            .dropdown-menu.show {
                                transform: translateY(12px);
                                transition: all 0.25s ease-out;
                            }

                            /* 🌸 เส้นแบ่งบาง ๆ ระหว่างเมนู */
                            .profile-menu .divider-line {
                                height: 1px;
                                background: linear-gradient(to right, transparent, rgba(0, 0, 0, 0.15), transparent);
                                margin: 8px 25px;
                                border-radius: 1px;
                                opacity: 0.7;
                            }

                            /* 🌿 ปรับความนุ่มโดยรวมอีกนิด */
                            .dropdown-menu.profile-menu {
                                width: 340px;
                                min-height: 480px;
                                border-radius: 28px;
                                background: rgba(255, 255, 255, 0.92);
                                backdrop-filter: blur(12px);
                                box-shadow: 0 10px 50px rgba(0, 0, 0, 0.15);
                                overflow: hidden;
                                transition: all 0.3s ease;
                            }

                            /* ✅ เอฟเฟกต์ตอนเปิดเมนู */
                            .dropdown-menu.show {
                                transform: translateY(12px);
                                opacity: 1;
                                transition: all 0.3s ease-out;
                            }
                            
                        </style>


                        <!-- ✅ เมนูโปรไฟล์แบบมีรูป -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userMenu"
                                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="images/avatar/thipnaree.jpg" class="rounded-circle me-2" width="35" height="35"
                                    alt="Profile">
                                <span class="fw-semibold"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end profile-menu" aria-labelledby="userMenu">
                                <li class="profile-header">
                                    <img src="images/avatar/thipnaree.jpg" alt="Profile">
                                    <h6><?php echo htmlspecialchars($_SESSION['username']); ?></h6>
                                    <small><?php echo htmlspecialchars($_SESSION['email']); ?></small>
                                </li>

                                <li>
                                    <a class="dropdown-item" href="profile.php">
                                        <i class="bi bi-person-circle"></i> โปรไฟล์ของฉัน
                                    </a>
                                </li>

                                <!-- 🔹 Divider แบบบางโปร่งใส -->
                                <li class="divider-line"></li>

                                <li>
                                    <a class="dropdown-item text-danger" href="logout.php">
                                        <i class="bi bi-box-arrow-right"></i> ออกจากระบบ
                                    </a>
                                </li>
                            </ul>

                        </li>

                    <?php else: ?>
                        <!-- ❌ ยังไม่ล็อกอิน -->
                        <li class="nav-item ms-3">
                            <a class="nav-link btn btn-info text-dark rounded-pill px-3" href="signin.php">Sign In</a>
                        </li>
                        <li class="nav-item ms-3">
                            <a class="nav-link btn btn-warning text-dark rounded-pill px-3" href="signup.php">Sign Up</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>