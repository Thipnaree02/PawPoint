<?php

session_start();

// ถ้ายังไม่มี session แสดงว่ายังไม่ล็อกอิน
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

include 'config/db.php';

// ป้องกัน Warning ตัวแปร $search ยังไม่ได้ประกาศ
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// ดึงข้อมูลสัตวแพทย์
if ($search != '') {
    $stmt = $conn->prepare("SELECT * FROM veterinarians 
                          WHERE fullname LIKE :s 
                          OR phone LIKE :s 
                          OR email LIKE :s 
                          ORDER BY id DESC");
    $stmt->execute(['s' => "%$search%"]);
} else {
    $stmt = $conn->query("SELECT * FROM veterinarians ORDER BY id DESC");
}
$vets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>จัดการสัตวแพทย์ | Elivet Admin</title>
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
        }

        .sidebar {
            width: 260px;
            background: #fff;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            border-right: 1px solid #eaeaea;
            padding: 1rem;
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
            background: #8bdc65;
            padding: 8px;
            border-radius: 10px;
            color: #1c461a;
            margin-right: 8px;
        }

        .sidebar a {
            display: block;
            color: #444;
            text-decoration: none;
            padding: .6rem .9rem;
            border-radius: 8px;
            margin-bottom: 4px;
            font-weight: 500;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #e9f8ea;
            color: #1d5e28;
        }

        .main {
            margin-left: 260px;
            padding: 2rem;
        }

        .navbar-custom {
            background: #fff;
            border-bottom: 1px solid #eee;
            padding: .8rem 1.2rem;
        }

        .card {
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        .btn-success {
            border-radius: 8px;
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

        .rounded-img {
            border-radius: 50%;
            object-fit: cover;
            width: 50px;
            height: 50px;
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


    <!-- Main Content -->
    <main class="main">
        <nav class="navbar navbar-custom d-flex justify-content-between align-items-center mb-4">
            <h4 class="m-0 fw-bold">จัดการสัตวแพทย์</h4>

            <form method="get" class="d-flex align-items-center" style="gap:8px;">
                <input type="text" name="search" class="form-control" placeholder="ค้นหาชื่อ / อีเมล / เบอร์โทร..."
                    value="<?= htmlspecialchars($search) ?>" style="width:220px;">
                <button class="btn btn-outline-success" type="submit"><i class="bi bi-search"></i></button>
                <a href="admin_add_vet.php" class="btn btn-success">
                    <i class="bi bi-plus-lg"></i> เพิ่มสัตวแพทย์ใหม่
                </a>
            </form>
        </nav>

        <!-- ตารางสัตวแพทย์ -->
        <div class="card p-3">
            <table class="table table-bordered table-striped align-middle">
                <thead>
                    <tr>
                        <th style="width:60px;">No.</th>
                        <th>รูปภาพ</th>
                        <th>ชื่อ-นามสกุล</th>
                        <th>สาขาความเชี่ยวชาญ</th>
                        <th>เบอร์โทร</th>
                        <th>วันทำงาน</th>
                        <th>เวลา</th>
                        <th style="width:120px;">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    if (count($vets) > 0) {
                        foreach ($vets as $row) { ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><img src="uploads/vets/<?= htmlspecialchars($row['photo']); ?>" class="rounded-img border">
                                </td>
                                <td><?= htmlspecialchars($row['fullname']); ?></td>
                                <td><?= htmlspecialchars($row['specialization']); ?></td>
                                <td><?= htmlspecialchars($row['phone']); ?></td>
                                <td><?= htmlspecialchars($row['working_days']); ?></td>
                                <td><?= htmlspecialchars($row['start_time'] . " - " . $row['end_time']); ?></td>
                                <td>
                                    <a href="edit_vet.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <a href="delete_vet.php?id=<?= $row['id']; ?>" class="btn btn-danger btn-sm"
                                        onclick="return confirm('ลบข้อมูลนี้หรือไม่?')">
                                        <i class="bi bi-trash3-fill"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php }
                    } else { ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted">ยังไม่มีข้อมูลสัตวแพทย์</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </main>
</body>

</html>