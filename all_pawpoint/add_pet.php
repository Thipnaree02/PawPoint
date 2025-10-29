<?php
include '../myadmin/config/db.php';
session_start();

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $pet_name = $_POST['pet_name'];
    $species = $_POST['species'] === "อื่นๆ" ? $_POST['species_other'] : $_POST['species'];
    $breed = $_POST['breed'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $note = $_POST['note'];

    $sql = "INSERT INTO pets (user_id, pet_name, species, breed, gender, age, note)
            VALUES (:user_id, :pet_name, :species, :breed, :gender, :age, :note)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        'user_id' => $user_id,
        'pet_name' => $pet_name,
        'species' => $species,
        'breed' => $breed,
        'gender' => $gender,
        'age' => $age,
        'note' => $note
    ]);

    // ส่งค่า success กลับไปหน้าเดิมเพื่อให้ JS แสดง SweetAlert
    header("Location: add_pet.php?success=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>เพิ่มสัตว์เลี้ยงของคุณ - PawPoint</title>
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

        .btn-back {
            background-color: #adb5bd;
            border: none;
        }

        .btn-back:hover {
            background-color: #6c757d;
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

        .btn-save:active {
            transform: translateY(1px);
            box-shadow: 0 2px 6px rgba(33, 136, 56, 0.2);
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
            align-items: center;
            margin-top: 25px;
        }
    </style>
</head>

<body>

    <div class="pet-form">
        <h3 class="mb-4 text-success text-center">🐾 เพิ่มสัตว์เลี้ยงของคุณ</h3>

        <form method="POST" id="petForm">
            <div class="mb-3">
                <label class="form-label">ชื่อสัตว์เลี้ยง</label>
                <input type="text" name="pet_name" class="form-control" placeholder="เช่น มะลิ, เหมียว" required>
            </div>

            <div class="mb-3">
                <label class="form-label">ประเภทสัตว์</label>
                <select name="species" id="species" class="form-select" required>
                    <option value="สุนัข">สุนัข</option>
                    <option value="แมว">แมว</option>
                    <option value="อื่นๆ">อื่นๆ</option>
                </select>
                <input type="text" name="species_other" id="species_other" class="form-control mt-2 d-none"
                    placeholder="ระบุประเภทสัตว์อื่นๆ">
            </div>

            <div class="mb-3">
                <label class="form-label">สายพันธุ์</label>
                <input type="text" name="breed" class="form-control" placeholder="เช่น ปอมเมอเรเนียน">
            </div>

            <div class="mb-3">
                <label class="form-label">เพศ</label>
                <select name="gender" class="form-select" required>
                    <option value="ตัวผู้">ตัวผู้</option>
                    <option value="ตัวเมีย">ตัวเมีย</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">อายุ (ปี)</label>
                <input type="number" name="age" class="form-control" min="0" placeholder="เช่น 2">
            </div>

            <div class="mb-3">
                <textarea name="note" class="form-control" rows="3"
                    placeholder="หมายเหตุ (เช่น แพ้อาหาร, ขี้กลัว)"></textarea>
            </div>

            <div class="btn-container">
                <a href="index.php" class="btn btn-back">
                    <i class="bi bi-arrow-left-circle"></i> ย้อนกลับ
                </a>
                <button type="submit" class="btn btn-save">
                    <i class="bi bi-check-circle"></i> บันทึกข้อมูล
                </button>
            </div>

        </form>
    </div>

    <script>
        // ✅ ถ้าเลือก "อื่นๆ" ให้แสดงช่องพิมพ์เพิ่มเติม
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
        <?php if (isset($_GET['success'])): ?>
            Swal.fire({
                icon: 'success',
                title: 'บันทึกสำเร็จ!',
                text: 'เพิ่มข้อมูลสัตว์เลี้ยงเรียบร้อยแล้ว 🎉',
                confirmButtonColor: '#198754'
            }).then(() => {
                window.location.href = 'pet_list.php';
            });
        <?php endif; ?>
    </script>

</body>

</html>