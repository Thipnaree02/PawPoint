<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<aside class="sidebar">
    <div class="brand">
        <i class="bi bi-hospital"></i> Elivet
    </div>

    <a href="index.php" class="<?= $current_page == 'index.php' ? 'active' : '' ?>">
        <i class="bi bi-house-door me-2"></i> กลับสู่หน้าหลัก
    </a>

    <a href="admin.php" class="<?= $current_page == 'admin.php' ? 'active' : '' ?>">
        <i class="bi bi-person-gear me-2"></i>ผู้ดูแลระบบ
    </a>

    <a href="appointments.php" class="<?= $current_page == 'appointments.php' ? 'active' : '' ?>">
        <i class="bi bi-calendar-week me-2"></i> ตารางนัดหมาย
    </a>

    <a href="grooming_bookings.php" class="<?= $current_page == 'grooming_bookings.php' ? 'active' : '' ?>">
        <i class="bi bi-calendar-check me-2"></i> ตารางอาบน้ำตัดขน
    </a>

    <a href="vet_list.php" class="<?= $current_page == 'vet_list.php' ? 'active' : '' ?>">
        <i class="bi bi-person-badge me-2"></i> ตารางสัตวแพทย์
    </a>

    <a href="room_booking.php" class="<?= $current_page == 'room_booking.php' ? 'active' : '' ?>">
        <i class="bi bi-door-open me-2"></i> การจองห้องพัก
    </a>

    <a href="grooming_packages.php" class="<?= $current_page == 'grooming_packages.php' ? 'active' : '' ?>">
        <i class="bi bi-scissors me-2"></i> เพิ่มบริการอาบน้ำตัดขน
    </a>

</aside>


<style>
    .sidebar a.active {
        background: #c5f2ce;
        /* เขียวอ่อน */
        color: #145a23;
        font-weight: 600;
        border-left: 4px solid #2c7a3d;
    }

    .sidebar a:hover {
        background: #e9f8ea;
        color: #1d5e28;
    }

    .sidebar {
        width: 260px;
        background: #fff;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        border-right: 1px solid #eaeaea;
        padding: 1.5rem 1rem;
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

    .sidebar a {
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

    .sidebar a:hover,
    .sidebar a.active {
        background: #e9f8ea;
        color: #1d5e28;
    }

    .main-content {
        margin-left: 260px;
        padding: 2rem;
        flex: 1;
    }
</style>