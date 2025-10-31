<?php
include '../myadmin/config/db.php'; // เชื่อมต่อฐานข้อมูล

$stmt = $conn->query("
    SELECT 
        u.user_id,
        u.username,
        COUNT(a.app_id) AS total_bookings
    FROM users u
    LEFT JOIN appointments a ON u.user_id = a.user_id
    GROUP BY u.user_id, u.username
    ORDER BY total_bookings DESC
");

$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงานลูกค้าที่ใช้บริการน้อยที่สุด</title>

    <!-- ✅ ใช้ Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            background-color: #f8fafb;
            font-family: 'Prompt', sans-serif;
        }

        .card {
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            background: #fff;
            padding: 2rem;
        }

        h3 {
            color: #198754;
            font-weight: 700;
        }

        .table thead {
            background-color: #198754;
            color: white;
        }

        .btn-back {
            background-color: #198754;
            color: white;
            border-radius: 8px;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-back:hover {
            background-color: #157347;
        }

        .chart-container {
            margin-top: 40px;
        }
    </style>
</head>

<body class="py-4">

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3><i class="bi bi-bar-chart-fill me-2"></i>รายงานลูกค้าที่ใช้บริการน้อยที่สุด</h3>
            <a href="index.php" class="btn btn-back"><i class="bi bi-house-door-fill me-1"></i> กลับหน้าหลัก</a>
        </div>

        <div class="card">
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center align-middle">
                    <thead>
                        <tr>
                            <th style="width: 10%;">ลำดับ</th>
                            <th>ชื่อลูกค้า</th>
                            <th>จำนวนการจอง</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($customers) > 0): ?>
                            <?php $i = 1;
                            foreach ($customers as $c): ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= htmlspecialchars($c['username']) ?></td>
                                    <td>
                                        <span class="badge bg-success fs-6"><?= $c['total_bookings'] ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-muted py-3">ไม่มีข้อมูลลูกค้า</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="chart-container">
                <canvas id="userChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- ✅ JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // เตรียมข้อมูลกราฟจาก PHP
        const labels = <?= json_encode(array_column($customers, 'username')) ?>;
        const data = <?= json_encode(array_column($customers, 'total_bookings')) ?>;

        new Chart(document.getElementById('userChart'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'จำนวนการจอง',
                    data: data,
                    backgroundColor: 'rgba(25, 135, 84, 0.6)',
                    borderColor: '#198754',
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: 'จำนวนการจองของลูกค้าแต่ละคน',
                        font: { size: 18 }
                    }
                },
                scales: {
                    y: { beginAtZero: true, title: { display: true, text: 'จำนวนการจอง' } },
                    x: { title: { display: true, text: 'ชื่อผู้ใช้' } }
                }
            }
        });
    </script>

</body>

</html>