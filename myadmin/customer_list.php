<?php
session_start();

// ถ้ายังไม่มี session แสดงว่ายังไม่ล็อกอิน
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

include '../myadmin/config/db.php';

// ถ้ามีการกดลบ (Soft Delete)
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmtCustomerList = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmtCustomerList->execute([$delete_id]);

    echo "<script>
        alert('ลบข้อมูลลูกค้าสำเร็จแล้ว!');
        window.location.href = 'customer_list.php';
    </script>";
    exit();
}

// ดึงข้อมูลลูกค้าที่ status = active เท่านั้น
$stmtCustomerList = $conn->query("
    SELECT user_id, username, email, phone, address 
    FROM users 
    WHERE (status = 'active' OR status IS NULL)
    ORDER BY user_id ASC
");

$customers = $stmtCustomerList->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลลูกค้า | Elivet Admin</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background-color: #f8fafb;
            font-family: 'Noto Sans Thai', sans-serif;
        }

        .main-content {
            margin-left: 260px;
            padding: 2rem;
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

        .card {
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        h3 {
            color: #198754;
            font-weight: 700;
        }

        .btn-danger {
            background-color: #dc3545;
            border: none;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        /* ปรับคอลัมน์ให้สวยขึ้น */
        .table td,
        .table th {
            vertical-align: middle;
            text-align: center;
            word-wrap: break-word;
            white-space: normal;
        }

        /* กำหนดความกว้างเฉพาะคอลัมน์ */
        .table th:nth-child(2),
        .table td:nth-child(2) {
            width: 15%;
        }

        .table th:nth-child(3),
        .table td:nth-child(3) {
            width: 20%;
        }

        .table th:nth-child(4),
        .table td:nth-child(4) {
            width: 15%;
        }

        .table th:nth-child(5),
        .table td:nth-child(5) {
            width: 30%;
            text-align: left;
            padding-left: 10px;
            padding-right: 10px;

            /* ✅ ปรับให้แสดงที่อยู่ครบ ไม่ตัดข้อความ */
            white-space: pre-wrap;
            word-wrap: break-word;
            overflow-wrap: break-word;
            max-width: 400px;
        }

        .table th:nth-child(6),
        .table td:nth-child(6) {
            width: 10%;
        }
    </style>
</head>

<body>
    <?php include 'sidebar.php'; ?>

    <!-- Main content -->
    <div class="main-content">
        <h3 class="mb-3">ข้อมูลลูกค้า</h3>

        <div class="card p-3">
            <table class="table table-bordered table-hover align-middle">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>ชื่อผู้ใช้</th>
                        <th>อีเมล</th>
                        <th>เบอร์โทรศัพท์</th>
                        <th>ที่อยู่</th>
                        <th>การจัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($customers) > 0): ?>
                        <?php $i = 1;
                        foreach ($customers as $c): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= htmlspecialchars($c['username'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($c['email'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($c['phone'] ?? '-') ?></td>
                                <td><?= nl2br(htmlspecialchars($c['address'] ?? '-')) ?></td>
                                <td>
                                    <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $c['user_id'] ?>)">
                                        <i class='bi bi-trash'></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">ไม่มีข้อมูลลูกค้าในระบบ</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- สคริปต์ยืนยันก่อนลบ -->
    <script>
        function confirmDelete(userId) {
            Swal.fire({
                title: 'แน่ใจหรือไม่?',
                text: "คุณต้องการลบข้อมูลลูกค้าคนนี้ใช่หรือไม่?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'ใช่, ลบเลย!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'customer_list.php?delete_id=' + userId;
                }
            });
        }
    </script>
</body>

</html>
