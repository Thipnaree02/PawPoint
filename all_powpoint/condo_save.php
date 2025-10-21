<?php
session_start();
include '../Admin/config/connextdb.php'; // เรียกไฟล์เชื่อมฐานข้อมูล (PDO)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าจากฟอร์ม condo.php
    $owner_name = trim($_POST['owner_name']);
    $pet_name   = trim($_POST['pet_name']);
    $room_id    = $_POST['room_id'];
    $checkin    = $_POST['checkin'];
    $checkout   = $_POST['checkout'];
    $days       = $_POST['days'];

    // ตรวจสอบค่าว่าง
    if (empty($owner_name) || empty($pet_name) || empty($room_id) || empty($checkin) || empty($checkout) || empty($days)) {
        echo "<script>alert('กรุณากรอกข้อมูลให้ครบทุกช่อง'); window.history.back();</script>";
        exit;
    }

    try {
        // ✅ ดึงราคาห้องจากตาราง room_type
        $stmt = $connextdb->prepare("SELECT price_night FROM room_type WHERE id = :room_id");
        $stmt->bindParam(':room_id', $room_id);
        $stmt->execute();
        $room = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$room) {
            echo "<script>alert('ไม่พบข้อมูลห้องที่เลือก'); window.history.back();</script>";
            exit;
        }

        // ✅ คำนวณราคารวม
        $total_price = $room['price_night'] * $days;

        // ✅ บันทึกข้อมูลลงตาราง booking
        $sql = "INSERT INTO booking (owner_name, pet_name, room_id, checkin, checkout, days, total_price)
                VALUES (:owner_name, :pet_name, :room_id, :checkin, :checkout, :days, :total_price)";
        $stmt = $connextdb->prepare($sql);
        $stmt->bindParam(':owner_name', $owner_name);
        $stmt->bindParam(':pet_name', $pet_name);
        $stmt->bindParam(':room_id', $room_id);
        $stmt->bindParam(':checkin', $checkin);
        $stmt->bindParam(':checkout', $checkout);
        $stmt->bindParam(':days', $days);
        $stmt->bindParam(':total_price', $total_price);
        $stmt->execute();

        // ✅ แสดงผลยืนยันสำเร็จ
        echo "<script>
                alert('ยืนยันการจองสำเร็จ!\\nราคารวมทั้งหมด: " . number_format($total_price, 2) . " บาท');
                window.location='index.php';
              </script>";
    } catch (PDOException $e) {
        echo "<script>alert('เกิดข้อผิดพลาด: " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('ไม่พบข้อมูลที่ส่งมา'); window.location='condo.php';</script>";
}
?>
