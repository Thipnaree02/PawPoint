<?php
session_start();
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>บริการอาบน้ำ / ตัดขนสัตว์เลี้ยง</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- ✅ ต้องมี SweetAlert ตรงนี้ -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background-color: #f9fafb;
            font-family: "Prompt", sans-serif;
        }

        h2 {
            color: #333;
            font-weight: 600;
        }

        .package-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            transition: 0.3s;
        }

        .package-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .review-card {
            background: #fff;
            border-radius: 15px;
            padding: 15px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 15px;
        }

        .price-table th {
            background-color: #66b8a6;
            color: #fff;
        }

        .btn-main {
            background-color: #66b8a6;
            color: #fff;
            border-radius: 25px;
            padding: 10px 25px;
            transition: 0.3s;
        }

        .btn-main:hover {
            background-color: #57a190;
            color: #fff;
        }

        /* ปุ่มกลับสู่หน้าหลัก */
        .btn-secondary-main {
            background-color: #7bd8f1ff;
            color: #2f4f4f;
            border-radius: 25px;
            padding: 10px 25px;
            font-weight: 500;
            margin-left: 10px;
            transition: 0.3s;
        }

        .btn-secondary-main:hover {
            background-color: #c0d4d8;
            color: #000;
        }
    </style>
</head>

<body>
    <?php
    // ✅ ตรวจสอบว่าล็อกอินหรือยัง
    if (!isset($_SESSION['user_id'])) {
        echo "
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'warning',
                title: 'กรุณาเข้าสู่ระบบก่อนใช้งาน',
                text: 'คุณต้องล็อกอินก่อนจองบริการอาบน้ำ / ตัดขนสัตว์เลี้ยง',
                confirmButtonText: 'เข้าสู่ระบบ',
                confirmButtonColor: '#3085d6'
            }).then(() => {
                window.location.href = 'signin.php';
            });
        });
        </script>
        ";
        return; // ✅ ใช้ return แทน exit
    }
    ?>

    <section class="section-padding text-center py-5">
        <div class="container">
            <h2 class="mb-4">บริการอาบน้ำ / ตัดขนสัตว์เลี้ยง</h2>
            <p class="mb-5">เลือกแพ็กเกจที่เหมาะกับสัตว์เลี้ยงของคุณ พร้อมจองคิวได้ทันที!</p>

            <!-- ปุ่มจองบริการ -->
            <div class="mb-5">
                <a href="booking.php?service=grooming" class="btn btn-main">🧼 จองบริการอาบน้ำ / ตัดขน</a>
                <a href="index.php" class="btn btn-secondary-main">กลับสู่หน้าหลัก</a>
            </div>

            <!-- เลือกแพ็กเกจ -->
            <h4 class="mb-3">เลือกแพ็กเกจ</h4>
            <?php
            require_once '../myadmin/config/db.php';
            $packages = $conn->query("SELECT * FROM grooming_packages WHERE is_active = 1 ORDER BY price ASC")->fetchAll();
            ?>

            <div class="row justify-content-center mb-5">
                <?php foreach ($packages as $pkg): ?>
                    <div class="col-md-3 mb-4">
                        <div class="package-card p-4">
                            <h5><?= htmlspecialchars($pkg['name_th']) ?></h5>
                            <p><?= htmlspecialchars($pkg['description_th']) ?></p>
                            <p><strong>ราคา:</strong> <?= number_format($pkg['price'], 2) ?> บาท</p>
                            <a href="booking.php?service=grooming&package_id=<?= $pkg['id'] ?>"
                                class="btn btn-outline-success btn-sm">
                                เลือกแพ็กเกจนี้
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- ตารางราคา -->
            <h4 class="mb-3">ตารางราคา</h4>
            <div class="table-responsive mb-5">
                <table class="table table-bordered price-table">
                    <thead>
                        <tr>
                            <th>แพ็กเกจ</th>
                            <th>รายละเอียด</th>
                            <th>ราคา (บาท)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>เล็ก</td>
                            <td>อาบน้ำ ตัดขน พ่นน้ำหอม</td>
                            <td>250</td>
                        </tr>
                        <tr>
                            <td>กลาง</td>
                            <td>อาบน้ำ ตัดขน เคลือบขน</td>
                            <td>350</td>
                        </tr>
                        <tr>
                            <td>ใหญ่</td>
                            <td>อาบน้ำ ตัดขน ตัดเล็บ แปรงฟัน</td>
                            <td>450</td>
                        </tr>
                        <tr>
                            <td>สปาเพิ่ม</td>
                            <td>สปาขน + อาบน้ำสมุนไพร</td>
                            <td>600</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div> <!-- container -->
    </section>
</body>

</html>