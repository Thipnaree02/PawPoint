<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>บริการอาบน้ำ / ตัดขนสัตว์เลี้ยง</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

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

        .btn-main {
  background-color: #66b8a6; /* เขียวมินต์ */
  color: #fff;
  border-radius: 25px;
  padding: 10px 25px;
  font-weight: 500;
  transition: 0.3s;
}

.btn-main:hover {
  background-color: #57a190;
  color: #fff;
}

/* ปุ่มกลับสู่หน้าหลัก */
.btn-secondary-main {
  background-color: #7bd8f1ff; /* เทาอ่อนฟ้า */
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
            <div class="row justify-content-center mb-5">

                <div class="col-md-3 mb-4">
                    <div class="package-card p-4">
                        <h5>แพ็กเกจเล็ก</h5>
                        <p>สำหรับสัตว์เลี้ยงน้ำหนักไม่เกิน 5 กก.</p>
                        <p><strong>ราคา:</strong> 250 บาท</p>
                        <a href="booking.php?service=grooming&package=small" class="btn btn-outline-success btn-sm">
                            เลือกแพ็กเกจนี้
                        </a>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="package-card p-4">
                        <h5>แพ็กเกจกลาง</h5>
                        <p>สำหรับสัตว์เลี้ยงน้ำหนัก 5–15 กก.</p>
                        <p><strong>ราคา:</strong> 350 บาท</p>
                        <a href="booking.php?service=grooming&package=medium" class="btn btn-outline-success btn-sm">
                            เลือกแพ็กเกจนี้
                        </a>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="package-card p-4">
                        <h5>แพ็กเกจใหญ่</h5>
                        <p>สำหรับสัตว์เลี้ยงน้ำหนัก 15–30 กก.</p>
                        <p><strong>ราคา:</strong> 450 บาท</p>
                        <a href="booking.php?service=grooming&package=large" class="btn btn-outline-success btn-sm">
                            เลือกแพ็กเกจนี้
                        </a>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="package-card p-4">
                        <h5>แพ็กเกจสปาเพิ่ม</h5>
                        <p>อาบน้ำ + สปาขน + กลิ่นหอมพิเศษ</p>
                        <p><strong>ราคา:</strong> 600 บาท</p>
                        <a href="booking.php?service=grooming&package=spa" class="btn btn-outline-success btn-sm">
                            เลือกแพ็กเกจนี้
                        </a>
                    </div>
                </div>

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

            <!-- รีวิวลูกค้า -->
            <h4 class="mb-3">⭐ รีวิวจากลูกค้า</h4>
            <div class="row justify-content-center">
                <div class="col-md-4">
                    <div class="review-card">
                        <p>“น้องหมาหอมมาก ขนฟูสุด ๆ พนักงานน่ารักมากค่ะ ❤️”</p>
                        <small>- คุณแพรว</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="review-card">
                        <p>“บริการดี มีรูปก่อนหลังให้ดูด้วย ประทับใจค่ะ 🐶”</p>
                        <small>- คุณมายด์</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="review-card">
                        <p>“ราคาคุ้มค่า ร้านสะอาด ขนไม่พันเลยครับ”</p>
                        <small>- คุณบอล</small>
                    </div>
                </div>
            </div>

            <!-- รูปก่อน-หลัง (อัปโหลดได้ภายหลัง) -->
            <div class="mt-5">
                <h4>📸 รูปก่อน-หลังอาบน้ำ</h4>
                <p class="text-muted">ระบบจะแสดงรูปก่อน-หลังอัตโนมัติเมื่ออัปโหลด</p>
                <img src="images/grooming_before_after.png" alt="before-after" class="img-fluid rounded-3 shadow"
                    style="max-width: 600px;">
            </div>

        </div>
    </section>
</body>

</html>