<?php
include 'config/db.php';

// ✅ เพิ่มนัดหมายใหม่
if (isset($_POST['add_appointment'])) {
    $pet_id = $_POST['pet_id'];
    $vet_id = $_POST['vet_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $status = $_POST['status'];

    $stmtAppoint = $conn->prepare("INSERT INTO appointments (pet_id, vet_id, date, time, status) VALUES (?, ?, ?, ?, ?)");
    $stmtAppoint->execute([$pet_id, $vet_id, $date, $time, $status]);
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
    $pet_id = $_POST['pet_id'];
    $vet_id = $_POST['vet_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $status = $_POST['status'];

    $stmtAppoint = $conn->prepare("UPDATE appointments SET pet_id=?, vet_id=?, date=?, time=?, status=? WHERE app_id=?");
    $stmtAppoint->execute([$pet_id, $vet_id, $date, $time, $status, $id]);
    header("Location: appointments.php?action=edited");
    exit;
}

// ✅ ดึงข้อมูล (พร้อมระบบค้นหา)
$search = "";
if (isset($_GET['search']) && $_GET['search'] !== "") {
    $search = trim($_GET['search']);
    $stmtAppoint = $conn->prepare("SELECT * FROM appointments 
                            WHERE pet_id LIKE ? 
                               OR vet_id LIKE ? 
                               OR date LIKE ? 
                               OR time LIKE ? 
                               OR status LIKE ?
                            ORDER BY app_id DESC");
    $stmtAppoint->execute(["%$search%", "%$search%", "%$search%", "%$search%", "%$search%"]);
} else {
    $stmtAppoint = $conn->query("SELECT * FROM appointments ORDER BY app_id DESC");
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
        .sidebar {
            width: 260px; background: #fff; height: 100vh;
            position: fixed; top: 0; left: 0;
            border-right: 1px solid #eaeaea; padding: 1rem;
        }
        .sidebar .brand {
            display: flex; align-items: center;
            font-weight: 600; font-size: 20px;
            color: #2c7a3d; margin-bottom: 2rem;
        }
        .sidebar .brand i {
            background: #8bdc65; padding: 8px;
            border-radius: 10px; color: #1c461a; margin-right: 8px;
        }
        .sidebar a {
            display: block; color: #444; text-decoration: none;
            padding: .6rem .9rem; border-radius: 8px;
            margin-bottom: 4px; font-weight: 500;
        }
        .sidebar a:hover, .sidebar a.active {
            background: #e9f8ea; color: #1d5e28;
        }
        .main { margin-left: 260px; padding: 2rem; }
        .navbar-custom {
            background: #fff; border-bottom: 1px solid #eee;
            padding: .8rem 1.2rem;
        }
    </style>
</head>

<body>
       <?php include 'sidebar.php'; ?>

    <!-- Main -->
    <main class="main">
        <nav class="navbar navbar-custom d-flex justify-content-between align-items-center mb-4">
            <h4 class="m-0 fw-bold">ตารางนัดหมาย</h4>
            <form method="get" class="d-flex align-items-center" style="gap:8px;">
                <input type="text" name="search" class="form-control" placeholder="ค้นหา รหัสสัตว์เลี้ยง / รหัสสัตวแพทย์ / วันที่ / เวลา / สถานะ"
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
                            <th>รหัสสัตว์เลี้ยง</th>
                            <th>รหัสสัตวแพทย์</th>
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
                                <td><?= htmlspecialchars($app['pet_id']) ?></td>
                                <td><?= htmlspecialchars($app['vet_id']) ?></td>
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
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $app['app_id'] ?>"><i class="bi bi-pencil"></i></button>
                                    <a href="?delete=<?= $app['app_id'] ?>" class="btn btn-sm btn-danger delete-btn"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>

                            <!-- Modal แก้ไข -->
                            <div class="modal fade" id="editModal<?= $app['app_id'] ?>" tabindex="-1">
                              <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                  <form method="post">
                                    <div class="modal-header bg-warning">
                                      <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i> แก้ไขนัดหมาย</h5>
                                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                      <input type="hidden" name="app_id" value="<?= $app['app_id'] ?>">
                                      <div class="mb-3">
                                        <label class="form-label">รหัสสัตว์เลี้ยง</label>
                                        <input type="number" name="pet_id" value="<?= $app['pet_id'] ?>" class="form-control" required>
                                      </div>
                                      <div class="mb-3">
                                        <label class="form-label">รหัสสัตวแพทย์</label>
                                        <input type="number" name="vet_id" value="<?= $app['vet_id'] ?>" class="form-control" required>
                                      </div>
                                      <div class="mb-3">
                                        <label class="form-label">วันที่</label>
                                        <input type="date" name="date" value="<?= $app['date'] ?>" class="form-control" required>
                                      </div>
                                      <div class="mb-3">
                                        <label class="form-label">เวลา</label>
                                        <input type="time" name="time" value="<?= $app['time'] ?>" class="form-control" required>
                                      </div>
                                      <div class="mb-3">
                                        <label class="form-label">สถานะ</label>
                                        <select name="status" class="form-select">
                                          <option value="pending" <?= $app['status']=="pending"?"selected":"" ?>>รอดำเนินการ</option>
                                          <option value="confirmed" <?= $app['status']=="confirmed"?"selected":"" ?>>ยืนยันแล้ว</option>
                                          <option value="completed" <?= $app['status']=="completed"?"selected":"" ?>>เสร็จสิ้น</option>
                                          <option value="cancelled" <?= $app['status']=="cancelled"?"selected":"" ?>>ยกเลิก</option>
                                        </select>
                                      </div>
                                    </div>
                                    <div class="modal-footer">
                                      <button type="submit" name="edit_appointment" class="btn btn-warning">บันทึก</button>
                                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                                    </div>
                                  </form>
                                </div>
                              </div>
                            </div>

                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Modal เพิ่มนัดหมาย -->
    <div class="modal fade" id="addModal" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <form method="post">
            <div class="modal-header bg-success text-white">
              <h5 class="modal-title"><i class="bi bi-calendar-plus me-2"></i> เพิ่มนัดหมายใหม่</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label class="form-label">รหัสสัตว์เลี้ยง</label>
                <input type="number" name="pet_id" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">รหัสสัตวแพทย์</label>
                <input type="number" name="vet_id" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">วันที่</label>
                <input type="date" name="date" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">เวลา</label>
                <input type="time" name="time" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">สถานะ</label>
                <select name="status" class="form-select">
                  <option value="pending">รอดำเนินการ</option>
                  <option value="confirmed">ยืนยันแล้ว</option>
                  <option value="completed">เสร็จสิ้น</option>
                  <option value="cancelled">ยกเลิก</option>
                </select>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" name="add_appointment" class="btn btn-success">บันทึก</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
            </div>
          </form>
        </div>
      </div>
    </div>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const params = new URLSearchParams(window.location.search);
  const action = params.get("action");

  if (action === "added") Swal.fire({ icon: "success", title: "เพิ่มนัดหมายสำเร็จ!", text: "บันทึกข้อมูลเรียบร้อย", confirmButtonColor: "#4ca771" });
  if (action === "edited") Swal.fire({ icon: "success", title: "แก้ไขข้อมูลสำเร็จ!", text: "ข้อมูลถูกอัปเดตเรียบร้อยแล้ว", confirmButtonColor: "#4ca771" });
  if (action === "deleted") Swal.fire({ icon: "success", title: "ลบข้อมูลสำเร็จ!", text: "นัดหมายถูกลบออกแล้ว", confirmButtonColor: "#4ca771" });

  document.querySelectorAll(".delete-btn").forEach(btn => {
    btn.addEventListener("click", e => {
      e.preventDefault();
      Swal.fire({
        title: "ต้องการลบนัดหมายนี้หรือไม่?",
        text: "เมื่อลบแล้วจะไม่สามารถกู้คืนได้!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#e74c3c",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "ลบเลย",
        cancelButtonText: "ยกเลิก"
      }).then(result => {
        if (result.isConfirmed) window.location.href = btn.href;
      });
    });
  });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
