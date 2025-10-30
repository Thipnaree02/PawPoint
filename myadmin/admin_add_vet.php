<?php
session_start();

//  ตรวจสอบการล็อกอิน
if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit;
}

include 'config/db.php'; // ใช้ $conn แบบ PDO

$message = "";
$type = "";
$redirect = "";

if (isset($_POST['add_vet'])) {
  // รับค่าจากฟอร์ม
  $fullname = trim($_POST['fullname']);
  $specialization = trim($_POST['specialization']);
  $phone = trim($_POST['phone']);
  $email = trim($_POST['email']);
  $experience_years = trim($_POST['experience_years']);
  $working_days = trim($_POST['working_days']);
  $start_time = trim($_POST['start_time']);
  $end_time = trim($_POST['end_time']);
  $note = trim($_POST['note']);

  // จัดการรูปภาพ
  $photo = "default_vet.png";
  if (!empty($_FILES['photo']['name'])) {
    $targetDir = "uploads/vets/";
    if (!is_dir($targetDir)) {
      mkdir($targetDir, 0777, true); // สร้างโฟลเดอร์ถ้ายังไม่มี
    }

    $photo = time() . "_" . basename($_FILES["photo"]["name"]);
    $targetFilePath = $targetDir . $photo;
    move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFilePath);
  }

  try {
    // ใช้ Prepared Statement ป้องกัน SQL Injection
    $stmt = $conn->prepare("
            INSERT INTO veterinarians 
            (fullname, specialization, phone, email, experience_years, working_days, start_time, end_time, photo, note)
            VALUES (:fullname, :specialization, :phone, :email, :experience_years, :working_days, :start_time, :end_time, :photo, :note)
        ");

    $stmt->execute([
      ':fullname' => $fullname,
      ':specialization' => $specialization,
      ':phone' => $phone,
      ':email' => $email,
      ':experience_years' => $experience_years,
      ':working_days' => $working_days,
      ':start_time' => $start_time,
      ':end_time' => $end_time,
      ':photo' => $photo,
      ':note' => $note
    ]);

    $message = "เพิ่มข้อมูลสัตวแพทย์เรียบร้อยแล้ว!";
    $type = "success";
    $redirect = "vet_list.php";

  } catch (PDOException $e) {
    $message = "เกิดข้อผิดพลาด: " . $e->getMessage();
    $type = "error";
  }
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
  <meta charset="UTF-8">
  <title>เพิ่มข้อมูลสัตวแพทย์</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

  <!-- SweetAlert2 แจ้งเตือน -->
  <?php if (!empty($message)): ?>
    <script>
      Swal.fire({
        title: '<?php echo ($type === "success") ? "สำเร็จ!" : "เกิดข้อผิดพลาด"; ?>',
        text: '<?php echo $message; ?>',
        icon: '<?php echo $type; ?>',
        confirmButtonText: 'ตกลง',
        confirmButtonColor: '<?php echo ($type === "success") ? "#198754" : "#dc3545"; ?>'
      }).then((result) => {
        <?php if ($type === "success" && !empty($redirect)): ?>
          window.location.href = '<?php echo $redirect; ?>';
        <?php endif; ?>
      });
    </script>
  <?php endif; ?>

</body>

</html>