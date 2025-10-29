<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include '../myadmin/config/db.php';

if (isset($_POST['room_type_id'])) {
    if (!isset($_SESSION['user_id'])) {
        echo "<script>alert('กรุณาเข้าสู่ระบบก่อนทำการจอง');window.location.href='signin.php';</script>";
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $room_type_id = $_POST['room_type_id'];
    $pet_name = $_POST['pet_name'];
    $checkin_date = $_POST['checkin_date'];
    $checkout_date = $_POST['checkout_date'];
    $total_price = $_POST['total_price'];

    try {
        $stmtCondoSave = $conn->prepare("
            INSERT INTO room_booking (user_id, room_type_id, pet_name, checkin_date, checkout_date, total_price, status)
            VALUES (?, ?, ?, ?, ?, ?, 'pending')
        ");
        $stmtCondoSave->execute([$user_id, $room_type_id, $pet_name, $checkin_date, $checkout_date, $total_price]);

        echo "<!DOCTYPE html>
        <html lang='th'>
        <head>
          <meta charset='UTF-8'>
          <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
        <script>
          Swal.fire({
            title: 'จองสำเร็จ! 🎉',
            text: 'ระบบได้บันทึกการจองของคุณเรียบร้อยแล้ว',
            icon: 'success',
            confirmButtonText: 'ตกลง',
            confirmButtonColor: '#4aa9d9'
          }).then(() => {
            window.location.href = 'index.php';
          });
        </script>
        </body>
        </html>";
    } catch (PDOException $e) {
        echo '<h4 style=\"color:red;text-align:center;\">เกิดข้อผิดพลาด: ' . $e->getMessage() . '</h4>';
    }
} else {
    header('Location: index.php');
    exit;
}
?>
