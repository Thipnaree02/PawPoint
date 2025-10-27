<?php
include 'config/db.php';
header('Content-Type: application/json; charset=utf-8');

try {
    // ตรวจสอบว่ามีข้อมูลที่ส่งมาครบหรือไม่
    if (empty($_POST['id']) || empty($_POST['status'])) {
        echo json_encode([
            "success" => false,
            "message" => "ขาดข้อมูลที่จำเป็น"
        ]);
        exit;
    }

    $id = $_POST['id'];
    $status = $_POST['status'];

    // ตรวจสอบว่าสถานะที่ส่งมาถูกต้อง
    $allowed = ['pending', 'confirmed', 'completed', 'cancelled'];
    if (!in_array($status, $allowed)) {
        echo json_encode([
            "success" => false,
            "message" => "สถานะไม่ถูกต้อง"
        ]);
        exit;
    }

    // อัปเดตสถานะในฐานข้อมูล
    $stmt = $conn->prepare("UPDATE room_booking SET status = :status WHERE id = :id");
    $stmt->execute([':status' => $status, ':id' => $id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode([
            "success" => true,
            "message" => "อัปเดตสถานะสำเร็จ"
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "ไม่พบข้อมูลที่ต้องการอัปเดต"
        ]);
    }

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
?>
