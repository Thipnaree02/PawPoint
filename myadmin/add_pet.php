<?php
session_start();

// ถ้ายังไม่มี session แสดงว่ายังไม่ล็อกอิน
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

include '../myadmin/config/db.php';

// ดึงรายชื่อลูกค้าทั้งหมด
$users = $conn->query("SELECT user_id, username FROM users ORDER BY username ASC")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $pet_name = $_POST['pet_name'];
    $species = $_POST['species'];
    $breed = $_POST['breed'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $note = $_POST['note'];

    $stmt = $conn->prepare("INSERT INTO pets (user_id, pet_name, species, breed, gender, age, note)
                            VALUES (:user_id, :pet_name, :species, :breed, :gender, :age, :note)");
    $stmt->execute([
        'user_id' => $user_id,
        'pet_name' => $pet_name,
        'species' => $species,
        'breed' => $breed,
        'gender' => $gender,
        'age' => $age,
        'note' => $note
    ]);

    header("Location: add_pet.php?success=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>เพิ่มสัตว์เลี้ยงให้ลูกค้า - PawPoint Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Prompt', sans-serif;
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
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        label {
            font-weight: 500;
            margin-bottom: 5px;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
        }

        .btn-submit {
            background: linear-gradient(135deg, #198754, #28a745);
            border: none;
            color: #fff;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 600;
            transition: 0.3s;
            box-shadow: 0 5px 12px rgba(25, 135, 84, 0.3);
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #157347, #1c7430);
            transform: translateY(-2px);
        }

        .btn-back {
            background: #dee2e6;
            color: #333;
            padding: 10px 25px;
            border-radius: 8px;
            transition: 0.3s;
            font-weight: 500;
        }

        .btn-back:hover {
            background: #adb5bd;
            color: #000;
        }
    </style>
</head>

<body class="container py-4">

    <!-- Header -->
    <div class="page-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0"><i class="bi bi-paw"></i> เพิ่มสัตว์เลี้ยงให้ลูกค้า</h4>
        <a href="pets.php" class="btn btn-light"><i class="bi bi-arrow-left"></i> กลับไปหน้ารายการ</a>
    </div>

    <!-- Form Card -->
    <div class="card p-4">
        <form method="POST">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label"><i class="bi bi-person-circle"></i> เจ้าของสัตว์เลี้ยง</label>
                    <select name="user_id" class="form-select" required>
                        <option value="">-- เลือกลูกค้า --</option>
                        <?php foreach ($users as $u): ?>
                            <option value="<?= $u['user_id'] ?>"><?= htmlspecialchars($u['username']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label"><i class="bi bi-paw"></i> ชื่อสัตว์เลี้ยง</label>
                    <input type="text" name="pet_name" class="form-control" placeholder="เช่น มาลี, โบโบ้" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label"><i class="bi bi-tag"></i> ประเภทสัตว์</label>
                    <select name="species" class="form-select">
                        <option>สุนัข</option>
                        <option>แมว</option>
                        <option>อื่นๆ</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label"><i class="bi bi-flower1"></i> สายพันธุ์</label>
                    <input type="text" name="breed" class="form-control" placeholder="เช่น โกลเด้น, เปอร์เซีย">
                </div>

                <div class="col-md-6">
                    <label class="form-label"><i class="bi bi-gender-ambiguous"></i> เพศ</label>
                    <select name="gender" class="form-select">
                        <option>ตัวผู้</option>
                        <option>ตัวเมีย</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label"><i class="bi bi-cake2"></i> อายุ (ปี)</label>
                    <input type="number" name="age" class="form-control" min="0" placeholder="เช่น 2">
                </div>

                <div class="col-12">
                    <label class="form-label"><i class="bi bi-chat-text"></i> หมายเหตุ</label>
                    <textarea name="note" class="form-control" placeholder="เช่น แพ้อาหาร, ชอบเห่า, กลัวหมอ"></textarea>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="pets.php" class="btn btn-back"><i class="bi bi-arrow-left-circle"></i> ย้อนกลับ</a>
                    <button type="submit" class="btn btn-submit"><i class="bi bi-check-circle"></i>
                        บันทึกข้อมูล</button>
                </div>
            </div>
        </form>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'เพิ่มสัตว์เลี้ยงสำเร็จ!',
                text: 'ข้อมูลถูกบันทึกเรียบร้อยแล้ว 🐾',
                showConfirmButton: false,
                timer: 1800
            });
        </script>
    <?php endif; ?>

</body>

</html>