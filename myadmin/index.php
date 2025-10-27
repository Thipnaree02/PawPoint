<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit;
}
include 'config/db.php';
?>


<!doctype html>
<html lang="th">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Elivet Admin — แดชบอร์ดคลินิกสัตว์เลี้ยง</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/css/styles.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300;400;500;600;700&display=swap"
    rel="stylesheet">
</head>

<body>
  <div class="d-flex">
    <!-- Sidebar -->
    <aside id="sidebar" class="sidebar border-end">
      <div class="p-3 d-flex align-items-center gap-2 brand">
        <div class="brand-badge"><i class="bi bi-crosshair2"></i></div>
        <div>
          <div class="fw-bold">ELIVET</div>
          <small class="text-muted">Pet Clinic Admin</small>
        </div>
        <button class="btn btn-light btn-sm ms-auto d-lg-none" id="btnCloseSidebar">
          <i class="bi bi-x-lg"></i>
        </button>
      </div>
      <div class="px-3 pb-3">
        <a class="btn btn-success w-100 mb-3" href="#"><i class="bi bi-plus-circle me-2"></i>เพิ่มการนัดหมาย</a>
        <div class="menu small fw-medium text-uppercase mb-2 text-muted">เมนู</div>
        <ul class="nav flex-column gap-1">
          <li class="nav-item"><a class="nav-link active" href="#"><i class="bi bi-speedometer2 me-2"></i>แดชบอร์ด</a>
          </li>
          <li class="nav-item"><a class="nav-link " href="admin.php"><i class="bi bi-people-fill me-2"></i>Admin</a>
          </li>
          <li class="nav-item"><a class="nav-link " href="vet_list.php"><i
                class="bi bi-people-fill me-2"></i>ตารางสัตวแพทย์</a></li>
          <li class="nav-item"><a class="nav-link" href="customer_list.php"><i class="bi bi-people me-2"></i>ลูกค้า</a>
          </li>
          <li class="nav-item"><a class="nav-link" href="appointments.php"><i
                class="bi bi-calendar-week me-2"></i>ปฏิทินนัดหมาย</a> </li>
          <li class="nav-item"><a class="nav-link" href="room_booking.php"><i class="bi bi-bandaid me-2"></i>บริการ &
              แพ็คเกจ</a></li>
          <li class="nav-item"><a class="nav-link" href="grooming_packages.php"><i
                class="bi bi-clipboard2-check me-2"></i>บริการอาบน้ำตัดขน</a></li>
          <li class="nav-item"><a class="nav-link" href="#"><i class="bi bi-cash-coin me-2"></i>รายงานการเงิน</a></li>
          <li class="nav-item"><a class="nav-link" href="#"><i class="bi bi-gear me-2"></i>ตั้งค่า</a></li>
        </ul>
        <div class="p-3 mt-4 rounded-3 help-box">
          <div class="d-flex align-items-center gap-2">
            <img src="assets/img/vet.png" class="rounded-circle flex-shrink-0" width="44" height="44" alt="vet">
            <div>
              <div class="fw-semibold">พร้อมช่วยเหลือ</div>
              <small class="text-muted">แชทหาพนักงานได้ทันที</small>
            </div>
          </div>
        </div>
      </div>
    </aside>

    <!-- Main -->
    <main class="flex-grow-1">
      <!-- Topbar -->
      <nav class="navbar border-bottom sticky-top bg-white">
        <div class="container-fluid">
          <div class="d-flex align-items-center gap-2">
            <button class="btn btn-outline-secondary d-lg-none" id="btnOpenSidebar"><i class="bi bi-list"></i></button>
            <div class="fs-5 fw-semibold">ยินดีต้อนรับกลับ, เจนนี่ 🐾</div>
          </div>
          <div class="d-flex align-items-center gap-3">
            <div class="input-group d-none d-md-flex" style="max-width: 320px;">
              <span class="input-group-text bg-transparent"><i class="bi bi-search"></i></span>
              <input class="form-control border-start-0" type="search" placeholder="ค้นหา...">
            </div>
            <button class="btn btn-light position-relative">
              <i class="bi bi-bell"></i>
              <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span>
            </button>
            <div class="dropdown">
              <a class="d-flex align-items-center gap-2 text-decoration-none" href="#" data-bs-toggle="dropdown">
                <img src="https://i.pravatar.cc/40?img=5" class="rounded-circle" width="36" height="36" alt="user">
                <span class="fw-medium d-none d-sm-inline">Jenny Teach</span>
                <i class="bi bi-caret-down-fill small"></i>
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="#">โปรไฟล์</a></li>
                <li><a class="dropdown-item" href="#">สลับบทบาท</a></li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item text-danger" href="logout.php">ออกจากระบบ</a>

              </ul>
            </div>
          </div>
        </div>
      </nav>

      <div class="container-fluid p-4">
        <?php
        include 'config/db.php'; // ✅ เพิ่มตรงนี้ เพื่อให้ $conn ใช้งานได้
        
        // ✅ ดึงข้อมูลจากฐานข้อมูล
        
        $stmtIndex = $conn->query("
  SELECT a.pet_name, a.service_type, a.date, a.status, v.vet_name, u.username AS owner_name
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

        // 3️⃣ นับจำนวนตามประเภทบริการ
        $stmtService = $conn->query("
    SELECT service_type, COUNT(*) AS count 
    FROM appointments 
    GROUP BY service_type
  ");
        $services = $stmtService->fetchAll(PDO::FETCH_KEY_PAIR);

        // จัด format ให้ตรงกับชื่อไทย
        $serviceNames = [
          'health_check' => 'ตรวจสุขภาพ',
          'vaccination' => 'ฉีดวัคซีน',
          'sterilization' => 'ผ่าตัด/ทำหมัน'
        ];
        ?>

        <!-- KPI Cards -->
        <div class="row g-3">
          <div class="col-12 col-sm-6 col-xl-3">
            <div class="card kpi shadow-sm">
              <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                  <div>
                    <div class="text-muted small">ลูกค้าใหม่</div>
                    <div class="fs-4 fw-bold">8434</div>
                  </div>
                  <div class="kpi-badge bg-kpi-1"><i class="bi bi-person-plus"></i></div>
                </div>
                <small class="text-success"><i class="bi bi-graph-up"></i> +12% จากเดือนก่อน</small>
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
                <small class="text-success"><i class="bi bi-graph-up"></i> +5% สัปดาห์นี้</small>
              </div>
            </div>
          </div>

          <div class="col-12 col-sm-6 col-xl-3">
            <div class="card kpi shadow-sm">
              <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                  <div>
                    <div class="text-muted small">รายได้เดือนนี้</div>
                    <div class="fs-4 fw-bold">฿ 12,463</div>
                  </div>
                  <div class="kpi-badge bg-kpi-3"><i class="bi bi-cash-stack"></i></div>
                </div>
                <small class="text-muted">อัพเดตอัตโนมัติทุกคืน</small>
              </div>
            </div>
          </div>

          <div class="col-12 col-sm-6 col-xl-3">
            <div class="card kpi shadow-sm">
              <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                  <div>
                    <div class="text-muted small">ลูกค้าประจำ</div>
                    <div class="fs-4 fw-bold">6428</div>
                  </div>
                  <div class="kpi-badge bg-kpi-4"><i class="bi bi-hearts"></i></div>
                </div>
                <small class="text-success"><i class="bi bi-graph-up"></i> +2.1% QoQ</small>
              </div>
            </div>
          </div>
        </div>

        <div class="row g-3 mt-1">
          <!-- Latest Patients -->
          <div class="col-12 col-xl-8">
            <div class="card shadow-sm h-100">
              <div class="card-header bg-white d-flex align-items-center justify-content-between">
                <div class="fw-semibold">ผู้ป่วยล่าสุด</div>
                <div class="d-flex gap-2">
                  <input type="search" class="form-control form-control-sm" placeholder="ค้นหา...">
                  <button class="btn btn-sm btn-outline-primary"><i class="bi bi-download"></i> ส่งออก</button>
                </div>
              </div>
              <div class="card-body table-responsive">
                <table class="table align-middle" id="tblPatients">
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
                          <td><?= htmlspecialchars($a['pet_name']) ?></td>
                          <td><?= htmlspecialchars($a['owner_name'] ?? '-') ?></td>
                          <td>
                            <?= $serviceNames[$a['service_type']] ?? 'อื่น ๆ' ?>
                          </td>
                          <td><?= htmlspecialchars($a['date']) ?></td>
                          <td>
                            <?php
                            switch ($a['status']) {
                              case 'completed':
                                echo '<span class="badge text-bg-success">เสร็จสิ้น</span>';
                                break;
                              case 'confirmed':
                                echo '<span class="badge text-bg-primary">กำลังดำเนินการ</span>';
                                break;
                              case 'pending':
                                echo '<span class="badge text-bg-secondary">รอคิว</span>';
                                break;
                              case 'cancelled':
                                echo '<span class="badge text-bg-danger">ยกเลิก</span>';
                                break;
                              default:
                                echo '<span class="badge text-bg-light text-dark">ไม่ระบุ</span>';
                            }
                            ?>
                          </td>
                          <td class="text-end">
                            <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></button>
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Today Chart & Reports -->
          <div class="col-12 col-xl-4">
            <div class="card shadow-sm mb-3">
              <div class="card-header bg-white fw-semibold">จำนวนสัตว์เลี้ยงวันนี้</div>
              <div class="card-body">
                <canvas id="todayChart" height="220"></canvas>
              </div>
            </div>

            <div class="card shadow-sm">
              <div class="card-header bg-white fw-semibold">รายงานสั้น ๆ</div>
              <div class="card-body">
                <ul class="list-group list-group-flush small">
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    นัดตรวจสุขภาพ <span class="badge text-bg-info"><?= $services['health_check'] ?? 0 ?></span>
                  </li>
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    ฉีดวัคซีน <span class="badge text-bg-primary"><?= $services['vaccination'] ?? 0 ?></span>
                  </li>
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    ผ่าตัด/ทำหมัน <span class="badge text-bg-warning"><?= $services['sterilization'] ?? 0 ?></span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>


      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
      <script src="assets/js/app.js"></script>
</body>

</html>