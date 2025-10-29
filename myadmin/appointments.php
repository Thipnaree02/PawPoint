<?php

session_start();

// ถ้ายังไม่มี session แสดงว่ายังไม่ล็อกอิน
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
include 'config/db.php';

// ✅ ฟังก์ชันลบข้อมูล
if (isset($_GET['delete'])) {
  $delete_id = $_GET['delete'];

  if (is_numeric($delete_id)) {
    $stmtDelete = $conn->prepare("DELETE FROM appointments WHERE app_id = ?");
    $stmtDelete->execute([$delete_id]);

    if ($stmtDelete->rowCount() > 0) {
      echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
      <script>
        document.addEventListener('DOMContentLoaded', function() {
          Swal.fire({
            icon: 'success',
            title: 'ลบข้อมูลสำเร็จ!',
            text: 'รายการนัดหมายถูกลบเรียบร้อยแล้ว',
            timer: 1500,
            showConfirmButton: false
          }).then(() => {
            window.location.href = 'appointments.php';
          });
        });
      </script>";
      exit;
    } else {
      echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
      <script>
        document.addEventListener('DOMContentLoaded', function() {
          Swal.fire({
            icon: 'error',
            title: 'ไม่พบข้อมูล!',
            text: 'ไม่พบรายการนัดหมายที่ต้องการลบ',
            confirmButtonColor: '#dc3545'
          }).then(() => {
            window.location.href = 'appointments.php';
          });
        });
      </script>";
      exit;
    }
  }
}

// ✅ ดึงข้อมูลตารางนัดหมาย
$search = "";
if (isset($_GET['search']) && $_GET['search'] !== "") {
  $search = trim($_GET['search']);
  $stmtAppoint = $conn->prepare("
    SELECT a.app_id, a.pet_name, a.vet_id, v.vet_name, 
           a.service_type, a.date, a.time, a.status
    FROM appointments a
    LEFT JOIN vets v ON a.vet_id = v.vet_id
    WHERE a.pet_name LIKE ? 
       OR v.vet_name LIKE ? 
       OR a.date LIKE ? 
       OR a.time LIKE ? 
       OR a.status LIKE ?
    ORDER BY a.app_id DESC
  ");
  $stmtAppoint->execute(["%$search%", "%$search%", "%$search%", "%$search%", "%$search%"]);
} else {
  $stmtAppoint = $conn->query("
    SELECT a.app_id, a.pet_name, a.vet_id, v.vet_name, 
           a.service_type, a.date, a.time, a.status
    FROM appointments a
    LEFT JOIN vets v ON a.vet_id = v.vet_id
    ORDER BY a.app_id DESC
  ");
}

$appointments = $stmtAppoint->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="th">

<head>
  <meta charset="utf-8">
  <title>ตารางนัดหมาย - ระบบคลินิกสัตว์เลี้ยง</title>
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
    }

    .main {
      margin-left: 260px;
      padding: 2rem;
    }

    .status-select {
      color: white;
      font-weight: 600;
      border: none;
      border-radius: 8px;
      padding: 6px 12px;
      text-align: center;
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

    .badge-service {
      display: inline-block;
      padding: 6px 10px;
      border-radius: 10px;
      font-weight: 600;
      font-size: 0.9rem;
    }

    .service-health {
      background-color: #b3e5fc;
      color: #0277bd;
    }

    .service-vaccine {
      background-color: #c8e6c9;
      color: #2e7d32;
    }

    .service-steril {
      background-color: #f8bbd0;
      color: #ad1457;
    }

    .service-other {
      background-color: #e0e0e0;
      color: #424242;
    }
  </style>
</head>

<body>
  <?php include 'sidebar.php'; ?>

  <main class="main">
    <nav class="navbar navbar-custom d-flex justify-content-between align-items-center mb-4">
      <h4 class="m-0 fw-bold">ตารางนัดหมาย</h4>
      <form method="get" class="d-flex align-items-center" style="gap:8px;">
        <input type="text" name="search" class="form-control"
          placeholder="ค้นหา ชื่อสัตว์เลี้ยง / สัตวแพทย์ / วันที่ / เวลา / สถานะ"
          value="<?= htmlspecialchars($search) ?>" style="width:280px;">
        <button class="btn btn-outline-success" type="submit"><i class="bi bi-search"></i></button>
        <a href="new_appointment.php" class="btn btn-success">
          <i class="bi bi-plus-lg"></i> เพิ่มนัดหมาย
        </a>
      </form>
    </nav>

    <div class="card shadow-sm">
      <div class="card-body">
        <table class="table table-bordered table-hover align-middle">
          <thead class="table-success text-center">
            <tr>
              <th>ลำดับ</th>
              <th>ชื่อสัตว์เลี้ยง</th>
              <th>สัตวแพทย์</th>
              <th>บริการ</th>
              <th>วันที่</th>
              <th>เวลา</th>
              <th>สถานะ</th>
              <th>จัดการ</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($appointments)): ?>
              <tr>
                <td colspan="8" class="text-center text-muted py-3">ไม่พบข้อมูล</td>
              </tr>
            <?php else:
              $i = 1;
              foreach ($appointments as $app): ?>
                <tr>
                  <td class="text-center"><?= $i++ ?></td>
                  <td><?= htmlspecialchars($app['pet_name'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($app['vet_name'] ?? '-') ?></td>

                  <td class="text-center">
                    <?php
                    switch ($app['service_type']) {
                      case 'health_check':
                        echo '<span class="badge-service service-health">ตรวจสุขภาพ</span>';
                        break;
                      case 'vaccination':
                        echo '<span class="badge-service service-vaccine">ฉีดวัคซีน</span>';
                        break;
                      case 'sterilization':
                        echo '<span class="badge-service service-steril">ทำหมัน</span>';
                        break;
                      default:
                        echo '<span class="badge-service service-other">ไม่ระบุ</span>';
                    }
                    ?>
                  </td>

                  <td><?= htmlspecialchars($app['date']) ?></td>
                  <td><?= htmlspecialchars($app['time']) ?></td>
                  <td class="text-center">
                    <select class="status-select status-<?= $app['status'] ?>" data-id="<?= $app['app_id'] ?>">
                      <option value="pending" <?= $app['status'] == 'pending' ? 'selected' : '' ?>>รอดำเนินการ</option>
                      <option value="confirmed" <?= $app['status'] == 'confirmed' ? 'selected' : '' ?>>ยืนยันแล้ว</option>
                      <option value="completed" <?= $app['status'] == 'completed' ? 'selected' : '' ?>>เสร็จสิ้น</option>
                      <option value="cancelled" <?= $app['status'] == 'cancelled' ? 'selected' : '' ?>>ยกเลิก</option>
                    </select>
                  </td>
                  <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger btn-delete" data-id="<?= $app['app_id'] ?>">
                      <i class="bi bi-trash"></i>
                    </button>
                  </td>
                </tr>
              <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    // ✅ ยืนยันก่อนลบ
    document.querySelectorAll('.btn-delete').forEach(btn => {
      btn.addEventListener('click', () => {
        const id = btn.dataset.id;
        Swal.fire({
          title: 'ยืนยันการลบ?',
          text: 'คุณต้องการลบรายการนี้หรือไม่?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'ลบ',
          cancelButtonText: 'ยกเลิก'
        }).then(result => {
          if (result.isConfirmed) {
            window.location.href = '?delete=' + id;
          }
        });
      });
    });

    // ✅ เปลี่ยนสถานะ
    document.querySelectorAll('.status-select').forEach(select => {
      select.addEventListener('change', function () {
        const id = this.dataset.id;
        const status = this.value;
        this.className = 'status-select status-' + status;

        fetch('update_appointment_status.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: `id=${id}&status=${status}`
        })
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              Swal.fire({
                icon: 'success',
                title: 'อัปเดตสำเร็จ',
                text: 'สถานะนัดหมายถูกเปลี่ยนเรียบร้อยแล้ว',
                timer: 1000,
                showConfirmButton: false
              });
            } else {
              Swal.fire('เกิดข้อผิดพลาด', data.message, 'error');
            }
          })
          .catch(() => Swal.fire('เกิดข้อผิดพลาด', 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้', 'error'));
      });
    });
  </script>
</body>

</html>