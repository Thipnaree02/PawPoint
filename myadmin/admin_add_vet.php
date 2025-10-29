<?php

session_start();

// ถ้ายังไม่มี session แสดงว่ายังไม่ล็อกอิน
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

include 'config/db.php';

if (isset($_POST['add_vet'])) {
    $fullname = $_POST['fullname'];
    $specialization = $_POST['specialization'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $experience_years = $_POST['experience_years'];
    $working_days = $_POST['working_days'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $note = $_POST['note'];

    // อัปโหลดรูปภาพ
    $photo = "default_vet.png";
    if (!empty($_FILES['photo']['name'])) {
        $targetDir = "uploads/vets/";
        $photo = basename($_FILES["photo"]["name"]);
        $targetFilePath = $targetDir . $photo;
        move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFilePath);
    }

    $sql = "INSERT INTO veterinarians 
            (fullname, specialization, phone, email, experience_years, working_days, start_time, end_time, photo, note)
            VALUES ('$fullname', '$specialization', '$phone', '$email', '$experience_years', '$working_days', '$start_time', '$end_time', '$photo', '$note')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('เพิ่มข้อมูลสัตวแพทย์เรียบร้อย'); window.location='vet_list.php';</script>";
    } else {
        echo "เกิดข้อผิดพลาด: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>เพิ่มข้อมูลสัตวแพทย์</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-5">
    <div class="card shadow-lg">
      <div class="card-header bg-primary text-white">
        <h4 class="mb-0">เพิ่มข้อมูลสัตวแพทย์</h4>
      </div>
      <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
          <div class="mb-3">
            <label class="form-label">ชื่อ-นามสกุล</label>
            <input type="text" name="fullname" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">สาขาความเชี่ยวชาญ</label>
            <input type="text" name="specialization" class="form-control" placeholder="เช่น ศัลยกรรม, ทันตกรรม">
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">เบอร์โทรศัพท์</label>
              <input type="text" name="phone" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">อีเมล</label>
              <input type="email" name="email" class="form-control">
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label">ประสบการณ์ (ปี)</label>
              <input type="number" name="experience_years" class="form-control" min="0">
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">วันทำงาน</label>
              <input type="text" name="working_days" class="form-control" placeholder="จันทร์ - ศุกร์">
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">เวลาเริ่ม - สิ้นสุด</label>
              <div class="d-flex">
                <input type="time" name="start_time" class="form-control me-2">
                <input type="time" name="end_time" class="form-control">
              </div>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">รูปภาพ</label>
            <input type="file" name="photo" class="form-control">
          </div>

          <div class="mb-3">
            <label class="form-label">หมายเหตุ</label>
            <textarea name="note" class="form-control" rows="3"></textarea>
          </div>

          <div class="text-end">
            <button type="submit" name="add_vet" class="btn btn-success px-4">บันทึก</button>
            <a href="vet_list.php" class="btn btn-secondary">ย้อนกลับ</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
