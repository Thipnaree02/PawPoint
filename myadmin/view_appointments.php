<?php
include 'config/db.php'; // เชื่อมต่อฐานข้อมูล

$search = $_GET['search'] ?? ''; // ถ้าไม่มีค่า search ให้กำหนดว่างไว้

$stmt = $conn->query("SELECT * FROM appointments ORDER BY app_id DESC");
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>รายการนัดหมายทั้งหมด</title>
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
    </style>
</head>

<body>
       <?php include 'sidebar.php'; ?>



    <!-- Main -->
    <main class="main">
        <nav class="navbar navbar-custom d-flex justify-content-between align-items-center mb-4">
            <h4 class="m-0 fw-bold">รายการนัดหมาย</h4>
            <form method="get" class="d-flex align-items-center" style="gap:8px;">
                <input type="text" name="search" class="form-control" placeholder="ค้นหา"
                    value="<?= htmlspecialchars($search) ?>" style="width:260px;">
                <button class="btn btn-outline-success" type="submit"><i class="bi bi-search"></i></button>
            </form>
        </nav>

        <table class="table table-bordered table-striped">
            <thead class="table-success text-center">
                <tr>
                    <th>ID</th>
                    <th>ผู้ใช้</th>
                    <th>สัตว์เลี้ยง</th>
                    <th>หมอ</th>
                    <th>บริการ</th>
                    <th>วันที่</th>
                    <th>เวลา</th>
                    <th>สถานะ</th>
                    <th>หมายเหตุ</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($appointments)): ?>
                    <tr>
                        <td colspan="9" class="text-center text-muted">ไม่มีข้อมูลนัดหมาย</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($appointments as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['app_id']) ?></td>
                            <td><?= htmlspecialchars($row['user_id']) ?></td>
                            <td><?= htmlspecialchars($row['pet_id']) ?></td>
                            <td><?= htmlspecialchars($row['vet_id']) ?></td>
                            <td><?= htmlspecialchars($row['service_type']) ?></td>
                            <td><?= htmlspecialchars($row['date']) ?></td>
                            <td><?= htmlspecialchars($row['time']) ?></td>
                            <td><?= htmlspecialchars($row['status']) ?></td>
                            <td><?= htmlspecialchars($row['note']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        </div>
</body>

</html>