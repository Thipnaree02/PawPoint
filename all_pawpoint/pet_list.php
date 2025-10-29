<?php
include '../myadmin/config/db.php';
session_start();

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// ดึงข้อมูลสัตว์เลี้ยงของผู้ใช้
$stmt = $conn->prepare("SELECT * FROM pets WHERE user_id = :user_id ORDER BY pet_id DESC");
$stmt->execute(['user_id' => $user_id]);
$pets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ✅ ลบข้อมูลสัตว์เลี้ยง
if (isset($_GET['delete'])) {
    $pet_id = $_GET['delete'];
    $del = $conn->prepare("DELETE FROM pets WHERE pet_id = :pet_id AND user_id = :user_id");
    $del->execute(['pet_id' => $pet_id, 'user_id' => $user_id]);
    header("Location: pet_list.php?deleted=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>ประวัติสัตว์เลี้ยงของคุณ - PawPoint</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background-color: #f8f9fa;
        }

        .pet-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 20px;
            transition: transform 0.2s;
        }

        .pet-card:hover {
            transform: translateY(-5px);
        }

        .btn-add {
            background: linear-gradient(135deg, #198754, #157347);
            color: white;
            font-weight: 600;
            border-radius: 8px;
            padding: 10px 22px;
            box-shadow: 0 4px 10px rgba(33, 136, 56, 0.3);
            transition: all 0.3s;
        }

        .btn-add:hover {
            background: linear-gradient(135deg, #157347, #125d3c);
            box-shadow: 0 6px 15px rgba(33, 136, 56, 0.4);
        }

        .btn-home {
            background: linear-gradient(135deg, #6c757d, #495057);
            color: white;
            font-weight: 600;
            border-radius: 8px;
            padding: 10px 22px;
            transition: all 0.3s ease;
        }

        .btn-home:hover {
            background: linear-gradient(135deg, #495057, #343a40);
            transform: translateY(-2px);
        }
    </style>
</head>

<body class="container py-4">

    <h3 class="mb-4 text-success text-center">🐾 ประวัติสัตว์เลี้ยงของคุณ</h3>

    <div class="text-center mb-4">
        <a href="index.php" class="btn btn-home">
            <i class="bi bi-house-door"></i> กลับไปหน้าหลัก
        </a>
    </div>


    <?php if (count($pets) === 0): ?>
        <div class="text-center p-5 bg-light rounded shadow-sm">
            <p class="text-muted">คุณยังไม่มีสัตว์เลี้ยงในระบบ</p>
            <a href="add_pet.php" class="btn btn-add">
                <i class="bi bi-plus-circle"></i> เพิ่มสัตว์เลี้ยงของคุณ
            </a>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($pets as $pet): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="pet-card">
                        <h5 class="text-success"><?= htmlspecialchars($pet['pet_name']) ?></h5>
                        <p class="mb-1"><strong>ประเภท:</strong> <?= htmlspecialchars($pet['species']) ?></p>
                        <p class="mb-1"><strong>สายพันธุ์:</strong> <?= htmlspecialchars($pet['breed']) ?></p>
                        <p class="mb-1"><strong>เพศ:</strong> <?= htmlspecialchars($pet['gender']) ?></p>
                        <p class="mb-1"><strong>อายุ:</strong> <?= htmlspecialchars($pet['age']) ?> ปี</p>
                        <?php if (!empty($pet['note'])): ?>
                            <p class="text-muted"><strong>หมายเหตุ:</strong> <?= htmlspecialchars($pet['note']) ?></p>
                        <?php endif; ?>
                        <div class="d-flex justify-content-between mt-3">
                            <a href="edit_pet.php?id=<?= $pet['pet_id'] ?>" class="btn btn-outline-success btn-sm">
                                <i class="bi bi-pencil-square"></i> แก้ไข
                            </a>
                            <button class="btn btn-outline-danger btn-sm" onclick="confirmDelete(<?= $pet['pet_id'] ?>)">
                                <i class="bi bi-trash"></i> ลบ
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-5">
            <a href="add_pet.php" class="btn btn-add">
                <i class="bi bi-plus-circle"></i> เพิ่มสัตว์เลี้ยงของคุณ
            </a>
        </div>
    <?php endif; ?>

    <script>
        // ✅ ลบด้วย SweetAlert2
        function confirmDelete(id) {
            Swal.fire({
                title: 'ยืนยันการลบ?',
                text: "คุณต้องการลบข้อมูลสัตว์เลี้ยงนี้หรือไม่",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'ลบเลย',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'pet_list.php?delete=' + id;
                }
            });
        }

        // ✅ แสดง SweetAlert เมื่อเพิ่มหรือลบสำเร็จ
        <?php if (isset($_GET['success'])): ?>
            Swal.fire({
                icon: 'success',
                title: 'เพิ่มสัตว์เลี้ยงสำเร็จ!',
                showConfirmButton: false,
                timer: 1800
            });
        <?php elseif (isset($_GET['deleted'])): ?>
            Swal.fire({
                icon: 'success',
                title: 'ลบข้อมูลสำเร็จ!',
                showConfirmButton: false,
                timer: 1800
            });
        <?php endif; ?>
    </script>

</body>

</html>