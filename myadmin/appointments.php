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

// ✅ ดึงข้อมูล (พร้อมระบบค้นหา)
$search = "";
if (isset($_GET['search']) && $_GET['search'] !== "") {
  $search = trim($_GET['search']);
  $stmtAppoint = $conn->prepare("
    SELECT 
        a.app_id,
        a.pet_name,
        a.vet_id,
        v.vet_name,
        a.date,
        a.time,
        a.status
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
    SELECT 
        a.app_id,
        a.pet_name,
        a.vet_id,
        v.vet_name,
        a.date,
        a.time,
        a.status
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
  <title>ตารางนัดหมาย - Elivet Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@400;600&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    * { font-family: 'Noto Sans Thai', sans-serif; }
    body { background: #f7f9fb; }
    .main { margin-left: 260px; padding: 2rem; }
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
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal" type="button">
          <i class="bi bi-plus-lg"></i> เพิ่มนัดหมาย
        </button>
      </form>
    </nav>

    <div class="card shadow-sm">
      <div class="card-body">
        <table class="table table-bordered table-hover align-middle">
          <thead class="table-success text-center">
            <tr>
              <th>No.</th>
              <th>ชื่อสัตว์เลี้ยง</th>
              <th>สัตวแพทย์</th>
              <th>วันที่</th>
              <th>เวลา</th>
              <th>สถานะ</th>
              <th>จัดการ</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($appointments)): ?>
              <tr><td colspan="7" class="text-center text-muted py-3">ไม่พบข้อมูล</td></tr>
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
                    <?php if ($app['status'] == 'pending'): ?>
                      <span class="badge bg-warning text-dark">รอดำเนินการ</span>
                    <?php elseif ($app['status'] == 'confirmed'): ?>
                      <span class="badge bg-primary">ยืนยันแล้ว</span>
                    <?php elseif ($app['status'] == 'completed'): ?>
                      <span class="badge bg-success">เสร็จสิ้น</span>
                    <?php else: ?>
                      <span class="badge bg-danger">ยกเลิก</span>
                    <?php endif; ?>
                  </td>
                  <td class="text-center">
                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                      data-bs-target="#editModal<?= $app['app_id'] ?>"><i class="bi bi-pencil"></i></button>
                    <a href="?delete=<?= $app['app_id'] ?>" class="btn btn-sm btn-danger delete-btn"><i class="bi bi-trash"></i></a>
                  </td>
                </tr>
              <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const params = new URLSearchParams(window.location.search);
      const action = params.get("action");
      if (action === "added") Swal.fire({ icon: "success", title: "เพิ่มนัดหมายสำเร็จ!", confirmButtonColor: "#4ca771" });
      if (action === "edited") Swal.fire({ icon: "success", title: "แก้ไขข้อมูลสำเร็จ!", confirmButtonColor: "#4ca771" });
      if (action === "deleted") Swal.fire({ icon: "success", title: "ลบข้อมูลสำเร็จ!", confirmButtonColor: "#4ca771" });
    });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
