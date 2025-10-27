<?php
include 'config/db.php';

// ✅ เพิ่มนัดหมายใหม่
if (isset($_POST['add_appointment'])) {
  $pet_name = $_POST['pet_name'];
  $vet_id = $_POST['vet_id'];
  $date = $_POST['date'];
  $time = $_POST['time'];
  $status = $_POST['status'];

  $stmtAppoint = $conn->prepare("INSERT INTO appointments (pet_name, vet_id, date, time, status) VALUES (?, ?, ?, ?, ?)");
  $stmtAppoint->execute([$pet_name, $vet_id, $date, $time, $status]);
  header("Location: appointments.php?action=added");
  exit;
}

// ✅ ลบนัดหมาย
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  $stmtAppoint = $conn->prepare("DELETE FROM appointments WHERE app_id=?");
  $stmtAppoint->execute([$id]);
  header("Location: appointments.php?action=deleted");
  exit;
}

// ✅ แก้ไขนัดหมาย
if (isset($_POST['edit_appointment'])) {
  $id = $_POST['app_id'];
  $pet_name = $_POST['pet_name'];
  $vet_id = $_POST['vet_id'];
  $date = $_POST['date'];
  $time = $_POST['time'];
  $status = $_POST['status'];

  $stmtAppoint = $conn->prepare("UPDATE appointments SET pet_name=?, vet_id=?, date=?, time=?, status=? WHERE app_id=?");
  $stmtAppoint->execute([$pet_name, $vet_id, $date, $time, $status, $id]);
  header("Location: appointments.php?action=edited");
  exit;
}

// ✅ ดึงข้อมูล
$search = "";
if (isset($_GET['search']) && $_GET['search'] !== "") {
  $search = trim($_GET['search']);
  $stmtAppoint = $conn->prepare("
    SELECT a.app_id, a.pet_name, a.vet_id, v.vet_name, a.date, a.time, a.status
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
    SELECT a.app_id, a.pet_name, a.vet_id, v.vet_name, a.date, a.time, a.status
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

    /* ✅ สีพื้นหลังสถานะ */
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
          value="<?= htmlspecialchars($search) ?>" style="width:260px;">
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
              <th>วันที่</th>
              <th>เวลา</th>
              <th>สถานะ</th>
              <th>การจัดการ</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($appointments)): ?>
              <tr>
                <td colspan="7" class="text-center text-muted py-3">ไม่พบข้อมูล</td>
              </tr>
            <?php else:
              $i = 1;
              foreach ($appointments as $app): ?>
                <tr>
                  <td class="text-center"><?= $i++ ?></td>
                  <td><?= htmlspecialchars($app['pet_name'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($app['vet_name'] ?? '-') ?></td>
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
                    <a href="?delete=<?= $app['app_id'] ?>" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>
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
    // ✅ เปลี่ยนสถานะจาก dropdown
    document.querySelectorAll('.status-select').forEach(select => {
      select.addEventListener('change', function () {
        const id = this.dataset.id;
        const status = this.value;

        // เปลี่ยนสี dropdown ตามสถานะใหม่
        this.className = 'status-select status-' + status;

        // ✅ ส่งค่าไปอัปเดตไฟล์ update_appointment_status.php
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