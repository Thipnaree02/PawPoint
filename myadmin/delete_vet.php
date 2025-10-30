<?php
session_start();
include 'config/db.php';

// ตรวจสอบสิทธิ์การเข้าถึง (ต้องล็อกอินก่อน)
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// ตรวจสอบว่ามีการส่งค่า id มาหรือไม่
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    try {
        // ดึงข้อมูลรูปภาพเพื่อลบไฟล์ออกจากโฟลเดอร์
        $stmt = $conn->prepare("SELECT photo FROM veterinarians WHERE id = ?");
        $stmt->execute([$id]);
        $vet = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($vet) {
            // ลบรูปภาพออกจากโฟลเดอร์ (ถ้าไม่ใช่รูป default)
            if (!empty($vet['photo']) && $vet['photo'] !== 'default_vet.png') {
                $filePath = "uploads/vets/" . $vet['photo'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            // ลบข้อมูลสัตวแพทย์ออกจากฐานข้อมูล
            $deleteStmt = $conn->prepare("DELETE FROM veterinarians WHERE id = ?");
            $deleteStmt->execute([$id]);

            $title = "ลบข้อมูลสำเร็จ!";
            $message = "ระบบได้ลบข้อมูลสัตวแพทย์ออกจากฐานข้อมูลเรียบร้อยแล้ว";
            $type = "success";
        } else {
            $title = "ไม่พบข้อมูล!";
            $message = "ไม่พบข้อมูลสัตวแพทย์ที่ต้องการลบ";
            $type = "error";
        }

    } catch (PDOException $e) {
        $title = "เกิดข้อผิดพลาด!";
        $message = "ไม่สามารถลบข้อมูลได้: " . $e->getMessage();
        $type = "error";
    }
} else {
    $title = "คำขอไม่ถูกต้อง!";
    $message = "ไม่พบรหัสสัตวแพทย์ที่ต้องการลบ";
    $type = "error";
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>ลบข้อมูลสัตวแพทย์ | PawPoint Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: "Noto Sans Thai", sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
    </style>
</head>

<body>

    <script>
        // แสดง SweetAlert2 แบบสวยพร้อม animation
        Swal.fire({
            title: "<?= $title ?>",
            text: "<?= $message ?>",
            icon: "<?= $type ?>",
            iconColor: "<?= ($type === 'success') ? '#198754' : '#dc3545' ?>",
            confirmButtonColor: "<?= ($type === 'success') ? '#198754' : '#dc3545' ?>",
            confirmButtonText: "ตกลง",
            showClass: {
                popup: "animate__animated animate__fadeInDown"
            },
            hideClass: {
                popup: "animate__animated animate__fadeOutUp"
            },
            backdrop: `
      rgba(0,0,0,0.3)
      left top
      no-repeat
    `
        }).then(() => {
            window.location = "vet_list.php";
        });
    </script>

    <!-- ✅ ใส่ animation library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
</body>

</html>