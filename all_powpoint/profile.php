<?php
session_start();
include '../myadmin/config/db.php';

// ✅ ตรวจสอบว่าล็อกอินแล้วหรือยัง
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

// ✅ ดึงข้อมูลผู้ใช้จากฐานข้อมูล
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmtProfile = $conn->prepare($sql);
$stmtProfile->bindParam(1, $user_id, PDO::PARAM_INT);
$stmtProfile->execute();
$user = $stmtProfile->fetch(PDO::FETCH_ASSOC);

$updateSuccess = false;

// ✅ เมื่อผู้ใช้กดบันทึกข้อมูล
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $address = trim($_POST["address"]);

    // ✅ ตรวจสอบค่าว่าง
    if (empty($username) || empty($email)) {
        echo "<script>
            alert('กรุณากรอกชื่อผู้ใช้และอีเมลให้ครบ');
            window.history.back();
        </script>";
        exit();
    }

    // ✅ อัปเดตข้อมูลในฐานข้อมูล
    $update = "UPDATE users SET username = ?, email = ?, phone = ?, address = ? WHERE user_id = ?";
    $stmt = $conn->prepare($update);
    $stmt->execute([$username, $email, $phone, $address, $user_id]);

    // ✅ อัปเดต session
    $_SESSION['username'] = $username;
    $_SESSION['email'] = $email;
    session_write_close();

    $updateSuccess = true;
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>โปรไฟล์ของฉัน | PawPoint</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background: #f3f7fb;
            padding-top: 90px;
        }

        .profile-container {
            max-width: 700px;
            margin: 60px auto;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            padding: 40px;
        }

        h3 {
            color: #4aa9d9;
            text-align: center;
            margin-bottom: 25px;
            font-weight: 700;
        }

        .btn-save {
            background-color: #4aa9d9;
            border: none;
            color: white;
        }

        .btn-save:hover {
            background-color: #2980b9;
        }

        label {
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="profile-container">
        <h3>โปรไฟล์ของฉัน</h3>

        <form method="POST">
            <div class="mb-3">
                <label>ชื่อผู้ใช้</label>
                <input type="text" name="username" class="form-control"
                    value="<?= htmlspecialchars($user['username']); ?>" required>
            </div>

            <div class="mb-3">
                <label>อีเมล</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']); ?>"
                    required>
            </div>

            <div class="mb-3">
                <label>เบอร์โทรศัพท์</label>
                <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone']); ?>">
            </div>

            <div class="mb-3">
                <label>ที่อยู่</label>
                <textarea name="address" class="form-control"
                    rows="3"><?= htmlspecialchars($user['address']); ?></textarea>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-save px-4">บันทึกการเปลี่ยนแปลง</button>
                <a href="index.php" class="btn btn-secondary px-4">ยกเลิก</a>
            </div>
        </form>
    </div>

    <?php if ($updateSuccess): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'อัปเดตข้อมูลสำเร็จ!',
                text: 'ระบบได้บันทึกข้อมูลของคุณเรียบร้อยแล้ว',
                showConfirmButton: false,
                timer: 1800,
                timerProgressBar: true
            }).then(() => {
                window.location.href = 'index.php';
            });
        </script>
    <?php endif; ?>
</body>

</html>