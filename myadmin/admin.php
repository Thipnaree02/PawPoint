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
    header("Location: admin.php");
    exit;
}

// ✅ ลบผู้ใช้
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmtAdmin = $conn->prepare("DELETE FROM admins WHERE admin_id=?");
    $stmtAdmin->execute([$id]);
    header("Location: admin.php");
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
    header("Location: admin.php");
    exit;
}

// ✅ ดึงข้อมูลทั้งหมด (พร้อมค้นหา)
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
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="brand"><i class="bi bi-hospital"></i> Elivet</div>
        <a href="index.html"><i class="bi bi-speedometer2 me-2"></i>แดชบอร์ด</a>
        <a href="admin.php" class="active"><i class="bi bi-person-gear me-2"></i>ผู้ดูแลระบบ</a>
        <a href="#"><i class="bi bi-calendar-week me-2"></i>ตารางนัดหมาย</a>
        <a href="#"><i class="bi bi-bandaid me-2"></i>บริการสัตว์เลี้ยง</a>
        <a href="#"><i class="bi bi-cash-coin me-2"></i>รายงานรายได้</a>
    </aside>

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
                <?php if ($search !== ""): ?>
                    <p class="text-muted mb-2">ผลการค้นหาสำหรับ "<b><?= htmlspecialchars($search) ?></b>"</p>
                <?php endif; ?>
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-success text-center">
                        <tr>
                            <th width="70">No.</th>
                            <th>รูปภาพ</th>
                            <th>ชื่อ-นามสกุล</th>
                            <th>ชื่อผู้ใช้</th>
                            <th>อีเมล</th>
                            <th>เบอร์โทร</th>
                            <th>สิทธิ์</th>
                            <th>วันที่สร้าง</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($admins)): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-3">ไม่พบข้อมูล</td>
                            </tr>
                        <?php else:
                           $i = 1;
                            foreach ($admins as $ad):  ?>
                                <tr>
                                    <td class="text-center"><img src="uploads/<?= $ad['profile_image'] ?>" width="50"
                                            class="rounded-circle"></td>
                                    <td><?= $ad['fullname'] ?></td>
                                    <td><?= $ad['username'] ?></td>
                                    <td><?= $ad['email'] ?></td>
                                    <td><?= $ad['phone'] ?></td>
                                    <td><?= $ad['role'] ?></td>
                                    <td><?= $ad['created_at'] ?></td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#editModal<?= $ad['admin_id'] ?>"><i
                                                class="bi bi-pencil"></i></button>
                                        <a href="?delete=<?= $ad['admin_id'] ?>" class="btn btn-sm btn-danger"
                                            onclick="return confirm('ลบผู้ใช้นี้หรือไม่?')"><i class="bi bi-trash"></i></a>
                                    </td>
                                </tr>

                                <!-- Modal แก้ไข -->
                                <div class="modal fade" id="editModal<?= $ad['admin_id'] ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="post" enctype="multipart/form-data">
                                                <div class="modal-header bg-warning-subtle">
                                                    <h5 class="modal-title">แก้ไขข้อมูลผู้ใช้</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="admin_id" value="<?= $ad['admin_id'] ?>">
                                                    <div class="mb-3"><label>ชื่อ-นามสกุล</label><input type="text"
                                                            name="fullname" value="<?= $ad['fullname'] ?>" class="form-control">
                                                    </div>
                                                    <div class="mb-3"><label>อีเมล</label><input type="email" name="email"
                                                            value="<?= $ad['email'] ?>" class="form-control"></div>
                                                    <div class="mb-3"><label>เบอร์โทร</label><input type="text" name="phone"
                                                            value="<?= $ad['phone'] ?>" class="form-control"></div>
                                                    <div class="mb-3"><label>สิทธิ์</label>
                                                        <select name="role" class="form-select">
                                                            <option value="Manager" <?= ($ad['role'] == 'Manager' ? 'selected' : '') ?>>
                                                                Manager</option>
                                                            <option value="Staff" <?= ($ad['role'] == 'Staff' ? 'selected' : '') ?>>
                                                                Staff</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3"><label>เปลี่ยนรูปโปรไฟล์ (ถ้ามี)</label>
                                                        <input type="file" name="profile_image" class="form-control">
                                                        <div class="mt-2"><img src="uploads/<?= $ad['profile_image'] ?>"
                                                                width="60" class="rounded-circle"></div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" name="edit_user"
                                                        class="btn btn-warning">บันทึก</button>
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">ยกเลิก</button>
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

    <!-- Modal เพิ่มผู้ใช้ (เวอร์ชันใหม่ สวยกว่าเดิม) -->
<div class="modal fade" id="addModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg">
      <form method="post" enctype="multipart/form-data">
        <div class="modal-header bg-gradient p-3" style="background:linear-gradient(90deg, #6fba82, #4ca771); color:white;">
          <h5 class="modal-title fw-bold"><i class="bi bi-person-plus-fill me-2"></i>เพิ่มผู้ใช้ใหม่</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body px-4 py-3">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold"><i class="bi bi-person-badge me-1 text-success"></i> ชื่อ-นามสกุล</label>
              <input type="text" name="fullname" class="form-control form-control-lg rounded-3 shadow-sm" placeholder="เช่น น.ส. ทิพย์นารี เพตาเสน" required>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold"><i class="bi bi-person me-1 text-success"></i> ชื่อผู้ใช้</label>
              <input type="text" name="username" class="form-control form-control-lg rounded-3 shadow-sm" placeholder="Username" required>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold"><i class="bi bi-lock-fill me-1 text-success"></i> รหัสผ่าน</label>
              <input type="password" name="password" class="form-control form-control-lg rounded-3 shadow-sm" placeholder="Password" required>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold"><i class="bi bi-envelope-fill me-1 text-success"></i> อีเมล</label>
              <input type="email" name="email" class="form-control form-control-lg rounded-3 shadow-sm" placeholder="example@email.com">
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold"><i class="bi bi-telephone-fill me-1 text-success"></i> เบอร์โทร</label>
              <input type="text" name="phone" class="form-control form-control-lg rounded-3 shadow-sm" placeholder="08xxxxxxxx">
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold"><i class="bi bi-person-gear me-1 text-success"></i> สิทธิ์การใช้งาน</label>
              <select name="role" class="form-select form-select-lg rounded-3 shadow-sm">
                <option value="Staff">Staff</option>
                <option value="Manager">Manager</option>
              </select>
            </div>

            <div class="col-md-12">
              <label class="form-label fw-semibold"><i class="bi bi-image me-1 text-success"></i> รูปโปรไฟล์</label>
              <input type="file" name="profile_image" id="profileInput" class="form-control form-control-lg rounded-3 shadow-sm">
              <div class="text-center mt-3">
                <img id="profilePreview" src="https://cdn-icons-png.flaticon.com/512/847/847969.png" 
                     width="100" class="rounded-circle shadow-sm border border-2 border-success-subtle" alt="preview">
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer bg-light rounded-bottom-3">
          <button type="submit" name="add_user" class="btn btn-success btn-lg px-4 rounded-3 shadow-sm">
            <i class="bi bi-check-circle me-1"></i> บันทึก
          </button>
          <button type="button" class="btn btn-secondary btn-lg px-4 rounded-3" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-1"></i> ยกเลิก
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- สคริปต์แสดงตัวอย่างรูปโปรไฟล์ -->
<script>
document.getElementById('profileInput').addEventListener('change', function(e) {
  const file = e.target.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = function(event) {
    document.getElementById('profilePreview').src = event.target.result;
  }
  reader.readAsDataURL(file);
});
</script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>