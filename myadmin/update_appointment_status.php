<?php

session_start();

// ถ้ายังไม่มี session แสดงว่ายังไม่ล็อกอิน
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

include 'config/db.php';
header('Content-Type: application/json; charset=utf-8');

try {
    if (empty($_POST['id']) || empty($_POST['status'])) {
        echo json_encode(['success' => false, 'message' => 'ข้อมูลไม่ครบ']);
        exit;
    }

    $id = $_POST['id'];
    $status = $_POST['status'];
    $allowed = ['pending', 'confirmed', 'completed', 'cancelled'];

    if (!in_array($status, $allowed)) {
        echo json_encode(['success' => false, 'message' => 'สถานะไม่ถูกต้อง']);
        exit;
    }

    $stmt = $conn->prepare("UPDATE appointments SET status = :status WHERE app_id = :id");
    $stmt->execute([':status' => $status, ':id' => $id]);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>