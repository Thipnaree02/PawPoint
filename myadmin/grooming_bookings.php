<?php
include 'config/db.php';

// ดึงข้อมูลการจองทั้งหมด
$stmt = $conn->query("
  SELECT 
    gb.*, 
    u.username AS customer_name, 
    gp.name_th AS package_name, 
    gp.price
  FROM grooming_bookings gb
  LEFT JOIN users u ON gb.user_id = u.user_id
  LEFT JOIN grooming_packages gp ON gb.package_id = gp.id
  ORDER BY gb.booking_date DESC, gb.booking_time ASC
");
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="th">

<head>
  <meta charset="UTF-8">
  <title>ตารางอาบน้ำ / ตัดขนสัตว์เลี้ยง | Elivet Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@400;600&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    * {
      font-family: 'Noto Sans Thai', sans-serif;
    }

    body {
      background: #f7f9fb;
      display: flex;
      overflow-x: hidden;
      height: 100vh;
    }

    .main-content {
      margin-left: 260px;
      padding: 2rem;
      flex: 1;
      overflow-y: auto;
    }

    .card {
      border-radius: 12px;
      box-shadow: 0 3px 12px rgba(0, 0, 0, 0.05);
      border: none;
    }

    .table th {
      background-color: #198754 !important;
      color: #fff;
      text-align: center;
      font-weight: 500;
    }

    .table td {
      text-align: center;
      vertical-align: middle;
      background: #fff;
    }

    /* ✅ ปรับสีสถานะให้ชัดเจน */
    .status-select {
      border-radius: 10px;
      padding: 6px 10px;
      border: none;
      font-weight: 600;
      font-size: 0.9rem;
      text-align: center;
      width: 160px;
      color: #fff;
      box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease-in-out;
      cursor: pointer;
    }

    .status-select.status-pending {
      background-color: #ffcc00;
      color: #000;
    }

    .status-select.status-pending:hover {
      background-color: #ffdb4d;
    }

    .status-select.status-confirmed {
      background-color: #007bff;
    }

    .status-select.status-confirmed:hover {
      background-color: #3399ff;
    }

    .status-select.status-completed {
      background-color: #28a745;
    }

    .status-select.status-completed:hover {
      background-color: #34ce57;
    }

    .status-select.status-cancelled {
      background-color: #dc3545;
    }

    .status-select.status-cancelled:hover {
      background-color: #e4606d;
    }

    .btn-delete {
      background-color: #dc3545;
      color: white;
      border: none;
      padding: 6px 10px;
      border-radius: 8px;
      transition: 0.2s;
    }

    .btn-delete:hover {
      background-color: #b02a37;
      transform: scale(1.05);
    }

    .search-box {
      max-width: 300px;
    }

    .search-box input {
      border-radius: 20px;
      border: 1px solid #ddd;
      padding: 8px 15px;
    }

    .header-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
    }
  </style>
</head>

<body>
  <?php include 'sidebar.php'; ?>

  <div class="main-content">
    <div class="header-bar mb-4">
      <h3 class="fw-bold text-success">
        <i class="bi bi-scissors"></i> ตารางอาบน้ำ / ตัดขนสัตว์เลี้ยง
      </h3>
      <div class="search-box">
        <input type="text" id="searchInput" class="form-control" placeholder="ค้นหาชื่อลูกค้า / แพ็กเกจ / วันที่...">
      </div>
    </div>

    <div class="card p-3">
      <table class="table table-bordered table-hover align-middle" id="bookingTable">
        <thead>
          <tr>
            <th>#</th>
            <th>วันที่</th>
            <th>เวลา</th>
            <th>ลูกค้า</th>
            <th>แพ็กเกจ</th>
            <th>ราคา</th>
            <th>สถานะ</th>
            <th>การจัดการ</th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 1;
          foreach ($bookings as $b): ?>
            <tr>
              <td><?= $i++ ?></td>
              <td><?= htmlspecialchars($b['booking_date']) ?></td>
              <td><?= htmlspecialchars(substr($b['booking_time'], 0, 5)) ?></td>
              <td><?= htmlspecialchars($b['customer_name'] ?? '-') ?></td>
              <td><?= htmlspecialchars($b['package_name']) ?></td>
              <td><?= number_format($b['price'], 0) ?> ฿</td>
              <td>
                <select class="status-select <?= 'status-' . $b['status'] ?>"
                  onchange="changeStatus(<?= $b['id'] ?>, this.value)">
                  <option value="pending" <?= $b['status'] == 'pending' ? 'selected' : '' ?>>รอดำเนินการ</option>
                  <option value="confirmed" <?= $b['status'] == 'confirmed' ? 'selected' : '' ?>>ยืนยันแล้ว</option>
                  <option value="completed" <?= $b['status'] == 'completed' ? 'selected' : '' ?>>เสร็จสิ้น</option>
                  <option value="cancelled" <?= $b['status'] == 'cancelled' ? 'selected' : '' ?>>ยกเลิก</option>
                </select>
              </td>
              <td>
                <button class="btn-delete" onclick="deleteBooking(<?= $b['id'] ?>)">
                  <i class="bi bi-trash3"></i>
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // ✅ ค้นหา
    document.getElementById('searchInput').addEventListener('keyup', function () {
      const value = this.value.toLowerCase();
      document.querySelectorAll('#bookingTable tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(value) ? '' : 'none';
      });
    });

    // ✅ เปลี่ยนสถานะ
    function changeStatus(id, status) {
      Swal.fire({
        title: 'ยืนยันการเปลี่ยนสถานะ?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'ตกลง',
        cancelButtonText: 'ยกเลิก'
      }).then(result => {
        if (result.isConfirmed) {
          fetch('update_grooming_status.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${id}&status=${status}`
          })
            .then(res => res.json())
            .then(data => {
              if (data.success) {
                Swal.fire('อัปเดตสำเร็จ', '', 'success').then(() => location.reload());
              } else {
                Swal.fire('เกิดข้อผิดพลาด', data.message, 'error');
              }
            });
        } else {
          location.reload();
        }
      });
    }

    // ✅ ลบข้อมูล
    function deleteBooking(id) {
      Swal.fire({
        title: 'แน่ใจหรือไม่?',
        text: 'ข้อมูลนี้จะถูกลบออกถาวร',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'ลบเลย',
        cancelButtonText: 'ยกเลิก',
        confirmButtonColor: '#dc3545'
      }).then(result => {
        if (result.isConfirmed) {
          fetch('delete_booking.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${id}`
          })
            .then(res => res.json())
            .then(data => {
              if (data.success) {
                Swal.fire('ลบสำเร็จ!', '', 'success').then(() => location.reload());
              } else {
                Swal.fire('เกิดข้อผิดพลาด', data.message, 'error');
              }
            });
        }
      });
    }
  </script>
</body>

</html>