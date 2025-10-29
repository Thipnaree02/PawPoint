<?php

session_start();

// ถ้ายังไม่มี session แสดงว่ายังไม่ล็อกอิน
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

header('Content-Type: application/json');
include 'config/db.php'; // ✅ ตรวจสอบ path ให้ตรงกับที่ใช้ในหลังบ้าน

if (!isset($_POST['id'], $_POST['status'])) {
    echo json_encode(['success' => false, 'message' => 'ข้อมูลไม่ครบ']);
    exit;
}

$id = $_POST['id'];
$status = $_POST['status'];

// ✅ ตรวจสอบสถานะที่อนุญาต
$allowed_status = ['pending', 'confirmed', 'completed', 'cancelled'];
if (!in_array($status, $allowed_status)) {
    echo json_encode(['success' => false, 'message' => 'สถานะไม่ถูกต้อง']);
    exit;
}

try {
    // ✅ อัปเดตสถานะในฐานข้อมูล
    $stmt = $conn->prepare("UPDATE grooming_bookings SET status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'ไม่พบข้อมูลหรือสถานะเหมือนเดิม']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
