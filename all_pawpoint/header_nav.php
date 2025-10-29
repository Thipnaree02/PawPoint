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
                <span>PawPoint</span>
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
                                gap: 8px;
                            }

                            .profile-toggle i {
                                font-size: 1.8rem;
                                color: #2980b9;
                            }

                            .profile-dropdown {
                                position: absolute;
                                top: 55px;
                                right: 0;
                                width: 300px;
                                background: linear-gradient(180deg, #6dd5fa, #2980b9);
                                border-radius: 20px;
                                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
                                overflow: hidden;
                                opacity: 0;
                                visibility: hidden;
                                transition: all 0.3s ease;
                                z-index: 1000;
                            }

                            .profile-wrapper:hover .profile-dropdown {
                                opacity: 1;
                                visibility: visible;
                                transform: translateY(5px);
                            }

                            .profile-info {
                                text-align: center;
                                padding: 20px;
                                color: #fff;
                            }

                            .profile-info i {
                                font-size: 60px;
                                color: #fff;
                                margin-bottom: 8px;
                            }

                            .profile-info h3 {
                                font-size: 18px;
                                margin: 5px 0;
                                color: #000;
                                font-weight: 700;
                            }

                            .profile-actions {
                                background: #fff;
                                padding: 15px 20px;
                                border-top: 1px solid #eee;
                            }

                            .profile-actions a {
                                display: flex;
                                align-items: center;
                                gap: 10px;
                                color: #333;
                                text-decoration: none;
                                font-size: 15px;
                                margin: 8px 0;
                                transition: color 0.2s;
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
                        </style>

                        <li class="nav-item">
                            <div class="profile-wrapper">
                                <div class="profile-toggle">
                                    <i class="bi bi-person-circle"></i>
                                    <span><?php echo htmlspecialchars($_SESSION['username'] ?? 'ไม่ระบุชื่อ'); ?></span>
                                    <i class="bi bi-caret-down-fill ms-1"></i>
                                </div>

                                <div class="profile-dropdown">
                                    <div class="profile-info">
                                        <i class="bi bi-person-circle"></i>
                                        <h3><?php echo htmlspecialchars($_SESSION['username'] ?? 'ไม่ระบุชื่อ'); ?></h3>
                                    </div>
                                    <div class="profile-actions">
                                        <a href="profile.php"><i class="bi bi-person"></i> โปรไฟล์ของฉัน</a>
                                        <a href="user_history.php"><i class="bi bi-clock-history"></i> ประวัติการใช้บริการ</a>
                                        <a href="pet_list.php"><i class="bi bi-journal-text"></i> ประวัติสัตว์เลี้ยงของคุณ</a>
                                        <a href="add_pet.php"><i class="bi bi-plus-circle"></i> เพิ่มสัตว์เลี้ยงของคุณ</a>
                                        <a href="logout.php" class="logout"><i class="bi bi-box-arrow-right"></i> ออกจากระบบ</a>
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
