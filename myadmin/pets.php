<?php
session_start();
include '../myadmin/config/db.php';

// 🔒 ตรวจสอบการล็อกอิน
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// 🧩 ลบข้อมูลสัตว์เลี้ยง (ก่อน query หลัก)
if (isset($_GET['delete'])) {
    $pet_id = $_GET['delete'];
    $del = $conn->prepare("DELETE FROM pets WHERE pet_id = :pet_id");
    $del->execute(['pet_id' => $pet_id]);
    header("Location: pets.php?deleted=1");
    exit;
}

// 🔍 ตัวแปรสำหรับค้นหา (กัน Error undefined)
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// 🐾 ดึงข้อมูลสัตว์เลี้ยงทั้งหมด (กรองด้วย search ถ้ามี)
if ($search !== '') {
    $stmt = $conn->prepare("
        SELECT p.pet_id, p.pet_name, p.species, p.breed, p.gender, p.age, p.note, u.username 
        FROM pets p
        JOIN users u ON p.user_id = u.user_id
        WHERE p.pet_name LIKE :search OR u.username LIKE :search
        ORDER BY p.pet_id DESC
    ");
    $stmt->execute(['search' => "%$search%"]);
} else {
    $stmt = $conn->prepare("
        SELECT p.pet_id, p.pet_name, p.species, p.breed, p.gender, p.age, p.note, u.username 
        FROM pets p
        JOIN users u ON p.user_id = u.user_id
        ORDER BY p.pet_id DESC
    ");
    $stmt->execute();
}
$pets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>รายการสัตว์เลี้ยง - PawPoint Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --sidebar-width: 280px;
        }

        body {
            background-color: #f4f6f9;
            font-family: 'Prompt', sans-serif;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            padding: 20px 40px;
            min-height: 100vh;
        }

        @media (max-width: 992px) {
            .main-content {
                margin-left: 0;
                padding: 16px;
            }
        }

        /* Header */
        .page-header {
            background: linear-gradient(135deg, #198754, #28a745);
            color: #fff;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Search + Button */
        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            margin-top: 15px;
            flex-wrap: wrap;
        }

        .input-group {
            width: 300px;
            max-width: 100%;
        }

        .btn-add {
            background: linear-gradient(135deg, #198754, #28a745);
            color: white;
            border-radius: 10px;
            font-weight: 600;
            padding: 8px 18px;
            transition: 0.3s;
            box-shadow: 0 4px 10px rgba(25, 135, 84, 0.3);
            white-space: nowrap;
        }

        .btn-add:hover {
            background: linear-gradient(135deg, #157347, #1c7430);
            transform: translateY(-2px);
        }

        /* Table */
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            overflow-x: auto;
        }

        .table th {
            background-color: #198754;
            color: white;
            text-align: center;
            vertical-align: middle;
        }

        .table td {
            text-align: center;
            vertical-align: middle;
            padding: 10px 12px;
        }

        .table-striped>tbody>tr:nth-of-type(odd)>* {
            background-color: #f8f9fa;
        }

        .btn-action {
            border-radius: 8px;
            font-weight: 500;
            padding: 6px 12px;
        }
    </style>
</head>

<body>
    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <!-- Header -->
        <div class="page-header d-flex flex-wrap justify-content-between align-items-center">
            <h4 class="mb-0">
                <i class="bi bi-clipboard-data"></i> รายการสัตว์เลี้ยงทั้งหมด
            </h4>

            <form method="GET" class="d-flex align-items-center gap-2 mt-3 mt-md-0">
                <div class="input-group" style="width: 280px;">
                    <span class="input-group-text bg-success text-white">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="search" class="form-control"
                        placeholder="ค้นหาชื่อสัตว์เลี้ยง / เจ้าของ..." value="<?= htmlspecialchars($search) ?>">
                </div>

                <a href="add_pet.php" class="btn btn-add">
                    <i class="bi bi-plus-circle"></i> เพิ่มสัตว์เลี้ยง
                </a>
            </form>
        </div>


        <!-- Table -->
        <div class="card p-3">
            <?php if (count($pets) == 0): ?>
                <div class="text-center text-muted py-5">
                    <i class="bi bi-emoji-frown fs-1"></i>
                    <p class="mt-2">ไม่พบข้อมูลสัตว์เลี้ยง</p>
                    <a href="add_pet.php" class="btn btn-add"><i class="bi bi-plus-circle"></i> เพิ่มข้อมูลใหม่</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>ลำดับ</th>
                                <th>ชื่อสัตว์เลี้ยง</th>
                                <th>ประเภท</th>
                                <th>สายพันธุ์</th>
                                <th>เพศ</th>
                                <th>อายุ</th>
                                <th>เจ้าของ</th>
                                <th>หมายเหตุ</th>
                                <th>การจัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1;
                            foreach ($pets as $pet): ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= htmlspecialchars($pet['pet_name']) ?></td>
                                    <td><?= htmlspecialchars($pet['species']) ?></td>
                                    <td><?= htmlspecialchars($pet['breed']) ?></td>
                                    <td><?= htmlspecialchars($pet['gender']) ?></td>
                                    <td><?= htmlspecialchars($pet['age']) ?> ปี</td>
                                    <td><?= htmlspecialchars($pet['username']) ?></td>
                                    <td><?= htmlspecialchars($pet['note']) ?></td>
                                    <td>
                                        <a href="edit_pet.php?id=<?= $pet['pet_id'] ?>"
                                            class="btn btn-outline-success btn-action btn-sm">
                                            <i class="bi bi-pencil-square"></i> 
                                        </a>
                                        <button class="btn btn-outline-danger btn-action btn-sm"
                                            onclick="confirmDelete(<?= $pet['pet_id'] ?>)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // SweetAlert ยืนยันการลบ
        function confirmDelete(id) {
            Swal.fire({
                title: 'ยืนยันการลบ?',
                text: 'คุณต้องการลบข้อมูลสัตว์เลี้ยงนี้หรือไม่',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'ลบเลย',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'pets.php?delete=' + id;
                }
            });
        }

        // แจ้งเตือนเมื่อสำเร็จ
        <?php if (isset($_GET['deleted'])): ?>
            Swal.fire({
                icon: 'success',
                title: 'ลบข้อมูลสำเร็จ!',
                showConfirmButton: false,
                timer: 1500
            });
        <?php elseif (isset($_GET['success'])): ?>
            Swal.fire({
                icon: 'success',
                title: 'เพิ่มข้อมูลสำเร็จ!',
                showConfirmButton: false,
                timer: 1500
            });
        <?php endif; ?>
    </script>
</body>

</html>