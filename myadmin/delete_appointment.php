<?php

session_start();

// ถ้ายังไม่มี session แสดงว่ายังไม่ล็อกอิน
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
require_once 'config/db.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

// ลบข้อมูล
$stmt = $conn->prepare("DELETE FROM appointments WHERE app_id = ?");
if ($stmt->execute([$id])) {
    echo "<script>alert('ลบข้อมูลสำเร็จแล้ว'); window.location='index.php';</script>";
} else {
    echo "<script>alert('ไม่สามารถลบข้อมูลได้'); window.location='index.php';</script>";
}
