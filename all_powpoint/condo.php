<?php
session_start();
include '../Admin/config/connextdb.php'; // เชื่อมฐานข้อมูลแบบ PDO

// ✅ ตรวจสอบว่าผู้ใช้ล็อกอินแล้วหรือยัง
if (!isset($_SESSION['user_id'])) {
    echo "
    <!DOCTYPE html>
    <html lang='th'>
    <head>
        <meta charset='UTF-8'>
        <title>กรุณาเข้าสู่ระบบ</title>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <style>
            body {
                background-color: #f8f9fa;
                font-family: 'Prompt', sans-serif;
            }
        </style>
    </head>
    <body>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'warning',
                title: 'กรุณาเข้าสู่ระบบก่อนจอง',
                text: 'คุณต้องเข้าสู่ระบบก่อนจองห้องพักสัตว์เลี้ยง',
                confirmButtonText: 'เข้าสู่ระบบ',
                confirmButtonColor: '#3c91e6',
                backdrop: 'rgba(0,0,0,0.4)',
                allowOutsideClick: false
            }).then(() => {
                window.location.href = 'signin.php';
            });
        });
        </script>
    </body>
    </html>
    ";
    exit(); // ❌ หยุดไม่ให้โหลดหน้าจอง
}

// ✅ ถ้าล็อกอินแล้ว ดึงข้อมูลห้องพักจากฐานข้อมูล
$query = "SELECT * FROM room_type";
$stmtCondo = $connextdb->prepare($query);
$stmtCondo->execute();
$result = $stmtCondo->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>PowPoint Condo | จองห้องพักสัตว์เลี้ยง</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #e9f2f7;
      font-family: 'Prompt', sans-serif;
    }
    .container {
      max-width: 900px;
      margin-top: 50px;
    }
    .card {
      border-radius: 15px;
      box-shadow: 0 3px 8px rgba(0,0,0,0.1);
    }
    .btn-book {
      background-color: #3c91e6;
      color: white;
    }
    .btn-book:hover {
      background-color: #2a6db4;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="text-center mb-4">
      <h2 class="fw-bold text-primary">จองห้องพักสัตว์เลี้ยง</h2>
      <p>เลือกประเภทห้องและกรอกรายละเอียดการเข้าพัก</p>
    </div>

    <div class="card p-4">
      <form action="condo_save.php" method="POST">
        <div class="mb-3">
          <label class="form-label">ชื่อเจ้าของสัตว์เลี้ยง</label>
          <input type="text" class="form-control" name="owner_name" required>
        </div>

        <div class="mb-3">
          <label class="form-label">ชื่อสัตว์เลี้ยง</label>
          <input type="text" class="form-control" name="pet_name" required>
        </div>

        <div class="mb-3">
          <label class="form-label">เลือกประเภทห้องพัก</label>
          <select class="form-select" name="room_id" id="roomSelect" required>
            <option value="">-- กรุณาเลือกห้องพัก --</option>
            <?php foreach ($result as $row) { ?>
              <option value="<?= $row['id'] ?>" data-price="<?= $row['price_night'] ?>" data-week="<?= $row['price_week'] ?>">
                <?= $row['name'] ?> (คืนละ <?= number_format($row['price_night']) ?> บาท)
              </option>
            <?php } ?>
          </select>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">วันที่เช็คอิน</label>
            <input type="date" class="form-control" name="checkin" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">วันที่เช็คเอาท์</label>
            <input type="date" class="form-control" name="checkout" required>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">จำนวนวันเข้าพัก</label>
          <input type="number" class="form-control" id="days" name="days" min="1" required>
        </div>

        <div class="mb-3">
          <label class="form-label fw-bold text-success">ราคารวมโดยประมาณ:</label>
          <input type="text" class="form-control" id="totalPrice" readonly>
        </div>

        <div class="text-center">
          <button type="button" id="btnConfirm" class="btn btn-book px-4 me-2">ยืนยันการจอง</button>
          <button type="button" id="btnCancel" class="btn btn-danger px-4">ยกเลิก</button>
        </div>
      </form>
    </div>
  </div>

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    const roomSelect = document.getElementById('roomSelect');
    const daysInput = document.getElementById('days');
    const totalPrice = document.getElementById('totalPrice');

    function calculatePrice() {
      const selectedOption = roomSelect.options[roomSelect.selectedIndex];
      const pricePerNight = selectedOption.getAttribute('data-price');
      const days = daysInput.value;
      if (pricePerNight && days) {
        const total = pricePerNight * days;
        totalPrice.value = total.toLocaleString() + ' บาท';
      } else {
        totalPrice.value = '';
      }
    }

    roomSelect.addEventListener('change', calculatePrice);
    daysInput.addEventListener('input', calculatePrice);

    // ✅ ปุ่มยืนยันการจอง
    document.getElementById('btnConfirm').addEventListener('click', function() {
      Swal.fire({
        title: 'ยืนยันการจอง?',
        text: "ตรวจสอบข้อมูลให้ถูกต้องก่อนยืนยัน",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'ยืนยัน',
        cancelButtonText: 'ตรวจสอบอีกครั้ง',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        backdrop: true,
        allowOutsideClick: false
      }).then((result) => {
        if (result.isConfirmed) {
          Swal.fire({
            title: 'กำลังดำเนินการ...',
            text: 'กรุณารอสักครู่',
            icon: 'info',
            showConfirmButton: false,
            timer: 1200,
            didOpen: () => {
              document.querySelector('form').submit();
            }
          });
        }
      });
    });

    // ❌ ปุ่มยกเลิก
    document.getElementById('btnCancel').addEventListener('click', function() {
      Swal.fire({
        title: 'ต้องการยกเลิกการจอง?',
        text: "หากยืนยัน ระบบจะกลับไปหน้าแรก",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'ใช่',
        cancelButtonText: 'ไม่',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33'
      }).then((result) => {
        if (result.isConfirmed) {
          Swal.fire({
            title: 'กำลังกลับหน้าแรก...',
            icon: 'success',
            showConfirmButton: false,
            timer: 1000
          });
          setTimeout(() => {
            window.location.href = 'index.php';
          }, 1000);
        }
      });
    });
  </script>

</body>
</html>
