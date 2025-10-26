<?php
session_start();
include '../myadmin/config/db.php';

// ตรวจสอบการเข้าสู่ระบบ
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $pet_name = $_POST['pet_name'];
    $room_type_id = $_POST['room_type_id'];
    $checkin = $_POST['checkin_date'];
    $checkout = $_POST['checkout_date'];
    $total_price = $_POST['total_price'];

    $sql = "INSERT INTO room_booking (user_id, pet_name, room_type_id, checkin_date, checkout_date, total_price)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmtCondoSave = $conn->prepare($sql);
    $stmtCondoSave->execute([$user_id, $pet_name, $room_type_id, $checkin, $checkout, $total_price]);

    echo "<script>
        alert('✅ จองห้องสำเร็จ!');
        window.location.href='condo.php';
    </script>";
}
?>
