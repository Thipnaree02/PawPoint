<?php
include 'config/db.php';
session_start(); // ✅ ใช้ session เพื่อดึง user_id ของผู้ใช้ที่ล็อกอินอยู่

// ✅ ถ้ายังไม่มีระบบล็อกอิน ให้ใช้ user_id ชั่วคราวเพื่อทดสอบ
// ถ้ามีระบบล็อกอินจริงให้ลบบรรทัดนี้ออก
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // ✅ ตั้งค่า user_id ทดสอบ (ต้องมี user_id=1 ในตาราง users)
}

// ✅ ดึงรายชื่อสัตวแพทย์จากฐานข้อมูล
$stmtVet = $conn->query("SELECT vet_id, vet_name FROM vets ORDER BY vet_name ASC");
$vets = $stmtVet->fetchAll(PDO::FETCH_ASSOC);

// ✅ เมื่อผู้ใช้ส่งฟอร์ม
if (isset($_POST['book_appointment'])) {
    $user_id = $_SESSION['user_id']; // ✅ ดึงจาก session
    $pet_name = $_POST['pet_name'];
    $vet_id = $_POST['vet_id'];
    $service_type = $_POST['service_type']; // ✅ เพิ่มการรับค่าประเภทบริการ
    $date = $_POST['date'];
    $time = $_POST['time'];
    $status = "pending"; // เริ่มต้นเป็น "รอดำเนินการ"

    // ✅ เพิ่มฟิลด์ user_id และ service_type ในคำสั่ง SQL
    $stmt = $conn->prepare("
        INSERT INTO appointments (user_id, pet_name, vet_id, service_type, date, time, status)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$user_id, $pet_name, $vet_id, $service_type, $date, $time, $status]);

    header("Location: new_appointment.php?success=1");
    exit;
}
?>

<!doctype html>
<html lang="th">

<head>
    <meta charset="utf-8">
    <title>จองนัดหมาย - คลินิกสัตว์เลี้ยง</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            font-family: 'Noto Sans Thai', sans-serif;
        }

        body {
            background: #f3f6fa;
        }

        .booking-container {
            max-width: 600px;
            margin: 60px auto;
            background: #fff;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div class="booking-container">
        <h3 class="text-center mb-4 fw-bold text-success">
            <i class="bi bi-calendar2-heart"></i> จองนัดหมายล่วงหน้า
        </h3>

        <form method="post">
            <div class="mb-3">
                <label class="form-label fw-semibold">ชื่อสัตว์เลี้ยง</label>
                <input type="text" name="pet_name" class="form-control" placeholder="กรอกชื่อสัตว์เลี้ยงของคุณ"
                    required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">เลือกสัตวแพทย์</label>
                <select name="vet_id" class="form-select" required>
                    <option value="">-- กรุณาเลือกสัตวแพทย์ --</option>
                    <?php foreach ($vets as $vet): ?>
                        <option value="<?= $vet['vet_id'] ?>"><?= htmlspecialchars($vet['vet_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">ประเภทบริการ</label>
                <select name="service_type" class="form-select" required>
                    <option value="health_check">ตรวจสุขภาพ</option>
                    <option value="vaccination">ฉีดวัคซีน</option>
                    <option value="surgery">ผ่าตัด</option>
                    <option value="sterilization">ทำหมัน</option>
                    <option value="other">อื่น ๆ</option>
                </select>
            </div>


            <div class="mb-3">
                <label class="form-label fw-semibold">วันที่นัดหมาย</label>
                <input type="date" name="date" class="form-control" min="<?= date('Y-m-d') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">เวลานัดหมาย</label>
                <input type="time" name="time" class="form-control" required>
            </div>

            <div class="text-center mt-4">
                <button type="submit" name="book_appointment" class="btn btn-success px-4 py-2">
                    <i class="bi bi-check-circle"></i> ยืนยันการจอง
                </button>
            </div>
        </form>
    </div>

    <div class="text-center mt-3">
        <a href="appointments.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> กลับไปตารางนัดหมาย
        </a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'จองนัดหมายสำเร็จ!',
                text: 'ระบบได้บันทึกข้อมูลเรียบร้อยแล้ว',
                confirmButtonColor: '#4ca771',
                confirmButtonText: 'ตกลง'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'appointments.php'; // ✅ กลับมาหน้าเดิมหลังจากกด OK
                }
            });
        </script>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>