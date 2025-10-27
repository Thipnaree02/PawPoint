<?php
include 'config/db.php';

// ✅ ฟังก์ชันป้องกัน htmlspecialchars() null
function safe($value)
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

// ดึงข้อมูลการจอง
$stmt = $conn->query("
  SELECT 
      rb.*, 
      u.username, 
      rb.pet_name, 
      rt.name AS room_name, 
      rt.id AS room_id
  FROM room_booking rb
  LEFT JOIN users u ON rb.user_id = u.user_id
  LEFT JOIN room_type rt ON rb.room_type_id = rt.id
  ORDER BY rb.id DESC
");

$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ดึงข้อมูลห้องพักทั้งหมด
$stmtRooms = $conn->query("SELECT id, name FROM room_type ORDER BY name ASC");
$rooms = $stmtRooms->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="th">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>รายการจองห้องพัก | Elivet Admin</title>
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
      height: 100vh;
      overflow-x: hidden;
    }

    .main-content {
      margin-left: 260px;
      padding: 2rem;
      flex: 1;
      overflow-y: auto;
    }

    .table th {
      background-color: #198754 !important;
      color: white;
      text-align: center;
    }

    .table td {
      text-align: center;
      vertical-align: middle;
    }

    .card {
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      background-color: #fff;
    }

    .btn-delete {
      background-color: #dc3545;
      color: white;
      border: none;
    }

    .btn-delete:hover {
      opacity: 0.85;
    }

    /* ✅ สีพื้นหลัง dropdown แบบ grooming_booking */
    .status-select {
      color: white;
      font-weight: 600;
      border: none;
      border-radius: 8px;
      padding: 6px 12px;
    }

    .status-pending {
      background-color: #ffc107 !important;
      color: black !important;
    }

    .status-confirmed {
      background-color: #0d6efd !important;
      color: white !important;
    }

    .status-completed {
      background-color: #198754 !important;
      color: white !important;
    }

    .status-cancelled {
      background-color: #dc3545 !important;
      color: white !important;
    }

    select.status-select option {
      color: black !important;
    }
  </style>
</head>

<body>
  <?php include 'sidebar.php'; ?>

  <div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h3 class="fw-bold text-success">รายการจองห้องพัก PawPoint Condo</h3>
      <div class="search-box">
        <input type="text" id="searchInput" placeholder="ค้นหาชื่อผู้ใช้ / สัตว์เลี้ยง / ห้องพัก..." class="form-control">
      </div>
    </div>

    <div class="card p-3">
      <table class="table table-bordered table-hover align-middle" id="bookingTable">
        <thead>
          <tr class="text-center">
            <th>No.</th>
            <th>ผู้ใช้</th>
            <th>สัตว์เลี้ยง</th>
            <th>ห้องพัก</th>
            <th>เช็คอิน</th>
            <th>เช็คเอาท์</th>
            <th>ราคา</th>
            <th>สถานะ</th>
            <th>จัดการ</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $i = 1;
          $statusText = [
            'pending' => 'รอดำเนินการ',
            'confirmed' => 'ยืนยันแล้ว',
            'completed' => 'เสร็จสิ้น',
            'cancelled' => 'ยกเลิก'
          ];

          foreach ($bookings as $b): ?>
            <tr class="text-center">
              <td><?= $i++ ?></td>
              <td><?= safe($b['username'] ?? '-') ?></td>
              <td><?= safe($b['pet_name']) ?></td>
              <td><?= safe($b['room_name']) ?></td>
              <td><?= safe($b['checkin_date']) ?></td>
              <td><?= safe($b['checkout_date']) ?></td>
              <td>฿<?= number_format($b['total_price'] ?? 0, 2) ?></td>

              <!-- ✅ dropdown เปลี่ยนสถานะได้พร้อมสีพื้นหลัง -->
              <td>
                <select class="status-select status-<?= $b['status'] ?>" data-id="<?= $b['id'] ?>">
                  <?php foreach ($statusText as $key => $text): ?>
                    <option value="<?= $key ?>" <?= $b['status'] === $key ? 'selected' : '' ?>>
                      <?= $text ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </td>

              <td>
                <button class="btn btn-delete btn-sm" onclick="deleteBooking(<?= $b['id'] ?>)">
                  <i class="bi bi-trash"></i>
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    // ✅ ค้นหา
    document.getElementById('searchInput').addEventListener('keyup', function() {
      const value = this.value.toLowerCase();
      document.querySelectorAll('#bookingTable tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(value) ? '' : 'none';
      });
    });

    // ✅ ลบข้อมูล
    function deleteBooking(id) {
      Swal.fire({
        title: 'ยืนยันการลบ?',
        text: 'ข้อมูลนี้จะถูกลบออกจากระบบถาวร',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'ลบเลย',
        cancelButtonText: 'ยกเลิก',
        confirmButtonColor: '#dc3545'
      }).then((result) => {
        if (result.isConfirmed) {
          fetch('delete_booking.php', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
              },
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

    // ✅ เปลี่ยนสถานะทันทีเมื่อเลือก dropdown
    document.querySelectorAll('.status-select').forEach(select => {
      select.addEventListener('change', function() {
        const id = this.dataset.id;
        const status = this.value;

        // อัปเดตสี dropdown ตามสถานะใหม่
        this.className = 'status-select status-' + status;

        fetch('update_booking_status.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `id=${id}&status=${status}`
          })
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              Swal.fire({
                icon: 'success',
                title: 'อัปเดตสำเร็จ',
                text: 'สถานะถูกเปลี่ยนเรียบร้อยแล้ว',
                timer: 1000,
                showConfirmButton: false
              });
            } else {
              Swal.fire('เกิดข้อผิดพลาด', data.message, 'error');
            }
          })
          .catch(() => Swal.fire('เกิดข้อผิดพลาด', 'ไม่สามารถติดต่อเซิร์ฟเวอร์ได้', 'error'));
      });
    });
  </script>
</body>

</html>
