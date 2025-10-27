<?php
session_start();
require_once '../myadmin/config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// 🧼 1. ประวัติอาบน้ำ / ตัดขน
$stmt1 = $conn->prepare("
  SELECT 
    'อาบน้ำ / ตัดขน' AS service_type, 
    gb.booking_date, 
    gb.booking_time, 
    gp.name_th AS detail, 
    gp.price, 
    gb.status, 
    gb.note
  FROM grooming_bookings gb
  LEFT JOIN grooming_packages gp ON gb.package_id = gp.id
  WHERE gb.user_id = ?
");

// 🩺 2. ประวัตินัดหมายบริการสัตวแพทย์
$stmt2 = $conn->prepare("
  SELECT 
    a.service_type AS raw_service_type,
    CASE 
      WHEN a.service_type = 'health_check' THEN 'ตรวจสุขภาพ'
      WHEN a.service_type = 'vaccination' THEN 'ฉีดวัคซีน'
      WHEN a.service_type = 'sterilization' THEN 'ทำหมัน'
      ELSE a.service_type
    END AS service_type, 
    a.date AS booking_date, 
    a.time AS booking_time, 
    v.vet_name AS detail, 
    NULL AS price,  
    a.status, 
    a.note
  FROM appointments a
  LEFT JOIN vets v ON a.vet_id = v.vet_id
  WHERE a.user_id = ?
  ORDER BY a.date DESC, a.time DESC
");



// 🏨 3. ประวัติการฝากเลี้ยง
$stmt3 = $conn->prepare("
  SELECT 
    'ฝากเลี้ยง' AS service_type, 
    r.checkin_date AS booking_date, 
    r.checkout_date AS booking_time, 
    rm.name AS detail, 
    r.total_price AS price, 
    r.status, 
    NULL AS note
  FROM room_booking r
  LEFT JOIN room_type rm ON r.room_type_id = rm.id
  WHERE r.user_id = ?
");

// ✅ รันคำสั่งทั้งหมด
$stmt1->execute([$user_id]);
$stmt2->execute([$user_id]);
$stmt3->execute([$user_id]);

// ✅ รวมผลลัพธ์ทั้งหมดเข้าด้วยกัน
$history = array_merge(
    $stmt1->fetchAll(PDO::FETCH_ASSOC),
    $stmt2->fetchAll(PDO::FETCH_ASSOC),
    $stmt3->fetchAll(PDO::FETCH_ASSOC)
);

// ✅ เรียงข้อมูลจากวันที่ล่าสุดก่อน
usort($history, function ($a, $b) {
    $datetimeA = strtotime($a['booking_date'] . ' ' . ($a['booking_time'] ?? '00:00'));
    $datetimeB = strtotime($b['booking_date'] . ' ' . ($b['booking_time'] ?? '00:00'));
    return $datetimeB <=> $datetimeA;
});
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>ประวัติการใช้บริการของฉัน | PawPoint</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #f8faf9;
            font-family: 'Noto Sans Thai', sans-serif;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.05);
            border: none;
        }

        h3 {
            color: #198754;
            font-weight: 700;
        }

        .table th {
            background-color: #198754;
            color: white;
            text-align: center;
        }

        .table td {
            text-align: center;
            vertical-align: middle;
        }

        .status-badge {
            border-radius: 30px;
            padding: 5px 15px;
            font-weight: 600;
            display: inline-block;
        }

        .pending {
            background: #fff3cd;
            color: #856404;
        }

        .confirmed {
            background: #cce5ff;
            color: #004085;
        }

        .completed {
            background: #d4edda;
            color: #155724;
        }

        .cancelled {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
            <h3><i class="bi bi-clock-history"></i> ประวัติการใช้บริการของฉัน</h3>
            <a href="index.php" class="btn btn-outline-success">
                <i class="bi bi-arrow-left-circle"></i> กลับหน้าหลัก
            </a>
        </div>

        <?php if (empty($history)): ?>
            <div class="alert alert-info text-center">
                <i class="bi bi-info-circle"></i> ยังไม่มีประวัติการใช้บริการ
            </div>
        <?php else: ?>
            <div class="card p-4">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ประเภทบริการ</th>
                                <th>วันที่</th>
                                <th>เวลา / วันที่ออก</th>
                                <th>รายละเอียด</th>
                                <th>ราคา</th>
                                <th>สถานะ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1;
                            foreach ($history as $h):
                                $status = $h['status'] ?? 'pending';
                                $map = [
                                    'pending' => ['🕒 รอดำเนินการ', 'pending'],
                                    'confirmed' => ['✅ ยืนยันแล้ว', 'confirmed'],
                                    'completed' => ['🐾 เสร็จสิ้น', 'completed'],
                                    'cancelled' => ['❌ ยกเลิก', 'cancelled']
                                ];
                                ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= htmlspecialchars($h['service_type']) ?></td>
                                    <td><?= htmlspecialchars($h['booking_date']) ?></td>
                                    <td><?= htmlspecialchars($h['booking_time']) ?></td>
                                    <td><?= htmlspecialchars($h['detail']) ?></td>
                                    <td><?= number_format($h['price'] ?? 0, 0) ?> ฿</td>
                                    <td><span class="status-badge <?= $map[$status][1] ?>"><?= $map[$status][0] ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>