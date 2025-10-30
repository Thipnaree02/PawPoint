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
                            <style>

                            /* ------------------------------ */
                            /* Profile Dropdown - Premium Look */
                            /* ------------------------------ */
                            .profile-wrapper {
                                position: relative;
                                display: inline-block;
                            }

                            .profile-toggle {
                                display: flex;
                                align-items: center;
                                cursor: pointer;
                                gap: 8px;
                                background: rgba(255, 255, 255, 0.9);
                                padding: 6px 12px;
                                border-radius: 20px;
                                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
                                transition: all 0.25s ease;
                            }

                            .profile-toggle:hover {
                                background: linear-gradient(90deg, #4aa9d9, #6dd5fa);
                                color: white;
                                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                            }

                            .profile-toggle i {
                                font-size: 1.7rem;
                                color: #2980b9;
                                transition: color 0.25s ease;
                            }

                            .profile-toggle:hover i {
                                color: white;
                            }

                            .profile-dropdown {
                                position: absolute;
                                top: 60px;
                                right: 0;
                                width: 280px;
                                background: rgba(255, 255, 255, 0.9);
                                backdrop-filter: blur(8px);
                                border-radius: 18px;
                                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
                                overflow: hidden;
                                opacity: 0;
                                visibility: hidden;
                                transform: translateY(-10px);
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
                                background: linear-gradient(180deg, #6dd5fa, #4aa9d9);
                                color: white;
                            }

                            .profile-info i {
                                font-size: 60px;
                                margin-bottom: 10px;
                            }

                            .profile-info h3 {
                                font-size: 18px;
                                font-weight: 600;
                            }

                            .profile-actions {
                                background: #fff;
                                padding: 15px 18px;
                            }

                            .profile-actions a {
                                display: flex;
                                align-items: center;
                                gap: 10px;
                                color: #333;
                                text-decoration: none;
                                font-size: 15px;
                                padding: 10px 8px;
                                border-radius: 10px;
                                transition: all 0.25s ease;
                            }

                            .profile-actions a i {
                                color: #4aa9d9;
                                font-size: 18px;
                                transition: color 0.25s ease;
                            }

                            .profile-actions a:hover {
                                background: linear-gradient(90deg, #4aa9d9, #6dd5fa);
                                color: white;
                                transform: translateX(5px);
                            }

                            .profile-actions a:hover i {
                                color: white;
                            }

                            .profile-actions a.logout {
                                color: #e74c3c;
                                border-top: 1px solid #eee;
                                margin-top: 8px;
                                padding-top: 12px;
                            }

                            .profile-actions a.logout:hover {
                                background: linear-gradient(90deg, #ff6b6b, #e74c3c);
                                color: white;
                            }
                        </style>

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
                                        <a href="user_history.php"><i class="bi bi-clock-history"></i>
                                            ประวัติการใช้บริการ</a>
                                        <a href="pet_list.php"><i class="bi bi-journal-text"></i>
                                            ประวัติสัตว์เลี้ยงของคุณ</a>
                                        <a href="add_pet.php"><i class="bi bi-plus-circle"></i> เพิ่มสัตว์เลี้ยงของคุณ</a>
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