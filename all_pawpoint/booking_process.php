<?php
session_start();

require_once '../myadmin/config/db.php'; // ✅ ตรวจให้ path ถูกต้อง

if (!isset($_SESSION['user_id'])) {
  header("Location: signin.php");
  exit();
}

$popup = ""; // จะเก็บ script popup ไว้แสดงหลัง HTML

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user_id = $_SESSION['user_id'];
  $pet_name = $_POST['pet_name'] ?? '';
  $service_type = $_POST['service_type'] ?? '';
  $date = $_POST['date'] ?? '';
  $time = $_POST['time'] ?? '';
  $vet_id = !empty($_POST['vet_id']) ? $_POST['vet_id'] : null;
  $note = $_POST['symptom'] ?? '';

  try {
    if (!isset($conn)) {
      throw new Exception('Database connection ($conn) not found.');
    }

    $stmt = $conn->prepare("
      INSERT INTO appointments (user_id, pet_name, vet_id, service_type, date, time, status, note)
      VALUES (:user_id, :pet_name, :vet_id, :service_type, :date, :time, 'pending', :note)
    ");

    $stmt->execute([
      ':user_id' => $user_id,
      ':pet_name' => $pet_name,
      ':vet_id' => $vet_id,
      ':service_type' => $service_type,
      ':date' => $date,
      ':time' => $time,
      ':note' => $note
    ]);

    // ✅ เตรียม SweetAlert2 popup หลังโหลดหน้า
    $popup = "
      <script>
        document.addEventListener('DOMContentLoaded', function() {
          Swal.fire({
            icon: 'success',
            title: 'จองคิวสำเร็จแล้ว!',
            text: 'ระบบได้บันทึกข้อมูลเรียบร้อยแล้ว',
            confirmButtonColor: '#3fb6a8',
            confirmButtonText: 'ตกลง'
          }).then(() => {
            window.location.href = 'index.php';
          });
        });
      </script>
    ";

  } catch (Exception $e) {
    $popup = "
      <script>
        document.addEventListener('DOMContentLoaded', function() {
          Swal.fire({
            icon: 'error',
            title: 'เกิดข้อผิดพลาด!',
            text: '" . addslashes($e->getMessage()) . "',
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'กลับไปแก้ไข'
          }).then(() => {
            window.history.back();
          });
        });
      </script>
    ";
  }
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ฟอร์มจองคิวตรวจสุขภาพสัตว์เลี้ยง</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@400;500;600&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    body {
      background: linear-gradient(180deg, #f0fdfa 0%, #ffffff 100%);
      font-family: 'Prompt', sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .booking-card {
      max-width: 650px;
      width: 100%;
      background: #fff;
      padding: 40px;
      border-radius: 25px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
      transition: 0.3s;
    }

    .booking-card:hover {
      transform: translateY(-3px);
    }

    .form-label {
      font-weight: 500;
      color: #333;
    }

    h3 {
      color: #2b7a78;
      font-weight: 600;
      margin-bottom: 20px;
    }

    .btn-confirm {
      background-color: #3fb6a8;
      color: white;
      border-radius: 30px;
      padding: 10px 25px;
      font-size: 17px;
      font-weight: 500;
      border: none;
      transition: 0.3s;
    }

    .btn-confirm:hover {
      background-color: #339c91;
    }

    .btn-cancel {
      background-color: #dc3545;
      color: white;
      border-radius: 30px;
      padding: 10px 25px;
      font-size: 17px;
      font-weight: 500;
      margin-left: 10px;
      text-decoration: none;
      display: inline-block;
      transition: 0.3s;
    }

    .btn-cancel:hover {
      background-color: #b82a38;
      color: white;
    }

    .logo-box {
      text-align: center;
      margin-bottom: 15px;
    }

    .logo-box img {
      width: 100px;
      height: 100px;
      object-fit: contain;
    }

    .header-line {
      width: 60%;
      height: 3px;
      background-color: #a0d9d2;
      margin: 15px auto 25px auto;
      border-radius: 10px;
    }
  </style>
</head>

<body>
  <div class="booking-card">
    <div class="logo-box">
      <img src="images/logo.png" alt="โลโก้ร้าน" id="clinicLogo">
    </div>
    <h3 class="text-center">จองคิวสัตว์เลี้ยง</h3>
    <div class="header-line"></div>

    <form method="POST">
      <div class="mb-4">
        <label for="service_type" class="form-label">เลือกประเภทบริการ</label>
        <select id="service_type" name="service_type" class="form-select text-center" required>
          <option value="">-- กรุณาเลือกบริการ --</option>
          <option value="health_check">ตรวจสุขภาพ</option>
          <option value="vaccination">ฉีดวัคซีน</option>
          <option value="sterilization">ทำหมัน</option>
        </select>
      </div>

      <div class="mb-3">
        <label for="pet_name" class="form-label">ชื่อสัตว์เลี้ยงของคุณ</label>
        <input type="text" class="form-control" id="pet_name" name="pet_name"
          placeholder="เช่น หมาชื่อโบโบ้ / แมวชื่อมะลิ" required>
      </div>

      <div class="mb-3">
        <label for="date" class="form-label">วันที่จอง</label>
        <input type="date" class="form-control" id="date" name="date" required>
      </div>

      <div class="mb-3">
        <label for="time" class="form-label">เวลา</label>
        <input type="time" class="form-control" id="time" name="time" required>
      </div>

      <div class="mb-3">
        <label for="vet" class="form-label">เลือกสัตวแพทย์ (ไม่บังคับ)</label>
        <select class="form-select" id="vet" name="vet_id">
          <option value="">-- ไม่เลือกหมอเฉพาะ --</option>
          <option value="1">น.สพ. ปริญญา ศรีมงคล</option>
          <option value="2">น.สพ. ธนพร ใจดี</option>
          <option value="3">น.สพ. ภูวเดช คำทอง</option>
          <option value="4">น.สพ. วิภาดา พรหมแก้ว</option>
          <option value="5">น.สพ. ธีรพงศ์ ศรีสวัสดิ์</option>
        </select>
      </div>

      <div class="mb-4">
        <label for="symptom" class="form-label">อาการเบื้องต้น</label>
        <textarea class="form-control" id="symptom" name="symptom" rows="3"
          placeholder="ระบุอาการ เช่น ซึม ไม่กินอาหาร เดินกะเผลก..."></textarea>
      </div>

      <div class="text-center">
        <button type="submit" class="btn btn-confirm">ยืนยันการจอง</button>
        <a href="index.php" class="btn btn-cancel">ยกเลิก</a>
      </div>
    </form>
  </div>

  <!-- ✅ แสดง popup หลัง HTML โหลด -->
  <?= $popup ?>
</body>

</html>