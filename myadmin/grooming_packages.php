<?php

session_start();

// ถ้ายังไม่มี session แสดงว่ายังไม่ล็อกอิน
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
include 'config/db.php';

// ✅ ป้องกัน XSS
function safe($v)
{
    return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8');
}

// ✅ เพิ่มแพ็กเกจ
if (isset($_POST['create'])) {
    $stmt = $conn->prepare("INSERT INTO grooming_packages(name_th, description_th, weight_min, weight_max, price, is_active) VALUES (?,?,?,?,?,?)");
    $stmt->execute([
        $_POST['name_th'],
        $_POST['description_th'],
        $_POST['weight_min'] ?: 0,
        $_POST['weight_max'] ?: 0,
        $_POST['price'],
        isset($_POST['is_active']) ? 1 : 0
    ]);
    header('Location: grooming_packages.php?success=1');
    exit;
}

// ✅ แก้ไข
if (isset($_POST['update'])) {
    $stmt = $conn->prepare("UPDATE grooming_packages SET name_th=?, description_th=?, weight_min=?, weight_max=?, price=?, is_active=? WHERE id=?");
    $stmt->execute([
        $_POST['name_th'],
        $_POST['description_th'],
        $_POST['weight_min'] ?: 0,
        $_POST['weight_max'] ?: 0,
        $_POST['price'],
        isset($_POST['is_active']) ? 1 : 0,
        $_POST['id']
    ]);
    header('Location: grooming_packages.php?success=1');
    exit;
}

// ✅ ลบ
if (isset($_POST['delete'])) {
    $stmt = $conn->prepare("DELETE FROM grooming_packages WHERE id=?");
    $stmt->execute([$_POST['id']]);
    header('Location: grooming_packages.php?success=1');
    exit;
}

// ✅ ดึงข้อมูลทั้งหมด
$packages = $conn->query("SELECT * FROM grooming_packages ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการแพ็กเกจอาบน้ำ/ตัดขน | Elivet Admin</title>
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

        .btn-edit {
            background-color: #ffc107;
            color: white;
            border: none;
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
            border: none;
        }

        .btn-edit:hover,
        .btn-delete:hover {
            opacity: 0.85;
        }
    </style>
</head>

<body>
    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-success">จัดการแพ็กเกจอาบน้ำ / ตัดขนสัตว์เลี้ยง</h3>
            <div class="search-box">
                <input type="text" id="searchInput" class="form-control" placeholder="ค้นหาชื่อแพ็กเกจ...">
            </div>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <script>
                Swal.fire({ icon: 'success', title: 'บันทึกสำเร็จ', timer: 1500, showConfirmButton: false });
            </script>
        <?php endif; ?>

        <!-- ฟอร์มเพิ่มแพ็กเกจ -->
        <div class="card p-4 mb-4">
            <h5 class="mb-3 text-success"><i class="bi bi-plus-circle"></i> เพิ่มแพ็กเกจใหม่</h5>
            <form method="post" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">ชื่อแพ็กเกจ</label>
                    <input type="text" name="name_th" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">ช่วงน้ำหนัก (กก.)</label>
                    <div class="d-flex gap-2">
                        <input type="number" step="0.1" name="weight_min" class="form-control" placeholder="min">
                        <input type="number" step="0.1" name="weight_max" class="form-control" placeholder="max">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">ราคา (บาท)</label>
                    <input type="number" name="price" class="form-control" required>
                </div>
                <div class="col-md-3 d-flex align-items-end gap-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" id="active" checked>
                        <label class="form-check-label" for="active">แสดงผล</label>
                    </div>
                    <button class="btn btn-success" name="create"><i class="bi bi-save"></i> เพิ่ม</button>
                </div>
                <div class="col-12">
                    <label class="form-label">รายละเอียด</label>
                    <textarea name="description_th" class="form-control" rows="2"
                        placeholder="เช่น อาบน้ำ + ตัดขน + กลิ่นหอมพิเศษ"></textarea>
                </div>
            </form>
        </div>

        <!-- ตารางแพ็กเกจ -->
        <div class="card p-4">
            <h5 class="mb-3 text-success"><i class="bi bi-list-ul"></i> รายการแพ็กเกจทั้งหมด</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle" id="packageTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>ชื่อแพ็กเกจ</th>
                            <th>น้ำหนัก (กก.)</th>
                            <th>ราคา</th>
                            <th>สถานะ</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($packages as $p): ?>
                            <tr>
                                <td><?= $p['id'] ?></td>
                                <td><?= safe($p['name_th']) ?></td>
                                <td><?= safe($p['weight_min']) ?> -
                                    <?= $p['weight_max'] > 0 ? safe($p['weight_max']) : 'ไม่จำกัด' ?></td>
                                <td>฿<?= number_format($p['price'], 2) ?></td>
                                <td><?= $p['is_active'] ? '<span class="badge bg-success">แสดงผล</span>' : '<span class="badge bg-secondary">ซ่อน</span>' ?>
                                </td>
                                <td>
                                    <button class="btn btn-edit btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#edit<?= $p['id'] ?>">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form method="post" class="d-inline" onsubmit="return confirm('ลบแพ็กเกจนี้?')">
                                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                        <button class="btn btn-delete btn-sm" name="delete"><i
                                                class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal แก้ไข -->
                            <div class="modal fade" id="edit<?= $p['id'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form method="post">
                                            <div class="modal-header bg-warning">
                                                <h5 class="modal-title">แก้ไขแพ็กเกจ</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                                <div class="mb-3">
                                                    <label class="form-label">ชื่อแพ็กเกจ</label>
                                                    <input type="text" name="name_th" value="<?= safe($p['name_th']) ?>"
                                                        class="form-control" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">ราคา (บาท)</label>
                                                    <input type="number" name="price" value="<?= safe($p['price']) ?>"
                                                        class="form-control" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">ช่วงน้ำหนัก (กก.)</label>
                                                    <div class="d-flex gap-2">
                                                        <input type="number" step="0.1" name="weight_min"
                                                            value="<?= safe($p['weight_min']) ?>" class="form-control">
                                                        <input type="number" step="0.1" name="weight_max"
                                                            value="<?= safe($p['weight_max']) ?>" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">รายละเอียด</label>
                                                    <textarea name="description_th"
                                                        class="form-control"><?= safe($p['description_th']) ?></textarea>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="is_active"
                                                        <?= $p['is_active'] ? 'checked' : '' ?>>
                                                    <label class="form-check-label">แสดงผล</label>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-success" name="update">บันทึก</button>
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">ปิด</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ✅ ค้นหา
        document.getElementById('searchInput').addEventListener('keyup', function () {
            const value = this.value.toLowerCase();
            document.querySelectorAll('#packageTable tbody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(value) ? '' : 'none';
            });
        });
    </script>
</body>

</html>