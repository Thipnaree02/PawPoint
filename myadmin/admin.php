<?php
include 'config/db.php';

// ✅ เพิ่มผู้ใช้ใหม่
if (isset($_POST['add_user'])) {
  $fullname = $_POST['fullname'];
  $username = $_POST['username'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $role = $_POST['role'];

  $photo = "default_admin.png";
  if (!empty($_FILES['profile_image']['name'])) {
    $photo = time() . "_" . basename($_FILES["profile_image"]["name"]);
    move_uploaded_file($_FILES["profile_image"]["tmp_name"], "uploads/" . $photo);
  }

  $stmtAdmin = $conn->prepare("INSERT INTO admins (fullname, username, password, email, phone, role, profile_image) VALUES (?, ?, ?, ?, ?, ?, ?)");
  $stmtAdmin->execute([$fullname, $username, $password, $email, $phone, $role, $photo]);
  header("Location: admin.php?action=added");
  exit;
}

// ✅ ลบผู้ใช้
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  $stmtAdmin = $conn->prepare("DELETE FROM admins WHERE admin_id=?");
  $stmtAdmin->execute([$id]);
  header("Location: admin.php?action=deleted");
  exit;
}

// ✅ แก้ไขผู้ใช้
if (isset($_POST['edit_user'])) {
  $id = $_POST['admin_id'];
  $fullname = $_POST['fullname'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $role = $_POST['role'];

  if (!empty($_FILES['profile_image']['name'])) {
    $photo = time() . "_" . basename($_FILES["profile_image"]["name"]);
    move_uploaded_file($_FILES["profile_image"]["tmp_name"], "uploads/" . $photo);
    $stmtAdmin = $conn->prepare("UPDATE admins SET fullname=?, email=?, phone=?, role=?, profile_image=? WHERE admin_id=?");
    $stmtAdmin->execute([$fullname, $email, $phone, $role, $photo, $id]);
  } else {
    $stmtAdmin = $conn->prepare("UPDATE admins SET fullname=?, email=?, phone=?, role=? WHERE admin_id=?");
    $stmtAdmin->execute([$fullname, $email, $phone, $role, $id]);
  }
  header("Location: admin.php?action=edited");
  exit;
}

// ✅ ดึงข้อมูลทั้งหมด
$search = "";
if (isset($_GET['search']) && $_GET['search'] !== "") {
  $search = trim($_GET['search']);
  $stmtAdmin = $conn->prepare("SELECT * FROM admins 
                               WHERE fullname LIKE ? 
                               OR username LIKE ? 
                               OR email LIKE ? 
                               OR phone LIKE ? 
                               ORDER BY admin_id DESC");
  $stmtAdmin->execute(["%$search%", "%$search%", "%$search%", "%$search%"]);
} else {
  $stmtAdmin = $conn->query("SELECT * FROM admins ORDER BY admin_id DESC");
}
$admins = $stmtAdmin->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="th">

<head>
  <meta charset="utf-8">
  <title>จัดการผู้ใช้ - Elivet Admin</title>
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

    .sidebar {
      width: 260px;
      background: #fff;
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      border-right: 1px solid #eaeaea;
      padding: 1rem;
    }

    .sidebar .brand {
      display: flex;
      align-items: center;
      font-weight: 600;
      font-size: 20px;
      color: #2c7a3d;
      margin-bottom: 2rem;
    }

    .sidebar .brand i {
      background: #8bdc65;
      padding: 8px;
      border-radius: 10px;
      color: #1c461a;
      margin-right: 8px;
    }

    .sidebar a {
      display: block;
      color: #444;
      text-decoration: none;
      padding: .6rem .9rem;
      border-radius: 8px;
      margin-bottom: 4px;
      font-weight: 500;
    }

    .sidebar a:hover,
    .sidebar a.active {
      background: #e9f8ea;
      color: #1d5e28;
    }

    .main {
      margin-left: 260px;
      padding: 2rem;
    }

    .navbar-custom {
      background: #fff;
      border-bottom: 1px solid #eee;
      padding: .8rem 1.2rem;
    }
  </style>
</head>

<body>
    <?php include 'sidebar.php'; ?>

  <!-- Main Content -->
  <main class="main">
    <nav class="navbar navbar-custom d-flex justify-content-between align-items-center mb-4">
      <h4 class="m-0 fw-bold">จัดการผู้ใช้ระบบ</h4>
      <form method="get" class="d-flex align-items-center" style="gap:8px;">
        <input type="text" name="search" class="form-control" placeholder="ค้นหาชื่อ / อีเมล / เบอร์โทร..."
          value="<?= htmlspecialchars($search) ?>" style="width:220px;">
        <button class="btn btn-outline-success" type="submit"><i class="bi bi-search"></i></button>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal" type="button"><i
            class="bi bi-plus-lg"></i> เพิ่มผู้ใช้ใหม่</button>
      </form>
    </nav>

    <div class="card shadow-sm">
      <div class="card-body">
        <table class="table table-bordered table-hover align-middle">
          <thead class="table-success text-center">
            <tr>
              <th>No.</th>
              <th>รูปภาพ</th>
              <th>ชื่อ-นามสกุล</th>
              <th>ชื่อผู้ใช้</th>
              <th>อีเมล</th>
              <th>เบอร์โทร</th>
              <th>สิทธิ์</th>
              <th>จัดการ</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if (empty($admins)): ?>
              <tr>
                <td colspan="8" class="text-center text-muted py-3">ไม่พบข้อมูล</td>
              </tr>
            <?php else:
              $i = 1;
              foreach ($admins as $ad): ?>
                <tr>
                  <td class="text-center"><?= $i++ ?></td>
                  <td class="text-center"><img src="uploads/<?= $ad['profile_image'] ?>" width="50" class="rounded-circle">
                  </td>
                  <td><?= $ad['fullname'] ?></td>
                  <td><?= $ad['username'] ?></td>
                  <td><?= $ad['email'] ?></td>
                  <td><?= $ad['phone'] ?></td>
                  <td><?= $ad['role'] ?></td>
                  <td class="text-center">
                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                      data-bs-target="#editModal<?= $ad['admin_id'] ?>"><i class="bi bi-pencil"></i></button>
                    <a href="?delete=<?= $ad['admin_id'] ?>" class="btn btn-sm btn-danger delete-btn"><i
                        class="bi bi-trash"></i></a>
                  </td>
                </tr>

                <!-- 🟡 Modal แก้ไขผู้ใช้ -->
                <div class="modal fade" id="editModal<?= $ad['admin_id'] ?>" tabindex="-1">
                  <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content border-0 shadow-lg">
                      <form method="post" enctype="multipart/form-data">
                        <div class="modal-header bg-warning text-dark">
                          <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i> แก้ไขข้อมูลผู้ใช้</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body px-4 py-3">
                          <input type="hidden" name="admin_id" value="<?= $ad['admin_id'] ?>">
                          <div class="row g-3">
                            <div class="col-md-6">
                              <label class="form-label">ชื่อ-นามสกุล</label>
                              <input type="text" name="fullname" value="<?= $ad['fullname'] ?>" class="form-control"
                                required>
                            </div>
                            <div class="col-md-6">
                              <label class="form-label">อีเมล</label>
                              <input type="email" name="email" value="<?= $ad['email'] ?>" class="form-control">
                            </div>
                            <div class="col-md-6">
                              <label class="form-label">เบอร์โทร</label>
                              <input type="text" name="phone" value="<?= $ad['phone'] ?>" class="form-control">
                            </div>
                            <div class="col-md-6">
                              <label class="form-label">สิทธิ์การใช้งาน</label>
                              <select name="role" class="form-select">
                                <option value="Staff" <?= $ad['role'] == "Staff" ? "selected" : "" ?>>Staff</option>
                                <option value="Manager" <?= $ad['role'] == "Manager" ? "selected" : "" ?>>Manager</option>
                              </select>
                            </div>
                            <div class="col-md-12">
                              <label class="form-label">เปลี่ยนรูปโปรไฟล์ (ถ้ามี)</label>
                              <input type="file" name="profile_image" class="form-control">
                            </div>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="submit" name="edit_user" class="btn btn-warning"><i
                              class="bi bi-check-circle me-1"></i> บันทึก</button>
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

  <?php include 'modal_add_user.php'; // 👉 ใช้โค้ด modal เดิมที่คุณมีอยู่ ?>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const params = new URLSearchParams(window.location.search);
      const action = params.get("action");

      if (action === "added") Swal.fire({ icon: "success", title: "เพิ่มผู้ใช้สำเร็จ!", text: "ข้อมูลถูกบันทึกเรียบร้อยแล้ว", confirmButtonColor: "#4ca771" });
      if (action === "edited") Swal.fire({ icon: "success", title: "แก้ไขข้อมูลสำเร็จ!", text: "บันทึกการเปลี่ยนแปลงเรียบร้อยแล้ว", confirmButtonColor: "#4ca771" });
      if (action === "deleted") Swal.fire({ icon: "success", title: "ลบข้อมูลสำเร็จ!", text: "ผู้ใช้ถูกลบออกจากระบบแล้ว", confirmButtonColor: "#4ca771" });

      document.querySelectorAll(".delete-btn").forEach(btn => {
        btn.addEventListener("click", e => {
          e.preventDefault();
          Swal.fire({
            title: "ต้องการลบผู้ใช้นี้หรือไม่?",
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