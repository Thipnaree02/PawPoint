<?php
require_once '../config/db.php';
require_once './_auth.php';

$id = (int) ($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT gb.*, gp.name_th AS package_name, gp.price
                        FROM grooming_bookings gb
                        JOIN grooming_packages gp ON gp.id = gb.package_id
                        WHERE gb.id=?");
$stmt->execute([$id]);
$bk = $stmt->fetch();
if (!$bk) {
    exit('ไม่พบรายการ');
}

// อัปโหลดรูป
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_photo'])) {
    $dir = __DIR__ . '/uploads/grooming/';
    if (!is_dir($dir))
        mkdir($dir, 0777, true);

    $fields = ['before_photo' => 'before', 'after_photo' => 'after'];
    $updates = [];
    foreach ($fields as $col => $prefix) {
        if (!empty($_FILES[$col]['name'])) {
            $ext = pathinfo($_FILES[$col]['name'], PATHINFO_EXTENSION);
            $fname = $prefix . "_{$id}_" . time() . "." . strtolower($ext);
            $dest = $dir . $fname;
            if (move_uploaded_file($_FILES[$col]['tmp_name'], $dest)) {
                $updates[$col] = "uploads/grooming/" . $fname; // path สำหรับแสดงผล
            }
        }
    }
    if ($updates) {
        $set = [];
        $vals = [];
        foreach ($updates as $c => $v) {
            $set[] = "$c=?";
            $vals[] = $v;
        }
        $vals[] = $id;
        $sql = "UPDATE grooming_bookings SET " . implode(',', $set) . ", updated_at=NOW() WHERE id=?";
        $u = $conn->prepare($sql)->execute($vals);
    }
    header("Location: grooming_booking_view.php?id=" . $id . "&ok=1");
    exit;
}
?>
<!doctype html>
<html lang="th">

<head>
    <meta charset="utf-8">
    <title>รายละเอียดคิว #<?= $bk['id'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-light">
    <div class="container py-5">
        <h3 class="mb-4">รายละเอียดคิว #<?= $bk['id'] ?></h3>

        <?php if (isset($_GET['ok'])): ?>
            <script>Swal.fire({ icon: 'success', title: 'อัปโหลดแล้ว', timer: 1200, showConfirmButton: false });</script>
        <?php endif; ?>

        <div class="card p-3 mb-4">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>แพ็กเกจ:</strong> <?= $bk['package_name'] ?> (<?= number_format($bk['price'], 0) ?> บาท)</p>
                    <p><strong>วันที่/เวลา:</strong> <?= $bk['booking_date'] ?> <?= $bk['booking_time'] ?></p>
                    <p><strong>สถานะ:</strong> <?= $bk['status'] ?></p>
                    <p><strong>หมายเหตุ:</strong> <?= nl2br(htmlspecialchars($bk['note'])) ?></p>
                </div>
                <div class="col-md-6">
                    <form method="post" enctype="multipart/form-data" class="row g-3">
                        <h5>อัปโหลดรูปก่อน-หลัง</h5>
                        <div class="col-12">
                            <label class="form-label">ก่อนทำ (before)</label>
                            <input type="file" name="before_photo" accept="image/*" class="form-control">
                            <?php if ($bk['before_photo']): ?>
                                <img src="<?= $bk['before_photo'] ?>" class="img-fluid rounded mt-2" style="max-height:200px">
                            <?php endif; ?>
                        </div>
                        <div class="col-12">
                            <label class="form-label">หลังทำ (after)</label>
                            <input type="file" name="after_photo" accept="image/*" class="form-control">
                            <?php if ($bk['after_photo']): ?>
                                <img src="<?= $bk['after_photo'] ?>" class="img-fluid rounded mt-2" style="max-height:200px">
                            <?php endif; ?>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary" name="upload_photo">บันทึกรูป</button>
                            <a href="grooming_bookings.php" class="btn btn-outline-secondary">กลับ</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</body>

</html>