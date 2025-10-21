<?php
session_start();
include '../Admin/config/connextdb.php';

if (!$connextdb) {
    die("❌ Database connection failed (variable not set).");
}

// ตรวจสอบว่าล็อกอินแล้วหรือยัง
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

// ดึงข้อมูลผู้ใช้จากฐานข้อมูล (ใช้ PDO)
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmtProfile = $connextdb->prepare($sql);
$stmtProfile->bindParam(1, $user_id, PDO::PARAM_INT);
$stmtProfile->execute();
$user = $stmtProfile->fetch(PDO::FETCH_ASSOC);

// ✅ path โฟลเดอร์เก็บรูป (อยู่ใน all_powpoint)
$avatarDir = "images/avatar/";
$defaultAvatar = $avatarDir . "users.png";

$updateSuccess = false; // ตัวแปรสำหรับ SweetAlert

// ✅ ถ้ามีการอัปเดตข้อมูลจากฟอร์ม
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];

    // ตรวจสอบไฟล์อัปโหลด
    if (isset($_FILES["avatar"]) && $_FILES["avatar"]["error"] == 0) {
        $targetDir = __DIR__ . "/images/avatar/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileName = time() . "_" . basename($_FILES["avatar"]["name"]);
        $targetFile = $targetDir . $fileName;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $allowed = ["jpg", "jpeg", "png", "gif"];

        if (in_array($imageFileType, $allowed)) {
            move_uploaded_file($_FILES["avatar"]["tmp_name"], $targetFile);
            $avatarPath = "images/avatar/" . $fileName; // ✅ path สำหรับเว็บ
        } else {
            $avatarPath = $user["avatar"];
        }
    } else {
        $avatarPath = $user["avatar"];
    }

    // ✅ อัปเดตข้อมูลในฐานข้อมูล
    $update = "UPDATE users SET username = ?, email = ?, phone = ?, address = ?, avatar = ? WHERE user_id = ?";
    $stmt = $connextdb->prepare($update);
    $stmt->execute([$username, $email, $phone, $address, $avatarPath, $user_id]);

    // ✅ อัปเดต session
    $_SESSION['username'] = $username;
    $_SESSION['email'] = $email;
    $_SESSION['avatar'] = $avatarPath; // ✅ เพิ่มบรรทัดนี้ เพื่อให้ header_nav ใช้รูปใหม่ได้ทันที
    session_write_close(); // ✅ เพิ่มบรรทัดนี้ เพื่อให้ session ปิดและข้อมูลใหม่โหลดได้เลย

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

        .profile-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .profile-header img {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            border: 4px solid #6dd5fa;
            object-fit: cover;
            margin-bottom: 15px;
            transition: 0.3s ease-in-out;
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
        <div class="profile-header">
            <h3>โปรไฟล์ของฉัน</h3>

            <form method="POST" enctype="multipart/form-data">
                <?php
                $avatarPath = (!empty($user['avatar']) && file_exists(__DIR__ . '/' . $user['avatar']))
                    ? htmlspecialchars($user['avatar'])
                    : htmlspecialchars($defaultAvatar);
                ?>
                <img id="avatarPreview" src="<?php echo $avatarPath; ?>" alt="Avatar">

                <!-- ✅ preview รูปใหม่ -->
                <div class="mt-2">
                    <input type="file" name="avatar" id="avatar" class="form-control" accept="image/*"
                        onchange="previewImage(event)">
                </div>

                <hr>

                <div class="mb-3">
                    <label>ชื่อผู้ใช้</label>
                    <input type="text" name="username" class="form-control"
                        value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </div>

                <div class="mb-3">
                    <label>อีเมล</label>
                    <input type="email" name="email" class="form-control"
                        value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>

                <div class="mb-3">
                    <label>เบอร์โทรศัพท์</label>
                    <input type="text" name="phone" class="form-control"
                        value="<?php echo htmlspecialchars($user['phone']); ?>">
                </div>

                <div class="mb-3">
                    <label>ที่อยู่</label>
                    <textarea name="address" class="form-control"
                        rows="3"><?php echo htmlspecialchars($user['address']); ?></textarea>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-save px-4">บันทึกการเปลี่ยนแปลง</button>
                    <a href="index.php" class="btn btn-secondary px-4">ยกเลิก</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- ✅ แสดง preview รูปก่อนอัปโหลด -->
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function () {
                const output = document.getElementById('avatarPreview');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>

    <!-- ✅ SweetAlert2 แจ้งอัปเดตสำเร็จ -->
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