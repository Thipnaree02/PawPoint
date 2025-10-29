<?php

session_start();

// ถ้ายังไม่มี session แสดงว่ายังไม่ล็อกอิน
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'config/db.php';

if (!isset($_GET['id'])) {
  header("Location: index.php");
  exit;
}

$id = $_GET['id'];

// ดึงข้อมูลนัดหมาย
$stmt = $conn->prepare("
  SELECT 
    a.app_id,
    a.pet_name,
    a.service_type,
    a.date,
    a.time,
    a.status,
    a.note,
    v.vet_name,
    u.username AS owner_name
  FROM appointments a
  LEFT JOIN vets v ON a.vet_id = v.vet_id
  LEFT JOIN users u ON a.user_id = u.user_id
  WHERE a.app_id = ?
");
$stmt->execute([$id]);
$appointment = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$appointment) {
  echo "<script>alert('ไม่พบนัดหมายนี้'); window.location='index.php';</script>";
  exit;
}

$serviceNames = [
  'health_check' => 'ตรวจสุขภาพ',
  'vaccination' => 'ฉีดวัคซีน',
  'sterilization' => 'ทำหมัน',
];

// สีสถานะ
$statusBadge = [
  'pending' => ['รอคิว', 'warning', 'bi-clock-history'],
  'confirmed' => ['กำลังดำเนินการ', 'primary', 'bi-gear'],
  'completed' => ['เสร็จสิ้น', 'success', 'bi-check-circle'],
  'cancelled' => ['ยกเลิก', 'danger', 'bi-x-circle']
][$appointment['status']] ?? ['ไม่ระบุ', 'secondary', 'bi-question-circle'];
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>รายละเอียดนัดหมาย | Elivet Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: #f6f8fa;
      font-family: 'Noto Sans Thai', sans-serif;
    }
    .card {
      border: none;
      border-radius: 16px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .badge-status {
      font-size: 1rem;
      padding: 8px 14px;
      border-radius: 30px;
    }
    .info-label {
      color: #888;
      font-weight: 500;
    }
    .info-value {
      font-weight: 600;
      color: #333;
    }
  </style>
</head>
<body>
  <div class="container py-5">
    <div class="card p-4 mx-auto" style="max-width: 700px;">
      <div class="d-flex align-items-center justify-content-between mb-4">
        <h4 class="fw-bold text-success mb-0">
          <i class="bi bi-clipboard2-check-fill me-2"></i>รายละเอียดนัดหมาย
        </h4>
        <span class="badge bg-<?= $statusBadge[1] ?> badge-status">
          <i class="bi <?= $statusBadge[2] ?>"></i> <?= $statusBadge[0] ?>
        </span>
      </div>

      <div class="row g-3">
        <div class="col-6">
          <div class="info-label">ชื่อสัตว์เลี้ยง</div>
          <div class="info-value"><?= htmlspecialchars($appointment['pet_name']) ?></div>
        </div>
        <div class="col-6">
          <div class="info-label">เจ้าของ</div>
          <div class="info-value"><?= htmlspecialchars($appointment['owner_name']) ?></div>
        </div>

        <div class="col-6">
          <div class="info-label">บริการ</div>
          <div class="info-value"><?= $serviceNames[$appointment['service_type']] ?? 'อื่น ๆ' ?></div>
        </div>
        <div class="col-6">
          <div class="info-label">สัตวแพทย์</div>
          <div class="info-value"><?= htmlspecialchars($appointment['vet_name'] ?? '-') ?></div>
        </div>

        <div class="col-6">
          <div class="info-label">วันที่</div>
          <div class="info-value"><?= htmlspecialchars($appointment['date']) ?></div>
        </div>
        <div class="col-6">
          <div class="info-label">เวลา</div>
          <div class="info-value"><?= htmlspecialchars($appointment['time']) ?></div>
        </div>

        <div class="col-12 mt-3">
          <div class="info-label">หมายเหตุ</div>
          <div class="info-value"><?= nl2br(htmlspecialchars($appointment['note'] ?? '-')) ?></div>
        </div>
      </div>

      <hr class="my-4">
      <div class="text-end">
        <a href="index.php" class="btn btn-outline-success px-4">
          <i class="bi bi-arrow-left-circle"></i> กลับหน้าแดชบอร์ด
        </a>
      </div>
    </div>
  </div>
</body>
</html>
