<?php
include '../myadmin/config/db.php';
session_start();

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// ตรวจสอบว่าได้รับ id มาหรือไม่
if (!isset($_GET['id'])) {
    header("Location: pet_list.php");
    exit;
}

$pet_id = $_GET['id'];

// ดึงข้อมูลสัตว์เลี้ยงจากฐานข้อมูล
$stmt = $conn->prepare("SELECT * FROM pets WHERE pet_id = :pet_id AND user_id = :user_id");
$stmt->execute(['pet_id' => $pet_id, 'user_id' => $user_id]);
$pet = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pet) {
    header("Location: pet_list.php?error=notfound");
    exit;
}

// ✅ เมื่อมีการบันทึก (กด submit)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pet_name = $_POST['pet_name'];
    $species = $_POST['species'] === "อื่นๆ" ? $_POST['species_other'] : $_POST['species'];
    $breed = $_POST['breed'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $note = $_POST['note'];

    $update = $conn->prepare("UPDATE pets 
    SET pet_name=:pet_name, species=:species, breed=:breed, gender=:gender, age=:age, note=:note 
    WHERE pet_id=:pet_id AND user_id=:user_id");

    $update->execute([
        'pet_name' => $pet_name,
        'species' => $species,
        'breed' => $breed,
        'gender' => $gender,
        'age' => $age,
        'note' => $note,
        'pet_id' => $pet_id,
        'user_id' => $user_id
    ]);

    header("Location: pet_list.php?updated=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>แก้ไขข้อมูลสัตว์เลี้ยง - PawPoint</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .pet-form {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .btn-save {
            background: linear-gradient(135deg, #28a745, #218838);
            color: #fff;
            font-weight: 600;
            border: none;
            border-radius: 10px;
            padding: 10px 28px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(33, 136, 56, 0.3);
        }

        .btn-save:hover {
            background: linear-gradient(135deg, #218838, #1e7e34);
            box-shadow: 0 6px 15px rgba(33, 136, 56, 0.4);
            transform: translateY(-2px);
        }

        .btn-back {
            background: linear-gradient(135deg, #adb5bd, #6c757d);
            color: white;
            font-weight: 500;
            border: none;
            border-radius: 10px;
            padding: 10px 22px;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: linear-gradient(135deg, #6c757d, #495057);
            transform: translateY(-2px);
        }

        .btn-container {
            display: flex;
            justify-content: space-between;
            margin-top: 25px;
        }
    </style>
</head>

<body>
    <div class="pet-form">
        <h3 class="mb-4 text-success text-center">🐾 แก้ไขข้อมูลสัตว์เลี้ยง</h3>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">ชื่อสัตว์เลี้ยง</label>
                <input type="text" name="pet_name" class="form-control"
                    value="<?= htmlspecialchars($pet['pet_name']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">ประเภทสัตว์</label>
                <select name="species" id="species" class="form-select">
                    <option value="สุนัข" <?= $pet['species'] == "สุนัข" ? "selected" : "" ?>>สุนัข</option>
                    <option value="แมว" <?= $pet['species'] == "แมว" ? "selected" : "" ?>>แมว</option>
                    <option value="อื่นๆ" <?= !in_array($pet['species'], ["สุนัข", "แมว"]) ? "selected" : "" ?>>อื่นๆ</option>
                </select>
                <input type="text" name="species_other" id="species_other"
                    class="form-control mt-2 <?= !in_array($pet['species'], ["สุนัข", "แมว"]) ? "" : "d-none" ?>"
                    value="<?= !in_array($pet['species'], ["สุนัข", "แมว"]) ? htmlspecialchars($pet['species']) : "" ?>"
                    placeholder="ระบุประเภทสัตว์อื่นๆ">
            </div>

            <div class="mb-3">
                <label class="form-label">สายพันธุ์</label>
                <input type="text" name="breed" class="form-control" value="<?= htmlspecialchars($pet['breed']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">เพศ</label>
                <select name="gender" class="form-select">
                    <option value="ตัวผู้" <?= $pet['gender'] == "ตัวผู้" ? "selected" : "" ?>>ตัวผู้</option>
                    <option value="ตัวเมีย" <?= $pet['gender'] == "ตัวเมีย" ? "selected" : "" ?>>ตัวเมีย</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">อายุ (ปี)</label>
                <input type="number" name="age" class="form-control" value="<?= htmlspecialchars($pet['age']) ?>">
            </div>

            <div class="mb-3">
                <textarea name="note" class="form-control" rows="3"
                    placeholder="หมายเหตุ"><?= htmlspecialchars($pet['note']) ?></textarea>
            </div>

            <div class="btn-container">
                <a href="pet_list.php" class="btn btn-back"><i class="bi bi-arrow-left-circle"></i> ย้อนกลับ</a>
                <button type="submit" class="btn btn-save"><i class="bi bi-save"></i> บันทึกการแก้ไข</button>
            </div>
        </form>
    </div>

    <script>
        // ✅ toggle “อื่นๆ” ช่องพิมพ์เพิ่ม
        document.getElementById('species').addEventListener('change', function () {
            const otherInput = document.getElementById('species_other');
            if (this.value === 'อื่นๆ') {
                otherInput.classList.remove('d-none');
                otherInput.required = true;
            } else {
                otherInput.classList.add('d-none');
                otherInput.required = false;
                otherInput.value = '';
            }
        });

        // ✅ SweetAlert เมื่อบันทึกสำเร็จ
        <?php if (isset($_GET['updated'])): ?>
            Swal.fire({
                icon: 'success',
                title: 'บันทึกสำเร็จ!',
                text: 'ข้อมูลสัตว์เลี้ยงถูกอัปเดตเรียบร้อย 🎉',
                confirmButtonColor: '#198754'
            }).then(() => {
                window.location.href = 'pet_list.php';
            });
        <?php endif; ?>
    </script>

</body>

</html>