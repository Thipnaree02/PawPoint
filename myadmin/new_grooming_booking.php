<?php

session_start();

// ถ้ายังไม่มี session แสดงว่ายังไม่ล็อกอิน
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'config/db.php';
session_start();

// ดึงข้อมูลลูกค้า
$stmtUser = $conn->query("SELECT user_id, username, email FROM users ORDER BY username ASC");
$users = $stmtUser->fetchAll(PDO::FETCH_ASSOC);

// ดึงข้อมูลแพ็กเกจอาบน้ำ–ตัดขน
$stmtPkg = $conn->query("SELECT id, name_th, price FROM grooming_packages ORDER BY name_th ASC");
$packages = $stmtPkg->fetchAll(PDO::FETCH_ASSOC);

// เมื่อเลือก user แล้วจะดึงสัตว์เลี้ยงของคนนั้น
$pets = [];
if (isset($_GET['user_id']) && $_GET['user_id'] != '') {
    $stmtPet = $conn->prepare("SELECT id, pet_name FROM pets WHERE user_id=?");
    $stmtPet->execute([$_GET['user_id']]);
    $pets = $stmtPet->fetchAll(PDO::FETCH_ASSOC);
}

// ✅ เมื่อกดบันทึก
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_booking'])) {
    $user_id = (int)$_POST['user_id'];
    $pet_id = (int)$_POST['pet_id'];
    $package_id = (int)$_POST['package_id'];
    $booking_date = $_POST['booking_date'] ?? '';
    $booking_time = $_POST['booking_time'] ?? '';
    $note = trim($_POST['note'] ?? '');

    if ($user_id && $pet_id && $package_id && $booking_date && $booking_time) {
        $stmt = $conn->prepare("
            INSERT INTO grooming_bookings (user_id, pet_id, package_id, booking_date, booking_time, note, status)
            VALUES (?, ?, ?, ?, ?, ?, 'pending')
        ");
        $stmt->execute([$user_id, $pet_id, $package_id, $booking_date, $booking_time, $note]);

        header("Location: new_grooming_booking.php?success=1");
        exit;
    } else {
        header("Location: new_grooming_booking.php?error=1");
        exit;
    }
}
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <title>เพิ่มนัดหมายอาบน้ำ / ตัดขนสัตว์เลี้ยง</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-light">
    <div class="container py-5 d-flex justify-content-center">
        <div class="card shadow-lg p-4" style="width: 600px;">
            <h4 class="text-center text-success mb-4">
                <i class="bi bi-scissors"></i> เพิ่มนัดหมายอาบน้ำ / ตัดขน
            </h4>

            <form method="post">
                <div class="mb-3">
                    <label class="form-label fw-semibold">เลือกลูกค้า</label>
                    <select name="user_id" class="form-select" required onchange="this.form.submit()">
                        <option value="">-- กรุณาเลือกลูกค้า --</option>
                        <?php foreach ($users as $u): ?>
                            <option value="<?= $u['user_id'] ?>" <?= ($_GET['user_id'] ?? '') == $u['user_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($u['username']) ?> (<?= htmlspecialchars($u['email']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">เลือกสัตว์เลี้ยง</label>
                    <select name="pet_id" class="form-select" required>
                        <option value="">-- กรุณาเลือกสัตว์เลี้ยง --</option>
                        <?php foreach ($pets as $p): ?>
                            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['pet_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">เลือกแพ็กเกจ</label>
                    <select name="package_id" class="form-select" required>
                        <option value="">-- กรุณาเลือกแพ็กเกจ --</option>
                        <?php foreach ($packages as $pkg): ?>
                            <option value="<?= $pkg['id'] ?>">
                                <?= htmlspecialchars($pkg['name_th']) ?> (<?= number_format($pkg['price'], 0) ?> บาท)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">วันที่อาบน้ำ / ตัดขน</label>
                    <input type="date" name="booking_date" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">เวลา</label>
                    <input type="time" name="booking_time" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">หมายเหตุเพิ่มเติม (ถ้ามี)</label>
                    <textarea name="note" class="form-control" rows="2" placeholder="เช่น สุนัขกลัวน้ำ ฯลฯ"></textarea>
                </div>

                <button type="submit" name="add_booking" class="btn btn-success w-100">
                    <i class="bi bi-check-circle"></i> ยืนยันการจอง
                </button>
                <a href="grooming_bookings.php" class="btn btn-outline-secondary w-100 mt-2">
                    <i class="bi bi-arrow-left"></i> กลับไปตารางนัดหมาย
                </a>
            </form>
        </div>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'เพิ่มการนัดหมายสำเร็จ!',
                text: 'ระบบได้บันทึกข้อมูลเรียบร้อยแล้ว',
                timer: 1800,
                showConfirmButton: false
            });
        </script>
    <?php elseif (isset($_GET['error'])): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด!',
                text: 'กรุณากรอกข้อมูลให้ครบถ้วน',
                timer: 2000,
                showConfirmButton: false
            });
        </script>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
