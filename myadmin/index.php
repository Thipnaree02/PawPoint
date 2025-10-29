<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit;
}
include 'config/db.php';

// ดึงชื่อและรูปแอดมินจาก session
$admin_name = $_SESSION['admin_name'] ?? 'แอดมิน';
$admin_image = $_SESSION['admin_image'] ?? '';

// แปลง path เป็น path จริงในเครื่องเพื่อเช็กไฟล์
$real_path = __DIR__ . '/' . ltrim($admin_image, '/');

// ตรวจสอบว่ามีไฟล์จริงหรือไม่
if (!empty($admin_image) && file_exists($real_path)) {
  // ใช้ path เดิมเลย (browser เข้าได้)
  $admin_image = $admin_image;
} else {
  // ถ้าไม่มี ให้ใช้รูป default
  $admin_image = 'assets/images/default_user.png';
}


// --------------------------------------------------------
//  ดึงข้อมูลจริงจากฐานข้อมูล
// --------------------------------------------------------

//  ดึงข้อมูลผู้ป่วยล่าสุด (6 รายการล่าสุด)
$stmtIndex = $conn->query("
  SELECT 
    a.app_id,
    a.pet_name,
    a.service_type,
    a.date,
    a.status,
    v.vet_name,
    u.username AS owner_name
  FROM appointments a
  LEFT JOIN vets v ON a.vet_id = v.vet_id
  LEFT JOIN users u ON a.user_id = u.user_id
  ORDER BY a.app_id DESC
  LIMIT 6
");
$appointments = $stmtIndex->fetchAll(PDO::FETCH_ASSOC);

// 2️⃣ นับจำนวนคิววันนี้
$today = date('Y-m-d');
$stmtQueue = $conn->prepare("SELECT COUNT(*) FROM appointments WHERE date = ?");
$stmtQueue->execute([$today]);
$queueToday = $stmtQueue->fetchColumn();

// 3️⃣ ดึงจำนวนลูกค้าใหม่ (เดือนนี้)
$newCustomers = $conn->query("
  SELECT COUNT(*) FROM users WHERE MONTH(created_at) = MONTH(CURDATE())
")->fetchColumn();

// 4️⃣ นับจำนวนลูกค้าทั้งหมด
$totalCustomers = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();

// 5️⃣ ดึงมูลค่าการจองเดือนนี้ (ยังไม่ชำระ)
$totalBookingValue = $conn->query("
  SELECT 
    COALESCE(
      (SELECT SUM(total_price)
       FROM room_booking 
       WHERE MONTH(checkin_date) = MONTH(CURDATE())
      ), 0
    )
    +
    COALESCE(
      (SELECT SUM(gp.price)
       FROM grooming_bookings gb
       LEFT JOIN grooming_packages gp ON gb.package_id = gp.id
       WHERE MONTH(gb.booking_date) = MONTH(CURDATE())
      ), 0
    ) AS total
")->fetchColumn();

// 6️⃣ ดึงจำนวนบริการแยกตามประเภท
$stmtService = $conn->query("
  SELECT service_type, COUNT(*) AS count 
  FROM appointments 
  GROUP BY service_type
");
$services = $stmtService->fetchAll(PDO::FETCH_KEY_PAIR);

// 7️⃣ ดึงข้อมูลสำหรับกราฟ (จำนวนสัตว์เลี้ยงที่มีการนัดหมายย้อนหลัง 7 วัน)
$stmtChart = $conn->query("
  SELECT DATE(date) AS day, COUNT(*) AS total 
  FROM appointments 
  WHERE date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
  GROUP BY day ORDER BY day ASC
");
$chartData = $stmtChart->fetchAll(PDO::FETCH_ASSOC);

// --------------------------------------------------------
$serviceNames = [
  'health_check' => 'ตรวจสุขภาพ',
  'vaccination' => 'ฉีดวัคซีน',
  'sterilization' => 'ผ่าตัด/ทำหมัน',
  'surgery' => 'ผ่าตัด',
  'other' => 'อื่น ๆ'
];
?>

<!doctype html>
<html lang="th">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PoePoint Admin — แดชบอร์ดคลินิกสัตว์เลี้ยง</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/css/styles.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300;400;500;600;700&display=swap"
    rel="stylesheet">

  <style>

    .brand-badge {
  width: 55px;
  height: 55px;
  background-color: #8be28b; /* เขียวมิ้นต์ */
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden; /* ✅ ตัดส่วนเกินของรูป */
}

.brand-logo {
  width: 100%;
  height: 100%;
  object-fit: cover; /* ✅ ครอบเต็มแต่ไม่บี้ภาพ */
  border-radius: 10px; /* ✅ โค้งตามกรอบ */
}


  </style>

</head>

<body>
  <div class="d-flex">
    <!-- Sidebar -->
    <aside id="sidebar" class="sidebar border-end">
      <div class="p-3 d-flex align-items-center gap-2 brand">
        <div class="brand-badge">
          <img src="../all_pawpoint/images/logo.png" alt="PawPoint Logo" class="brand-logo">
        </div>
        <div>
          <div class="fw-bold">PawPoint</div>
          <small class="text-muted">Pet Clinic Admin</small>
        </div>
        <button class="btn btn-light btn-sm ms-auto d-lg-none" id="btnCloseSidebar">
          <i class="bi bi-x-lg"></i>
        </button>
      </div>


      <div class="px-3 pb-3">
        <a class="btn btn-success w-100 mb-3" href="appointments_add.php">
          <i class="bi bi-plus-circle me-2"></i>เพิ่มการนัดหมาย
        </a>
        <div class="menu small fw-medium text-uppercase mb-2 text-muted">เมนู</div>
        <ul class="nav flex-column gap-1">
          <li class="nav-item"><a class="nav-link active" href="#"><i class="bi bi-speedometer2 me-2"></i>แดชบอร์ด</a>
          </li>

          <li class="nav-item"><a class="nav-link" href="admin.php"><i class="bi bi-people-fill me-2"></i>Admin</a></li>

          <li class="nav-item"><a class="nav-link" href="vet_list.php"><i
                class="bi bi-person-badge me-2"></i>ตารางสัตวแพทย์</a></li>

          <li class="nav-item"><a class="nav-link" href="customer_list.php"><i class="bi bi-people me-2"></i>ลูกค้า</a></li>

          <li class="nav-item"><a class="nav-link" href="pets.php"><i class="bi bi-people me-2"></i>สัตว์เลี้ยงทั้งหมด</a></li>

          <li class="nav-item"><a class="nav-link" href="appointments.php"><i
                class="bi bi-calendar-week me-2"></i>ปฏิทินนัดหมาย</a></li>

          <li class="nav-item"><a class="nav-link" href="grooming_bookings.php"><i
                class="bi bi-scissors me-2"></i>ปฏิทินอาบน้ำตัดขน</a></li>

          <li class="nav-item"><a class="nav-link" href="room_booking.php"><i
                class="bi bi-house-heart me-2"></i>จองคอนโดสัตว์เลี้ยง</a></li>

          <li class="nav-item"><a class="nav-link" href="grooming_packages.php"><i
                class="bi bi-scissors me-2"></i>บริการอาบน้ำตัดขน</a></li>

          <li class="nav-item"><a class="nav-link" href="#"><i class="bi bi-cash-coin me-2"></i>รายงานการเงิน</a></li>

        </ul>
      </div>
    </aside>

    <!-- Main -->
    <main class="flex-grow-1">
      <!-- Topbar -->
      <nav class="navbar border-bottom sticky-top bg-white">
        <div class="container-fluid">
          <div class="fs-5 fw-semibold">
            ยินดีต้อนรับกลับ, <?= htmlspecialchars($admin_name) ?> 🐾
          </div>

          <div class="d-flex align-items-center gap-3">
            <div class="dropdown">
              <a class="d-flex align-items-center gap-2 text-decoration-none" href="#" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle fs-5 text-secondary"></i>
                <span class="fw-medium"><?= htmlspecialchars($admin_name) ?></span>
                <i class="bi bi-caret-down-fill small"></i>
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="admin.php">โปรไฟล์</a></li>
                <li><a class="dropdown-item text-danger" href="logout.php">ออกจากระบบ</a></li>
              </ul>
            </div>

          </div>
        </div>
      </nav>

      <!-- Content -->
      <div class="container-fluid p-4">
        <!-- KPI -->
        <div class="row g-3">
          <div class="col-12 col-sm-6 col-xl-3">
            <div class="card kpi shadow-sm">
              <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                  <div>
                    <div class="text-muted small">ลูกค้าใหม่</div>
                    <div class="fs-4 fw-bold"><?= number_format($newCustomers) ?></div>
                  </div>
                  <div class="kpi-badge bg-kpi-1"><i class="bi bi-person-plus"></i></div>
                </div>
                <small class="text-success"><i class="bi bi-graph-up"></i> เพิ่มขึ้นเดือนนี้</small>
              </div>
            </div>
          </div>

          <div class="col-12 col-sm-6 col-xl-3">
            <div class="card kpi shadow-sm">
              <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                  <div>
                    <div class="text-muted small">คิววันนี้</div>
                    <div class="fs-4 fw-bold"><?= $queueToday ?></div>
                  </div>
                  <div class="kpi-badge bg-kpi-2"><i class="bi bi-calendar2-check"></i></div>
                </div>
                <small class="text-success"><i class="bi bi-graph-up"></i> อัปเดตอัตโนมัติ</small>
              </div>
            </div>
          </div>

          <div class="col-12 col-sm-6 col-xl-3">
            <div class="card kpi shadow-sm">
              <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                  <div>
                    <div class="text-muted small">มูลค่าการจองเดือนนี้</div>
                    <div class="fs-4 fw-bold text-primary">฿ <?= number_format($totalBookingValue ?? 0, 2) ?></div>
                  </div>
                  <div class="kpi-badge bg-kpi-3"><i class="bi bi-clipboard-check"></i></div>
                </div>
                <small class="text-muted">รวมจากคอนโด + อาบน้ำตัดขน (ยังไม่ชำระ)</small>
              </div>
            </div>
          </div>

          <div class="col-12 col-sm-6 col-xl-3">
            <div class="card kpi shadow-sm">
              <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                  <div>
                    <div class="text-muted small">ลูกค้าทั้งหมด</div>
                    <div class="fs-4 fw-bold"><?= number_format($totalCustomers) ?></div>
                  </div>
                  <div class="kpi-badge bg-kpi-4"><i class="bi bi-hearts"></i></div>
                </div>
                <small class="text-success"><i class="bi bi-graph-up"></i> อัปเดตเรียลไทม์</small>
              </div>
            </div>
          </div>
        </div>

        <!-- ตารางผู้ป่วยล่าสุด + กราฟ -->
        <div class="row g-3 mt-1">
          <div class="col-12 col-xl-8">
            <div class="card shadow-sm h-100">
              <div class="card-header bg-white fw-semibold">ผู้ป่วยล่าสุด</div>
              <div class="card-body table-responsive">
                <table class="table align-middle">
                  <thead>
                    <tr>
                      <th>สัตว์เลี้ยง</th>
                      <th>เจ้าของ</th>
                      <th>บริการ</th>
                      <th>วันที่</th>
                      <th>สถานะ</th>
                      <th class="text-end">การทำงาน</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (empty($appointments)): ?>
                      <tr>
                        <td colspan="6" class="text-center text-muted">ไม่มีข้อมูลการนัดหมาย</td>
                      </tr>
                    <?php else: ?>
                      <?php foreach ($appointments as $a): ?>
                        <tr>
                          <td><?= htmlspecialchars($a['pet_name'] ?? '-') ?></td>
                          <td><?= htmlspecialchars($a['owner_name'] ?? '-') ?></td>
                          <td><?= $serviceNames[$a['service_type']] ?? 'อื่น ๆ' ?></td>
                          <td><?= htmlspecialchars($a['date']) ?></td>
                          <td>
                            <?php
                            $statusClass = [
                              'completed' => 'success',
                              'confirmed' => 'primary',
                              'pending' => 'secondary',
                              'cancelled' => 'danger'
                            ][$a['status']] ?? 'light';
                            ?>
                            <span class="badge text-bg-<?= $statusClass ?>">
                              <?= [
                                'completed' => 'เสร็จสิ้น',
                                'confirmed' => 'กำลังดำเนินการ',
                                'pending' => 'รอคิว',
                                'cancelled' => 'ยกเลิก'
                              ][$a['status']] ?? 'ไม่ระบุ' ?>
                            </span>
                          </td>
                          <td class="text-end">
                            <a href="view_appointment.php?id=<?= $a['app_id'] ?>" class="btn btn-sm btn-outline-secondary"
                              title="ดูรายละเอียด"><i class="bi bi-eye"></i></a>
                            <a href="delete_appointment.php?id=<?= $a['app_id'] ?>" class="btn btn-sm btn-outline-danger"
                              onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลนี้?');" title="ลบข้อมูลนี้"><i
                                class="bi bi-trash"></i></a>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <div class="col-12 col-xl-4">
            <div class="card shadow-sm mb-3">
              <div class="card-header bg-white fw-semibold">จำนวนสัตว์เลี้ยง 7 วันล่าสุด</div>
              <div class="card-body">
                <canvas id="petChart" height="220"></canvas>
              </div>
            </div>

            <div class="card shadow-sm">
              <div class="card-header bg-white fw-semibold">รายงานสั้น ๆ</div>
              <div class="card-body">
                <ul class="list-group list-group-flush small">
                  <li class="list-group-item d-flex justify-content-between align-items-center">ตรวจสุขภาพ <span
                      class="badge text-bg-info"><?= $services['health_check'] ?? 0 ?></span></li>
                  <li class="list-group-item d-flex justify-content-between align-items-center">ฉีดวัคซีน <span
                      class="badge text-bg-primary"><?= $services['vaccination'] ?? 0 ?></span></li>
                  <li class="list-group-item d-flex justify-content-between align-items-center">ทำหมัน <span
                      class="badge text-bg-warning"><?= $services['sterilization'] ?? 0 ?></span></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const ctx = document.getElementById('petChart');
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: <?= json_encode(array_column($chartData, 'day')) ?>,
        datasets: [{
          label: 'จำนวนสัตว์เลี้ยงต่อวัน',
          data: <?= json_encode(array_column($chartData, 'total')) ?>,
          borderWidth: 2,
          fill: true
        }]
      }
    });
  </script>
</body>

</html>