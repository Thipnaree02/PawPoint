<?php
session_start();

// ถ้ายังไม่มี session แสดงว่ายังไม่ล็อกอิน
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

include 'config/db.php';

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// ✅ ตรวจสอบว่ามี id ที่ต้องการแก้ไขไหม
if (!isset($_GET['id'])) {
    header("Location: pets.php");
    exit;
}

$pet_id = $_GET['id'];

// ✅ ดึงข้อมูลสัตว์เลี้ยงจากฐานข้อมูล
$stmt = $conn->prepare("SELECT * FROM pets WHERE pet_id = :pet_id");
$stmt->execute(['pet_id' => $pet_id]);
$pet = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pet) {
    header("Location: pets.php?error=notfound");
    exit;
}

// ✅ ดึงรายชื่อลูกค้า (เพื่อเลือกเจ้าของ)
$users = $conn->query("SELECT user_id, username FROM users ORDER BY username ASC")->fetchAll(PDO::FETCH_ASSOC);

// ✅ เมื่อกดบันทึก
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $pet_name = $_POST['pet_name'];
    $species = $_POST['species'];
    $breed = $_POST['breed'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $note = $_POST['note'];

    $update = $conn->prepare("UPDATE pets 
                              SET user_id = :user_id, pet_name = :pet_name, species = :species, breed = :breed, gender = :gender, age = :age, note = :note
                              WHERE pet_id = :pet_id");
    $update->execute([
        'user_id' => $user_id,
        'pet_name' => $pet_name,
        'species' => $species,
        'breed' => $breed,
        'gender' => $gender,
        'age' => $age,
        'note' => $note,
        'pet_id' => $pet_id
    ]);

    header("Location: pets.php?updated=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>แก้ไขข้อมูลสัตว์เลี้ยง - PawPoint Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Prompt', sans-serif;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px 40px;
        }

        .page-header {
            background: linear-gradient(135deg, #198754, #28a745);
            color: #fff;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        }

        .btn-save {
            background: linear-gradient(135deg, #198754, #28a745);
            border: none;
            color: #fff;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 600;
            box-shadow: 0 4px 10px rgba(25, 135, 84, 0.3);
        }

        .btn-back {
            background: #dee2e6;
            color: #333;
            border-radius: 8px;
            padding: 10px 25px;
            font-weight: 500;
        }

        .btn-save:hover {
            background: linear-gradient(135deg, #157347, #1c7430);
            transform: translateY(-2px);
        }
    </style>
</head>

<body>
    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <div class="page-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="bi bi-pencil-square"></i> แก้ไขข้อมูลสัตว์เลี้ยง</h4>
            <a href="pets.php" class="btn btn-light"><i class="bi bi-arrow-left"></i> กลับไปหน้ารายการ</a>
        </div>

        <div class="card p-4">
            <form method="POST">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label"><i class="bi bi-person"></i> เจ้าของสัตว์เลี้ยง</label>
                        <select name="user_id" class="form-select" required>
                            <?php foreach ($users as $u): ?>
                                <option value="<?= $u['user_id'] ?>" <?= $u['user_id'] == $pet['user_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($u['username']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label"><i class="bi bi-paw"></i> ชื่อสัตว์เลี้ยง</label>
                        <input type="text" name="pet_name" value="<?= htmlspecialchars($pet['pet_name']) ?>"
                            class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label"><i class="bi bi-tag"></i> ประเภทสัตว์</label>
                        <select name="species" class="form-select">
                            <option <?= $pet['species'] == 'สุนัข' ? 'selected' : '' ?>>สุนัข</option>
                            <option <?= $pet['species'] == 'แมว' ? 'selected' : '' ?>>แมว</option>
                            <option <?= $pet['species'] == 'อื่นๆ' ? 'selected' : '' ?>>อื่นๆ</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label"><i class="bi bi-flower1"></i> สายพันธุ์</label>
                        <input type="text" name="breed" value="<?= htmlspecialchars($pet['breed']) ?>"
                            class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label"><i class="bi bi-gender-ambiguous"></i> เพศ</label>
                        <select name="gender" class="form-select">
                            <option <?= $pet['gender'] == 'ตัวผู้' ? 'selected' : '' ?>>ตัวผู้</option>
                            <option <?= $pet['gender'] == 'ตัวเมีย' ? 'selected' : '' ?>>ตัวเมีย</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label"><i class="bi bi-cake2"></i> อายุ (ปี)</label>
                        <input type="number" name="age" class="form-control" min="0"
                            value="<?= htmlspecialchars($pet['age']) ?>">
                    </div>

                    <div class="col-12">
                        <label class="form-label"><i class="bi bi-chat-text"></i> หมายเหตุ</label>
                        <textarea name="note" class="form-control"><?= htmlspecialchars($pet['note']) ?></textarea>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="pets.php" class="btn btn-back"><i class="bi bi-arrow-left-circle"></i> ย้อนกลับ</a>
                        <button type="submit" class="btn btn-save"><i class="bi bi-check-circle"></i>
                            บันทึกการแก้ไข</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php if (isset($_GET['updated'])): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'อัปเดตข้อมูลสำเร็จ!',
                showConfirmButton: false,
                timer: 1500
            });
        </script>
    <?php endif; ?>
</body>

</html>