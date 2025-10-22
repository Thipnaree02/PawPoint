<?php
session_start();
session_unset();  // ล้างค่าทั้งหมดใน session
session_destroy(); // ปิด session

header("Location: signin.php"); // กลับไปหน้าเข้าสู่ระบบ
exit();
?>
