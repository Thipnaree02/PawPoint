<?php
$service = $_GET['service'] ?? '';
$package = $_GET['package'] ?? '';

$packageNames = [
  'small' => 'แพ็กเกจเล็ก (250 บาท)',
  'medium' => 'แพ็กเกจกลาง (350 บาท)',
  'large' => 'แพ็กเกจใหญ่ (450 บาท)',
  'spa' => 'แพ็กเกจสปาเพิ่ม (600 บาท)'
];

$serviceNames = [
  'grooming' => 'อาบน้ำ / ตัดขน',
  'vaccine' => 'ฉีดวัคซีน',
  'health' => 'ตรวจสุขภาพ',
  'surgery' => 'ผ่าตัด / ทำหมัน'
];
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>จองคิวบริการ</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@400;500;600&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    body {
      font-family: 'Prompt', sans-serif;
      background: linear-gradient(180deg, #f0fdf4 0%, #ffffff 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .booking-card {
      background: #fff;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.08);
      max-width: 600px;
      width: 100%;
      padding: 40px;
      margin: 30px auto;
      transition: 0.3s;
    }

    .booking-card:hover {
      transform: translateY(-3px);
    }

    .booking-title {
      font-weight: 600;
      color: #2e856e;
    }

    .alert-custom {
      background-color: #e6f5ef;
      border: none;
      color: #2e856e;
      font-weight: 500;
    }

    .btn-submit {
      background-color: #66b8a6;
      border: none;
      border-radius: 30px;
      padding: 10px;
      font-size: 1.1rem;
      transition: 0.3s;
    }

    .btn-submit:hover {
      background-color: #57a190;
    }

    .btn-cancel {
      border-radius: 30px;
      padding: 10px;
      font-size: 1.1rem;
      transition: 0.3s;
    }

    label {
      font-weight: 500;
      color: #333;
    }
  </style>
</head>

<body>
  <div class="booking-card">
    <h2 class="text-center mb-4 booking-title">📅 ระบบจองคิวบริการ</h2>

    <?php if ($service): ?>
      <div class="alert alert-custom text-center mb-4">
        <div>คุณกำลังจองบริการ: <strong><?= $serviceNames[$service] ?? $service ?></strong></div>
        <?php if ($package): ?>
          <div>แพ็กเกจที่เลือก: <strong><?= $packageNames[$package] ?? $package ?></strong></div>
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <!-- แบบฟอร์มจอง -->
    <form id="bookingForm" action="booking_process.php" method="POST">
      <input type="hidden" name="service" value="<?= htmlspecialchars($service) ?>">
      <input type="hidden" name="package" value="<?= htmlspecialchars($package) ?>">

      <div class="mb-3">
        <label for="pet_name" class="form-label">ชื่อสัตว์เลี้ยง</label>
        <input type="text" name="pet_name" id="pet_name" class="form-control" placeholder="เช่น โบโบ้ / มะลิ" required>
      </div>

      <div class="mb-3">
        <label for="date" class="form-label">วันที่ต้องการจอง</label>
        <input type="date" name="date" id="date" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="time" class="form-label">เวลา</label>
        <input type="time" name="time" id="time" class="form-control" required>
      </div>

      <div class="d-flex gap-3 mt-4">
        <button type="button" id="btnConfirm" class="btn btn-submit flex-fill">ยืนยันการจอง</button>
        <button type="button" id="btnCancel" class="btn btn-outline-danger btn-cancel flex-fill">ยกเลิก</button>
      </div>
    </form>
  </div>

  <script>
    // ปุ่มยืนยันการจอง
    document.getElementById('btnConfirm').addEventListener('click', function () {
      Swal.fire({
        title: 'ยืนยันการจอง?',
        text: "คุณต้องการยืนยันการจองบริการนี้หรือไม่",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'ยืนยัน',
        cancelButtonText: 'ยกเลิก',
        confirmButtonColor: '#66b8a6',
        cancelButtonColor: '#dc3545'
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById('bookingForm').submit();
        }
      });
    });

    // ปุ่มยกเลิก
    document.getElementById('btnCancel').addEventListener('click', function () {
      Swal.fire({
        title: 'ต้องการยกเลิกการจอง?',
        text: "หากยืนยัน ระบบจะกลับไปหน้าก่อนหน้า",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'ใช่',
        cancelButtonText: 'ไม่',
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d'
      }).then((result) => {
        if (result.isConfirmed) {
          window.history.back();
        }
      });
    });
  </script>
</body>
</html>
