<?php

session_start();

// ถ้ายังไม่มี session แสดงว่ายังไม่ล็อกอิน
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

include 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $_POST['id'] ?? '';
  if (!$id) {
    echo json_encode(['success' => false, 'message' => 'ไม่พบ ID']);
    exit;
  }

  try {
    $stmt = $conn->prepare("DELETE FROM room_booking WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode(['success' => true]);
  } catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
  }
}
?>
