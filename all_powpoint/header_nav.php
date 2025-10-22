<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>


<header>
    <nav class="navbar navbar-expand-lg bg-light shadow-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="images/logo.png" class="logo img-fluid" alt="Logo">
                <span>PowPoint</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php#top">หน้าหลัก</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#section_2">เมนูบริการ</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#section_3">บุคคลากร</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#section_4">แพ็กเกจ</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#section_6">ติดต่อ</a></li>

                    <?php if (isset($_SESSION['user_id'])): ?>

                        <style>
                            .profile-wrapper {
                                position: relative;
                                display: inline-block;
                            }

                            .profile-toggle {
                                display: flex;
                                align-items: center;
                                cursor: pointer;
                            }

                            .profile-toggle img {
                                width: 35px;
                                height: 35px;
                                border-radius: 50%;
                                object-fit: cover;
                                margin-right: 8px;
                            }

                            .profile-dropdown {
                                position: absolute;
                                top: 55px;
                                right: 0;
                                width: 320px;
                                background: linear-gradient(180deg, #6dd5fa, #2980b9);
                                border-radius: 25px;
                                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
                                overflow: hidden;
                                transition: all 0.3s ease, opacity 0.3s ease;
                                z-index: 1000;
                                opacity: 0;
                                visibility: hidden;
                            }

                            .profile-wrapper:hover .profile-dropdown {
                                opacity: 1;
                                visibility: visible;
                                transform: translateY(6px);
                            }

                            .profile-info {
                                text-align: center;
                                padding: 25px 25px 20px 25px;
                            }

                            .profile-info img {
                                width: 110px;
                                height: 110px;
                                border-radius: 50%;
                                border: 4px solid #fff;
                                object-fit: cover;
                                margin-bottom: 12px;
                                background-color: #fff;
                            }

                            .profile-info h3 {
                                font-weight: 700;
                                color: #000;
                                font-size: 19px;
                                margin-bottom: 5px;
                            }

                            .profile-info p {
                                color: #fff;
                                font-size: 14px;
                                margin: 0;
                            }

                            .profile-actions {
                                background: #fff;
                                padding: 15px 25px;
                                border-top: 1px solid #eee;
                                border-radius: 0 0 25px 25px;
                            }

                            .profile-actions a {
                                display: flex;
                                align-items: center;
                                gap: 10px;
                                color: #333;
                                text-decoration: none;
                                font-size: 15px;
                                margin: 8px 0;
                                transition: color 0.2s ease;
                            }

                            .profile-actions a:hover {
                                color: #2980b9;
                            }

                            .profile-actions a.logout {
                                color: #e74c3c;
                            }

                            .profile-actions a.logout:hover {
                                color: #c0392b;
                            }

                            @media (min-width: 992px) {
                                .profile-dropdown {
                                    width: 360px;
                                    border-radius: 28px;
                                }

                                .profile-info img {
                                    width: 120px;
                                    height: 120px;
                                }

                                .profile-info h3 {
                                    font-size: 20px;
                                }
                            }
                        </style>

                        <!-- ✅ เมนูโปรไฟล์ -->
                        <li class="nav-item">
                            <div class="profile-wrapper">
                                <?php
                                // ✅ ใช้ avatar จาก session ที่อัปเดตล่าสุด
                                $defaultImg = "images/avatar/users.png";
                                if (!empty($_SESSION['avatar']) && file_exists(__DIR__ . '/' . $_SESSION['avatar'])) {
                                    $profileImg = $_SESSION['avatar'];
                                } else {
                                    $profileImg = $defaultImg;
                                }
                                ?>

                                <div class="profile-toggle">
                                    <img src="<?php echo htmlspecialchars($profileImg); ?>" alt="Profile Picture">
                                    <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                                    <i class="bi bi-caret-down-fill ms-1"></i>
                                </div>

                                <div class="profile-dropdown">
                                    <div class="profile-info">
                                        <img src="<?php echo htmlspecialchars($profileImg); ?>" alt="Profile Picture">
                                        <h3><?php echo htmlspecialchars($_SESSION['username']); ?></h3>
                                        <p><?php echo htmlspecialchars($_SESSION['email']); ?></p>
                                    </div>
                                    <div class="profile-actions">
                                        <a href="profile.php"><i class="bi bi-person-circle"></i> โปรไฟล์ของฉัน</a>
                                        <a href="logout.php" class="logout"><i class="bi bi-box-arrow-right"></i>
                                            ออกจากระบบ</a>
                                    </div>
                                </div>
                            </div>
                        </li>

                    <?php else: ?>
                        <li class="nav-item ms-3">
                            <a class="nav-link custom-btn custom-border-btn btn" href="signin.php">Sign In</a>
                        </li>

                        <li class="nav-item ms-3">
                            <a class="nav-link custom-btn custom-border-btn btn" href="signup.php">Sign Up</a>
                        </li>
                    <?php endif; ?>

                </ul>
            </div>
        </div>
    </nav>
</header>