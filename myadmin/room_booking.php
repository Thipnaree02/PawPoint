<?php
include 'config/db.php';

// ✅ ฟังก์ชันป้องกัน htmlspecialchars() null
function safe($value)
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

// ดึงข้อมูลการจอง
$stmt = $conn->query("
  SELECT rb.*, u.username, rt.name AS room_name
  FROM room_booking rb
  LEFT JOIN users u ON rb.user_id = u.user_id
  LEFT JOIN room_type rt ON rb.room_type_id = rt.id
  ORDER BY rb.id DESC
");
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายการจองห้องพัก | Elivet Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        * {
            font-family: 'Noto Sans Thai', sans-serif;
        }

        body {
            background: #f7f9fb;
            display: flex;
            height: 100vh;
            overflow-x: hidden;
        }

        /* ✅ Sidebar */
        .sidebar {
            width: 260px;
            background: #ffffff;
            border-right: 1px solid #eaeaea;
            padding: 1.5rem 1rem;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
        }

        .sidebar .brand {
            display: flex;
            align-items: center;
            font-weight: 600;
            font-size: 20px;
            color: #2c7a3d;
            margin-bottom: 2rem;
        }

        .sidebar .brand i {
            background: #b6e8a3;
            padding: 8px;
            border-radius: 10px;
            color: #1c461a;
            margin-right: 8px;
        }

        .sidebar .menu a {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #444;
            text-decoration: none;
            padding: 0.6rem 0.9rem;
            border-radius: 8px;
            margin-bottom: 6px;
            font-weight: 500;
            transition: 0.2s;
        }

        .sidebar .menu a:hover,
        .sidebar .menu a.active {
            background: #e9f8ea;
            color: #1d5e28;
        }

        /* ✅ Main Content */
        .main-content {
            margin-left: 260px;
            padding: 2rem;
            flex: 1;
            overflow-y: auto;
        }

        .table th {
            background-color: #198754 !important;
            color: white;
            text-align: center;
        }

        .table td {
            text-align: center;
            vertical-align: middle;
        }

        .card {
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        .search-box input {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 6px 10px;
            width: 250px;
        }

        .btn-edit {
            background-color: #ffc107;
            color: white;
            border: none;
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
            border: none;
        }

        .btn-edit:hover,
        .btn-delete:hover {
            opacity: 0.85;
        }

        .badge-status {
            font-size: 0.9em;
            padding: 6px 10px;
            border-radius: 10px;
            cursor: pointer;
        }

        .badge-pending {
            background-color: #17a2b8;
            color: white;
        }

        .badge-confirmed {
            background-color: #28a745;
            color: white;
        }

        .badge-cancelled {
            background-color: #dc3545;
            color: white;
        }

        .badge-completed {
            background-color: #6c757d;
            color: white;
        }

        .sidebar a.active {
            background-color: #d1f3d1;
            /* เขียวอ่อน */
            color: #1c6e2a;
            font-weight: 600;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background-color: #e8f9e8;
            color: #155d27;
        }
    </style>
</head>

<body>
   <?php include 'sidebar.php'; ?>

    <!-- ✅ Main Content -->
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-success">รายการจองห้องพัก PawPoint Condo</h3>
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="ค้นหาชื่อผู้ใช้ / สัตว์เลี้ยง / ห้องพัก...">
                <button class="btn btn-success"><i class="bi bi-search"></i></button>
            </div>
        </div>

        <div class="card p-3">
            <table class="table table-bordered table-hover align-middle" id="bookingTable">
                <thead>
                    <tr class="text-center">
                        <th>No.</th>
                        <th>ผู้ใช้</th>
                        <th>สัตว์เลี้ยง</th>
                        <th>ห้องพัก</th>
                        <th>เช็คอิน</th>
                        <th>เช็คเอาท์</th>
                        <th>ราคา</th>
                        <th>สถานะ</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1;
                    foreach ($bookings as $b): ?>
                        <tr class="text-center">
                            <td><?= $i++ ?></td>
                            <td><?= safe($b['username'] ?? '-') ?></td>
                            <td><?= safe($b['pet_name']) ?></td>
                            <td><?= safe($b['room_name']) ?></td>
                            <td><?= safe($b['checkin_date']) ?></td>
                            <td><?= safe($b['checkout_date']) ?></td>
                            <td>฿<?= number_format($b['total_price'] ?? 0, 2) ?></td>
                            <td>
                                <span class="badge-status badge-<?= safe($b['status']) ?>" data-id="<?= $b['id'] ?>"
                                    data-status="<?= safe($b['status']) ?>">
                                    <?= ucfirst($b['status'] ?? 'pending') ?>
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-edit btn-sm"><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-delete btn-sm" onclick="deleteBooking(<?= $b['id'] ?>)"><i
                                        class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // ✅ ค้นหา
        document.getElementById('searchInput').addEventListener('keyup', function () {
            const value = this.value.toLowerCase();
            document.querySelectorAll('#bookingTable tbody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(value) ? '' : 'none';
            });
        });

        // ✅ ลบข้อมูล
        function deleteBooking(id) {
            Swal.fire({
                title: 'ยืนยันการลบ?',
                text: 'ข้อมูลนี้จะถูกลบออกจากระบบถาวร',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'ลบเลย',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('delete_booking.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `id=${id}`
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('ลบสำเร็จ!', '', 'success').then(() => location.reload());
                            } else {
                                Swal.fire('เกิดข้อผิดพลาด', data.message, 'error');
                            }
                        });
                }
            });
        }
    </script>
</body>

</html>