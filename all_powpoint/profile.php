<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>โปรไฟล์ของฉัน</title>
</head>
<body>
    <h2>ยินดีต้อนรับ, <?php echo $_SESSION['username']; ?>!</h2>
    <a href="logout.php">ออกจากระบบ</a>
</body>
</html>
