<?php
include 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['id'];
    $pet_name = $_POST['pet_name'];
    $checkin = $_POST['checkin_date'];
    $checkout = $_POST['checkout_date'];
    $total = $_POST['total_price'];

    $stmt = $conn->prepare("UPDATE room_booking SET pet_name=?, checkin_date=?, checkout_date=?, total_price=? WHERE id=?");
    $stmt->execute([$pet_name, $checkin, $checkout, $total, $id]);

    header("Location: room_booking.php");
    exit;
}
?>
