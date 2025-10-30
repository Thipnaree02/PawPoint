<?php
session_start();
include 'config/db.php';

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// รับ id ของสัตวแพทย์
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header("Location: vet_list.php");
    exit;
}

// ดึงข้อมูลสัตวแพทย์จากฐานข้อมูล
$stmt = $conn->prepare("SELECT * FROM veterinarians WHERE id = ?");
$stmt->execute([$id]);
$vet = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$vet) {
    header("Location: vet_list.php");
    exit;
}

// ตัวแปร flag สำหรับ SweetAlert
$updateSuccess = false;

// เมื่อกดปุ่มบันทึก
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname']);
    $specialization = trim($_POST['specialization']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $working_days = trim($_POST['working_days']);
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $photo = $vet['photo']; // ค่าเดิม

    // อัปโหลดรูปภาพใหม่
    if (!empty($_FILES['photo']['name'])) {
        $targetDir = "uploads/vets/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        $fileName = time() . "_" . basename($_FILES["photo"]["name"]);
        $targetFilePath = $targetDir . $fileName;

        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFilePath)) {
            // ลบรูปเก่าถ้าไม่ใช่ default
            if (!empty($vet['photo']) && $vet['photo'] !== 'default_vet.png') {
                $oldPath = $targetDir . $vet['photo'];
                if (file_exists($oldPath)) unlink($oldPath);
            }
            $photo = $fileName;
        }
    }

    // อัปเดตข้อมูลในฐานข้อมูล
    $stmt = $conn->prepare("UPDATE veterinarians 
        SET fullname=?, specialization=?, phone=?, email=?, working_days=?, start_time=?, end_time=?, photo=? 
        WHERE id=?");
    $stmt->execute([$fullname, $specialization, $phone, $email, $working_days, $start_time, $end_time, $photo, $id]);

    // ตั้งค่า flag ว่าสำเร็จ
    $updateSuccess = true;
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แก้ไขข้อมูลสัตวแพทย์ | PawPoint Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background-color: #f8f9fa; font-family: 'Noto Sans Thai', sans-serif; }
        .container { max-width: 700px; background: #fff; border-radius: 15px; padding: 2rem; margin-top: 3rem; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .btn-save { background-color: #198754; color: #fff; border-radius: 8px; }
        .btn-save:hover { background-color: #157347; }
        img.preview { width: 120px; height: 120px; object-fit: cover; border-radius: 10px; border: 2px solid #ccc; }
    </style>
</head>

<body>
    <div class="container">
        <h3 class="mb-4 text-success fw-bold"><i class="bi bi-pencil-square me-2"></i>แก้ไขข้อมูลสัตวแพทย์</h3>

        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">ชื่อ-นามสกุล</label>
                <input type="text" name="fullname" class="form-control" required value="<?= htmlspecialchars($vet['fullname']); ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">สาขาความเชี่ยวชาญ</label>
                <input type="text" name="specialization" class="form-control" value="<?= htmlspecialchars($vet['specialization']); ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">เบอร์โทรศัพท์</label>
                <input type="text" name="phone" class="form-control" required value="<?= htmlspecialchars($vet['phone']); ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">อีเมล</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($vet['email']); ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">วันทำงาน</label>
                <input type="text" name="working_days" class="form-control" value="<?= htmlspecialchars($vet['working_days']); ?>">
                <small class="text-muted">เช่น จันทร์ - ศุกร์ หรือ อังคาร / พฤหัสบดี</small>
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label class="form-label">เวลาเริ่มงาน</label>
                    <input type="time" name="start_time" class="form-control" value="<?= htmlspecialchars($vet['start_time']); ?>">
                </div>
                <div class="col">
                    <label class="form-label">เวลาสิ้นสุดงาน</label>
                    <input type="time" name="end_time" class="form-control" value="<?= htmlspecialchars($vet['end_time']); ?>">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">รูปภาพปัจจุบัน</label><br>
                <img src="uploads/vets/<?= htmlspecialchars($vet['photo']); ?>" alt="" class="preview mb-2">
                <input type="file" name="photo" class="form-control mt-2">
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="vet_list.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> กลับ</a>
                <button type="submit" class="btn btn-save px-4"><i class="bi bi-check-circle"></i> บันทึกการแก้ไข</button>
            </div>
        </form>
    </div>

    <?php if ($updateSuccess): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'แก้ไขข้อมูลสำเร็จ!',
            text: 'ระบบได้บันทึกข้อมูลสัตวแพทย์เรียบร้อยแล้ว',
            confirmButtonColor: '#198754',
            confirmButtonText: 'ตกลง'
        }).then(() => {
            window.location = 'vet_list.php';
        });
    </script>
    <?php endif; ?>
</body>
</html>
