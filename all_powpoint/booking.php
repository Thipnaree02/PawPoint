<?php
session_start();
require_once '../myadmin/config/db.php';

// ✅ ต้องล็อกอินก่อน
if (!isset($_SESSION['user_id'])) {
  header('Location: signin.php');
  exit;
}

// ✅ รับ package_id มาจาก grooming_service.php
$package_id = $_GET['package_id'] ?? null;
$selected_package = null;

if ($package_id) {
  $stmt = $conn->prepare("SELECT * FROM grooming_packages WHERE id = ? AND is_active = 1");
  $stmt->execute([$package_id]);
  $selected_package = $stmt->fetch(PDO::FETCH_ASSOC);
}

// ✅ ดึงแพ็กเกจทั้งหมด
$packages = $conn->query("SELECT id, name_th, price FROM grooming_packages WHERE is_active = 1 ORDER BY price ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user_id = $_SESSION['user_id'];
  $package_id = (int) ($_POST['package_id'] ?? 0);
  $booking_date = $_POST['booking_date'] ?? '';
  $booking_time = $_POST['booking_time'] ?? '';
  $note = trim($_POST['note'] ?? '');
  $pet_id = !empty($_POST['pet_id']) ? (int) $_POST['pet_id'] : null;

  $stmt = $conn->prepare("SELECT id FROM grooming_packages WHERE id = ? AND is_active = 1");
  $stmt->execute([$package_id]);
  if (!$stmt->fetch())
    $error = "แพ็กเกจไม่ถูกต้อง";

  if (empty($error)) {
    $chk = $conn->prepare("SELECT COUNT(*) FROM grooming_bookings WHERE booking_date=? AND booking_time=? AND status IN ('pending','confirmed')");
    $chk->execute([$booking_date, $booking_time]);
    if ($chk->fetchColumn() > 0)
      $error = "ช่วงวัน-เวลานี้ถูกจองแล้ว กรุณาเลือกเวลาอื่น";
  }

  if (empty($error)) {
    $ins = $conn->prepare("
      INSERT INTO grooming_bookings (user_id, pet_id, package_id, booking_date, booking_time, note)
      VALUES (?, ?, ?, ?, ?, ?)
    ");
    $ins->execute([$user_id, $pet_id, $package_id, $booking_date, $booking_time, $note]);
    $_SESSION['flash_success'] = "จองสำเร็จ! รอการยืนยันจากร้าน";
    header("Location: my_bookings.php");
    exit;
  }
}
?>
<!doctype html>
<html lang="th">

<head>
  <meta charset="utf-8">
  <title>จองบริการอาบน้ำ / ตัดขน</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f9fafb;
      font-family: 'Noto Sans Thai', sans-serif;
    }

    .card {
      border-radius: 15px;
      box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
    }

    .btn-success {
      background-color: #4caf50;
      border: none;
    }

    .btn-success:hover {
      background-color: #449d48;
    }

    .price-box {
      background-color: #e8f5e9;
      border-radius: 10px;
      padding: 15px;
      border: 1px solid #c8e6c9;
    }

    .price-total {
      font-size: 1.3rem;
      font-weight: 600;
      color: #2e7d32;
    }
  </style>
</head>

<body>
  <div class="container py-5">
    <h3 class="mb-4 text-success fw-bold"><i class="bi bi-scissors"></i> จองบริการอาบน้ำ / ตัดขน</h3>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($selected_package): ?>
      <div class="price-box mb-4">
        <h5 class="mb-1"><i class="bi bi-star-fill text-warning"></i> แพ็กเกจที่เลือก:</h5>
        <p class="mb-1"><?= htmlspecialchars($selected_package['name_th']) ?></p>
        <p class="mb-0 text-success fw-bold">ราคา <?= number_format($selected_package['price'], 0) ?> บาท</p>
      </div>
    <?php endif; ?>

    <form method="post" class="card p-4 border-0 shadow-sm">
      <div class="mb-3">
        <label class="form-label">เลือกแพ็กเกจ</label>
        <select name="package_id" id="package_id" class="form-select" required onchange="updatePrice()">
          <option value="">-- เลือกแพ็กเกจ --</option>
          <?php foreach ($packages as $p): ?>
            <option value="<?= $p['id'] ?>" data-price="<?= $p['price'] ?>" <?= ($selected_package && $selected_package['id'] == $p['id']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($p['name_th']) ?> (<?= number_format($p['price'], 0) ?> บาท)
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">วันที่ต้องการ</label>
          <input type="date" name="booking_date" class="form-control" required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">เวลา</label>
          <input type="time" name="booking_time" class="form-control" required>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">หมายเหตุ (ถ้ามี)</label>
        <textarea name="note" class="form-control" rows="3" placeholder="เช่น ขนพันง่าย ผิวแพ้ง่าย ฯลฯ"></textarea>
      </div>

      <!-- ✅ กล่องแสดงค่าบริการรวม -->
      <div class="price-box mb-3 text-center">
        <span class="text-secondary">ค่าบริการรวม</span>
        <div class="price-total" id="totalPrice">0 บาท</div>
      </div>

      <div class="d-flex gap-2 justify-content-center">
        <button class="btn btn-success px-4"><i class="bi bi-check-circle"></i> ยืนยันการจอง</button>
        <a href="grooming_service.php" class="btn btn-outline-secondary px-4"><i class="bi bi-arrow-left"></i>
          กลับไปหน้าแพ็กเกจ</a>
      </div>
    </form>
  </div>

  <script>
    function updatePrice() {
      const select = document.getElementById('package_id');
      const price = select.options[select.selectedIndex].getAttribute('data-price');
      document.getElementById('totalPrice').innerText = price ? `${parseInt(price).toLocaleString()} บาท` : '0 บาท';
    }

    // ตั้งค่าเริ่มต้นเมื่อโหลดหน้า
    updatePrice();
  </script>

</body>

</html>