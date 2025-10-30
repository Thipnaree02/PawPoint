<?php
session_start();
include '../myadmin/config/db.php';

// ดึงข้อมูลห้องพักทั้งหมด
$stmt = $conn->query("SELECT * FROM room_type ORDER BY id ASC");
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ตรวจสอบสถานะล็อกอิน
$isLoggedIn = isset($_SESSION['user_id']);
?>

<!doctype html>
<html lang="th">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PawPoint Condo | จองห้องพักสัตว์เลี้ยง</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body {
      background-color: #f5f9fc;
      font-family: "Noto Sans Thai", sans-serif;
    }

    .room-card {
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
    }

    .room-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .room-img {
      width: 100%;
      height: 220px;
      object-fit: cover;
    }

    .btn-book {
      background-color: #4aa9d9;
      color: #fff;
      border: none;
      transition: 0.2s;
    }

    .btn-book:hover {
      background-color: #0078b5;
    }
  </style>
</head>

<body>

    <div class="container py-5">
      <div class="text-center mb-5">
    <h1 class="fw-bold text-success">PawPoint Condo</h1>
    <p class="text-muted">เลือกห้องพักสำหรับเพื่อนสี่ขาของคุณ 🐾</p>

    <!-- ปุ่มกลับไปหน้าแรก -->
    <a href="index.php#section_4" class="btn btn-outline-success mt-3 px-4">
      <i class="bi bi-arrow-left-circle"></i> กลับไปหน้าแรก
    </a>
  </div>


    <div class="row g-4">
      <?php foreach ($rooms as $room): ?>
        <div class="col-md-4">
          <div class="card room-card">
            <img src="images/causes/<?php echo strtolower(str_replace(' ', '-', $room['name'])); ?>.jpg"
              onerror="this.src='images/causes/default-room.jpg'" class="room-img" alt="<?php echo $room['name']; ?>">

            <div class="card-body text-center">
              <h4 class="card-title text-success"><?php echo htmlspecialchars($room['name']); ?></h4>
              <p class="text-muted small"><?php echo htmlspecialchars($room['description']); ?></p>
              <p class="mb-1"><strong>฿<?php echo number_format($room['price_night'], 2); ?></strong> / คืน</p>
              <p><strong>฿<?php echo number_format($room['price_week'], 2); ?></strong> / สัปดาห์</p>

              <!-- ปุ่มเปิด Modal -->
              <button class="btn btn-book w-100"
                <?php if ($isLoggedIn): ?>
                  data-bs-toggle="modal" data-bs-target="#bookModal<?php echo $room['id']; ?>"
                <?php else: ?>
                  onclick="showLoginAlert()"
                <?php endif; ?>>
                <i class="bi bi-calendar-check"></i> จองห้องนี้
              </button>
            </div>
          </div>
        </div>

        <!-- Modal จองห้อง -->
        <?php if ($isLoggedIn): ?>
        <div class="modal fade" id="bookModal<?php echo $room['id']; ?>" tabindex="-1">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <form action="condo_save.php" method="POST">
                <div class="modal-header">
                  <h5 class="modal-title">จองห้อง: <?php echo $room['name']; ?></h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                  <input type="hidden" name="room_type_id" value="<?php echo $room['id']; ?>">
                  <input type="hidden" id="pricePerNight<?php echo $room['id']; ?>"
                    value="<?php echo $room['price_night']; ?>">

                  <div class="mb-3">
                    <label class="form-label">ชื่อสัตว์เลี้ยง</label>
                    <input type="text" name="pet_name" class="form-control" required>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">วันที่เช็คอิน</label>
                    <input type="date" name="checkin_date" id="checkin<?php echo $room['id']; ?>" class="form-control"
                      required>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">วันที่เช็คเอาท์</label>
                    <input type="date" name="checkout_date" id="checkout<?php echo $room['id']; ?>" class="form-control"
                      required>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">ราคารวมทั้งหมด (บาท)</label>
                    <input type="text" name="total_price" id="total<?php echo $room['id']; ?>" class="form-control"
                      readonly>
                  </div>
                </div>

                <div class="modal-footer">
                  <button type="submit" class="btn btn-success">ยืนยันการจอง</button>
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- SweetAlert Script -->
  <script>
    function showLoginAlert() {
      Swal.fire({
        title: 'ต้องเข้าสู่ระบบก่อน!',
        text: 'กรุณาเข้าสู่ระบบก่อนทำการจองห้องพัก 🐶🐱',
        icon: 'warning',
        confirmButtonText: 'เข้าสู่ระบบ',
        confirmButtonColor: '#4aa9d9',
        showCancelButton: true,
        cancelButtonText: 'ยกเลิก',
        // backdrop: `rgba(0,0,0,0.4) url('https://media.giphy.com/media/l0MYGB3E3q4kN9x3u/giphy.gif') center left no-repeat`,
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = "signin.php";
        }
      });
    }
  </script>

  <?php if ($isLoggedIn): ?>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      <?php foreach ($rooms as $room): ?>
        const checkin<?php echo $room['id']; ?> = document.getElementById('checkin<?php echo $room['id']; ?>');
        const checkout<?php echo $room['id']; ?> = document.getElementById('checkout<?php echo $room['id']; ?>');
        const total<?php echo $room['id']; ?> = document.getElementById('total<?php echo $room['id']; ?>');
        const price<?php echo $room['id']; ?> = parseFloat(document.getElementById('pricePerNight<?php echo $room['id']; ?>').value);

        function calculate<?php echo $room['id']; ?>() {
          const checkinDate = new Date(checkin<?php echo $room['id']; ?>.value);
          const checkoutDate = new Date(checkout<?php echo $room['id']; ?>.value);

          if (checkinDate && checkoutDate && checkoutDate > checkinDate) {
            const timeDiff = checkoutDate - checkinDate;
            const days = timeDiff / (1000 * 3600 * 24);
            const totalPrice = days * price<?php echo $room['id']; ?>;
            total<?php echo $room['id']; ?>.value = totalPrice.toFixed(2);
          } else {
            total<?php echo $room['id']; ?>.value = "";
          }
        }

        checkin<?php echo $room['id']; ?>.addEventListener('change', calculate<?php echo $room['id']; ?>);
        checkout<?php echo $room['id']; ?>.addEventListener('change', calculate<?php echo $room['id']; ?>);
      <?php endforeach; ?>
    });
  </script>
  <?php endif; ?>

</body>
</html>
