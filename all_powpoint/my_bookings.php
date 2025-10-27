<?php
session_start();
require_once '../myadmin/config/db.php';

if (!isset($_SESSION['user_id'])) {
  header('Location: signin.php');
  exit;
}

$user_id = $_SESSION['user_id'];

// ดึงข้อมูลการจองของลูกค้า
$stmt = $conn->prepare("
  SELECT gb.*, gp.name_th, gp.price
  FROM grooming_bookings gb
  LEFT JOIN grooming_packages gp ON gb.package_id = gp.id
  WHERE gb.user_id = ?
  ORDER BY gb.booking_date DESC, gb.booking_time DESC
");
$stmt->execute([$user_id]);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="th">

<head>
  <meta charset="utf-8">
  <title>ประวัติการจองอาบน้ำ / ตัดขน</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: #f8faf9;
      font-family: 'Noto Sans Thai', sans-serif;
      color: #333;
    }

    h3 {
      font-weight: 700;
    }

    .card {
      border: none;
      border-radius: 15px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .table th {
      background-color: #4caf50;
      color: white;
      text-align: center;
      font-weight: 500;
    }

    .table td {
      text-align: center;
      vertical-align: middle;
      background: #fff;
    }

    .status-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      border-radius: 25px;
      padding: 5px 12px;
      font-size: 0.9rem;
      font-weight: 600;
    }

    .pending {
      background: #fff3cd;
      color: #856404;
    }

    .confirmed {
      background: #cce5ff;
      color: #004085;
    }

    .completed {
      background: #d4edda;
      color: #155724;
    }

    .cancelled {
      background: #f8d7da;
      color: #721c24;
    }

    .filter-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
      flex-wrap: wrap;
    }

    .filter-bar input {
      max-width: 250px;
    }

    .btn-outline-success {
      border-radius: 30px;
    }

    .badge i {
      font-size: 1rem;
    }
  </style>
</head>

<body>
  <div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
      <h3 class="text-success mb-3">
        <i class="bi bi-journal-check"></i> ประวัติการจองบริการอาบน้ำ / ตัดขน
      </h3>
      <a href="grooming_service.php" class="btn btn-outline-success">
        <i class="bi bi-arrow-left-circle"></i> กลับไปหน้าแพ็กเกจ
      </a>
    </div>

    <?php if (empty($bookings)): ?>
      <div class="alert alert-info text-center shadow-sm">
        <i class="bi bi-info-circle"></i> ยังไม่มีการจองในระบบ
      </div>
    <?php else: ?>

      <div class="card p-3">
        <div class="filter-bar">
          <div>
            <input type="text" id="searchInput" class="form-control" placeholder="🔍 ค้นหาแพ็กเกจ / วันที่...">
          </div>
          <div class="text-muted small mt-2 mt-md-0">
            แสดงทั้งหมด <?= count($bookings) ?> รายการ
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-bordered table-hover align-middle" id="bookingTable">
            <thead>
              <tr>
                <th>#</th>
                <th>แพ็กเกจ</th>
                <th>วันที่</th>
                <th>เวลา</th>
                <th>หมายเหตุ</th>
                <th>ราคา</th>
                <th>สถานะ</th>
              </tr>
            </thead>
            <tbody>
              <?php $i = 1;
              foreach ($bookings as $b): ?>
                <?php
                $status = $b['status'] ?? 'pending';
                $map = [
                  'pending' => ['🕒 รอดำเนินการ', 'pending'],
                  'confirmed' => ['✅ ยืนยันแล้ว', 'confirmed'],
                  'completed' => ['🐾 เสร็จสิ้น', 'completed'],
                  'cancelled' => ['❌ ยกเลิก', 'cancelled']
                ];
                ?>
                <tr>
                  <td><?= $i++ ?></td>
                  <td class="fw-semibold"><?= htmlspecialchars($b['name_th']) ?></td>
                  <td><?= htmlspecialchars($b['booking_date']) ?></td>
                  <td><?= htmlspecialchars(substr($b['booking_time'], 0, 5)) ?></td>
                  <td><?= htmlspecialchars($b['note'] ?: '-') ?></td>
                  <td class="fw-bold text-success"><?= number_format($b['price'], 0) ?> ฿</td>
                  <td>
                    <span class="status-badge <?= $map[$status][1] ?>">
                      <?= $map[$status][0] ?>
                    </span>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

    <?php endif; ?>
  </div>

  <script>
    // ✅ ค้นหาในตาราง
    document.getElementById('searchInput').addEventListener('keyup', function() {
      const filter = this.value.toLowerCase();
      document.querySelectorAll('#bookingTable tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
      });
    });
  </script>
</body>

</html>
