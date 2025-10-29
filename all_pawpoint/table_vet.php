<?php
include '../myadmin/config/db.php';
$stmt = $conn->query("SELECT * FROM veterinarians ORDER BY id ASC");
$vets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>PawPoint | ทีมสัตวแพทย์</title>

    <!-- CSS FILES -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-icons.css" rel="stylesheet">
    <link href="css/templatemo-kind-heart-charity.css" rel="stylesheet">
</head>

<body id="section_1">

    <!-- 🔹 ส่วนหัวเหมือน index.php -->
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
                            <a href="#" class="social-icon-link bi-twitter"></a>
                        </li>
                        <li class="social-icon-item">
                            <a href="https://www.facebook.com/yong.thipnaree?locale=th_TH"
                                class="social-icon-link bi-facebook"></a>
                        </li>
                        <li class="social-icon-item">
                            <a href="https://www.instagram.com/thipnaree.ng?igsh=bWVpejEyd2toNWh2&utm_source=qr"
                                class="social-icon-link bi-instagram"></a>
                        </li>
                        <li class="social-icon-item">
                            <a href="https://www.youtube.com/@happythipnaree"
                                class="social-icon-link bi-youtube"></a>
                        </li>
                        <li class="social-icon-item">
                            <a href="#" class="social-icon-link bi-whatsapp"></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <?php include 'header_nav.php'; ?>

    <!-- 🔹 เนื้อหาหลัก -->
    <main>
        <section class="news-detail-header-section text-center">
            <div class="section-overlay"></div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-12">
                        <h1 class="text-white">ทีมสัตวแพทย์ประจำคลินิก</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="news-section section-padding section-bg">
            <div class="container">
                <div class="row g-4">
                    <?php if (count($vets) > 0) {
                        foreach ($vets as $vet) { ?>
                            <div class="col-sm-6 col-md-4 col-lg-3 d-flex">
                                <div class="vet-card w-100 shadow rounded-4 bg-white">
                                    <img src="../myadmin/uploads/vets/<?= htmlspecialchars($vet['photo']); ?>"
                                        onerror="this.src='../myadmin/uploads/vets/default_vet.png';"
                                        class="vet-photo img-fluid rounded-top" alt="สัตวแพทย์">
                                    <div class="vet-info p-3 text-center">
                                        <h5 class="text-success fw-bold"><?= htmlspecialchars($vet['fullname']); ?></h5>
                                        <p><?= htmlspecialchars($vet['specialization']); ?></p>
                                        <p class="icon-text">
                                            <i class="bi bi-telephone-fill"></i>
                                            <?= htmlspecialchars($vet['phone']); ?>
                                        </p>
                                        <small>วันทำงาน: <?= htmlspecialchars($vet['working_days']); ?></small><br>
                                        <small>เวลา: <?= htmlspecialchars($vet['start_time']); ?> - <?= htmlspecialchars($vet['end_time']); ?></small>
                                    </div>
                                </div>
                            </div>
                    <?php }
                    } else { ?>
                        <div class="text-center text-muted py-5">
                            <p>ยังไม่มีข้อมูลสัตวแพทย์</p>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </section>
    </main>

    <!-- 🔹 Footer เดิมจากเทมเพลต -->
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
                    <h5 class="site-footer-title mb-3">Contact Infomation</h5>
                    <p class="text-white d-flex mb-2">
                        <i class="bi-telephone me-2"></i>
                        <a href="tel: 120-240-9600" class="site-footer-link">120-240-9600</a>
                    </p>
                    <p class="text-white d-flex">
                        <i class="bi-envelope me-2"></i>
                        <a href="mailto:info@yourgmail.com" class="site-footer-link">donate@charity.org</a>
                    </p>
                    <p class="text-white d-flex mt-3">
                        <i class="bi-geo-alt me-2"></i>
                        Akershusstranda 20, 0150 Oslo, Norway
                    </p>
                    <a href="#" class="custom-btn btn mt-3">Get Direction</a>
                </div>
            </div>
        </div>

        <div class="site-footer-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-7 col-12">
                        <p class="copyright-text mb-0">Copyright © 2036 <a href="#">Kind Heart</a> Charity Org.
                            Design: <a href="https://templatemo.com" target="_blank">TemplateMo</a></p>
                    </div>
                    <div class="col-lg-6 col-md-5 col-12 d-flex justify-content-center align-items-center mx-auto">
                        <ul class="social-icon">
                            <li class="social-icon-item"><a href="#" class="social-icon-link bi-twitter"></a></li>
                            <li class="social-icon-item"><a href="#" class="social-icon-link bi-facebook"></a></li>
                            <li class="social-icon-item"><a href="#" class="social-icon-link bi-instagram"></a></li>
                            <li class="social-icon-item"><a href="#" class="social-icon-link bi-linkedin"></a></li>
                            <li class="social-icon-item"><a href="https://youtube.com/templatemo" class="social-icon-link bi-youtube"></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- JS -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.sticky.js"></script>
    <script src="js/click-scroll.js"></script>
    <script src="js/counter.js"></script>
    <script src="js/custom.js"></script>
</body>
</html>
