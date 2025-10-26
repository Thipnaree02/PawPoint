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
          <li class="nav-item"><a class="nav-link " href="admin.php"><i class="bi bi-people-fill me-2"></i>Admin</a></li>
          <li class="nav-item"><a class="nav-link " href="vet_list.php"><i class="bi bi-people-fill me-2"></i>ตารางสัตวแพทย์</a></li>
          <li class="nav-item"><a class="nav-link" href="customer_list.php"><i class="bi bi-people me-2"></i>ลูกค้า/สัตว์เลี้ยง</a></li>
          <li class="nav-item"><a class="nav-link" href="#"><i class="bi bi-calendar-week me-2"></i>ปฏิทินนัดหมาย</a>
          </li>
          <li class="nav-item"><a class="nav-link" href="room_booking.php"><i class="bi bi-bandaid me-2"></i>บริการ & แพ็คเกจ</a></li>
          <li class="nav-item"><a class="nav-link" href="#"><i
                class="bi bi-clipboard2-check me-2"></i>คำขอ/คิววันนี้</a></li>
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
                <li><a class="dropdown-item text-danger" href="#">ออกจากระบบ</a></li>
              </ul>
            </div>
          </div>
        </div>
      </nav>

      <!-- Content -->
      <div class="container-fluid p-4">
        <!-- KPI Cards -->
        <div class="row g-3">
          <div class="col-12 col-sm-6 col-xl-3">
            <div class="card kpi shadow-sm">
              <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                  <div>
                    <div class="text-muted small">ลูกค้าใหม่</div>
                    <div class="fs-4 fw-bold" id="kpiCustomers">8434</div>
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
                    <div class="fs-4 fw-bold" id="kpiQueue">38</div>
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
                    <!-- แถวตัวอย่าง จะถูกแทนที่ด้วย JS หากเชื่อม API -->
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
                    นัดตรวจวัคซีน <span class="badge text-bg-primary">12</span>
                  </li>
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    อาบน้ำตัดขน <span class="badge text-bg-success">9</span>
                  </li>
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    ผ่าตัด/ทำหมัน <span class="badge text-bg-warning">3</span>
                  </li>
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    เคสฉุกเฉิน <span class="badge text-bg-danger">2</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
  <script src="assets/js/app.js"></script>
</body>

</html>