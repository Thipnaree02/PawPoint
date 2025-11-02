-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 02, 2025 at 06:50 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_website`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` enum('Manager','Staff') DEFAULT 'Staff',
  `profile_image` varchar(255) DEFAULT 'default_admin.png',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `fullname`, `username`, `password`, `email`, `phone`, `role`, `profile_image`, `created_at`) VALUES
(3, 'ทิพย์นารี เพตาเสน', 'Thipnaree', '$2y$10$1rnhEwWv2ZRttn8mV6XGue3tmqdPLt..TBSaGMJDW8HxVWYE1wb0y', '65010914602@msu.ac.th', '0652961246', 'Staff', '1761733746_thipnaree.jpg', '2025-10-26 07:38:15');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `app_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pet_name` varchar(100) DEFAULT NULL,
  `vet_id` int(11) NOT NULL,
  `service_type` enum('health_check','vaccination','sterilization') NOT NULL DEFAULT 'health_check',
  `date` date NOT NULL,
  `time` time NOT NULL,
  `status` enum('pending','confirmed','cancelled','completed') DEFAULT 'pending',
  `price` decimal(10,2) DEFAULT 0.00,
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`app_id`, `user_id`, `pet_name`, `vet_id`, `service_type`, `date`, `time`, `status`, `price`, `note`, `created_at`) VALUES
(1, 3, 'มาคัส', 1, 'health_check', '2025-10-28', '10:30:00', 'pending', 0.00, '', '2025-10-27 15:25:15'),
(3, 3, 'สีหมอก', 2, 'vaccination', '2025-10-29', '10:40:00', 'pending', 0.00, '', '2025-10-27 15:26:31'),
(4, 3, 'นปโป๊ะ', 5, 'sterilization', '2025-10-29', '23:30:00', 'confirmed', 0.00, '', '2025-10-27 15:30:04'),
(5, 13, 'ชูใจ', 1, 'vaccination', '2025-11-02', '10:20:00', 'completed', 0.00, '', '2025-10-30 20:18:20'),
(7, 8, 'นานะ', 1, 'health_check', '2025-11-01', '15:35:00', 'completed', 0.00, '', '2025-10-31 07:34:28');

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `id` int(11) NOT NULL,
  `owner_name` varchar(100) NOT NULL,
  `pet_name` varchar(100) NOT NULL,
  `room_id` int(11) NOT NULL,
  `checkin` date NOT NULL,
  `checkout` date NOT NULL,
  `days` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`id`, `owner_name`, `pet_name`, `room_id`, `checkin`, `checkout`, `days`, `total_price`, `created_at`) VALUES
(1, 'otto', 'นปโป๊ะ', 3, '2025-10-22', '2025-10-24', 3, 3000.00, '2025-10-21 17:33:45'),
(2, 'ตันหยง', 'โตโต้', 2, '2025-10-23', '2025-10-24', 2, 1200.00, '2025-10-21 17:35:00'),
(3, 'ชวิน', 'หมี', 1, '2025-10-23', '2025-10-23', 1, 350.00, '2025-10-21 17:45:19');

-- --------------------------------------------------------

--
-- Table structure for table `grooming_bookings`
--

CREATE TABLE `grooming_bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `pet_id` int(11) DEFAULT NULL,
  `package_id` int(11) NOT NULL,
  `booking_date` date NOT NULL,
  `booking_time` time NOT NULL,
  `note` text DEFAULT NULL,
  `status` enum('pending','confirmed','completed','cancelled','no_show') DEFAULT 'pending',
  `before_photo` varchar(255) DEFAULT NULL,
  `after_photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grooming_bookings`
--

INSERT INTO `grooming_bookings` (`id`, `user_id`, `pet_id`, `package_id`, `booking_date`, `booking_time`, `note`, `status`, `before_photo`, `after_photo`, `created_at`, `updated_at`) VALUES
(1, 3, NULL, 4, '2025-10-28', '09:50:00', '', 'completed', NULL, NULL, '2025-10-27 09:47:47', NULL),
(2, 3, NULL, 3, '2025-10-28', '22:30:00', '', 'confirmed', NULL, NULL, '2025-10-27 13:27:13', NULL),
(3, 3, NULL, 4, '2025-10-30', '11:40:00', '', 'pending', NULL, NULL, '2025-10-28 15:38:17', NULL),
(4, 3, NULL, 4, '2025-11-01', '12:10:00', '', 'pending', NULL, NULL, '2025-10-28 16:08:48', NULL),
(5, 13, NULL, 3, '2025-11-01', '10:20:00', '', 'confirmed', NULL, NULL, '2025-10-30 20:17:38', NULL),
(6, 8, NULL, 4, '2025-11-01', '15:35:00', '', 'confirmed', NULL, NULL, '2025-10-31 07:35:15', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `grooming_packages`
--

CREATE TABLE `grooming_packages` (
  `id` int(11) NOT NULL,
  `name_th` varchar(100) NOT NULL,
  `description_th` text DEFAULT NULL,
  `weight_min` decimal(5,2) DEFAULT 0.00,
  `weight_max` decimal(5,2) DEFAULT 0.00,
  `price` decimal(10,2) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grooming_packages`
--

INSERT INTO `grooming_packages` (`id`, `name_th`, `description_th`, `weight_min`, `weight_max`, `price`, `is_active`, `created_at`) VALUES
(1, 'แพ็กเกจเล็ก', 'สำหรับสัตว์เลี้ยงไม่เกิน 5 กก. | อาบน้ำ ตัดขน พ่นน้ำหอม', 0.00, 5.00, 250.00, 1, '2025-10-27 07:31:18'),
(2, 'แพ็กเกจกลาง', 'สำหรับสัตว์เลี้ยง 5–15 กก. | อาบน้ำ ตัดขน เคลือบขน', 5.00, 15.00, 350.00, 1, '2025-10-27 07:31:18'),
(3, 'แพ็กเกจใหญ่', 'สำหรับสัตว์เลี้ยง 15–30 กก. | อาบน้ำ ตัดขน ตัดเล็บ แปรงฟัน', 15.00, 30.00, 450.00, 1, '2025-10-27 07:31:18'),
(4, 'แพ็กเกจสปาเพิ่ม', 'อาบน้ำ + สปาขน + อาบน้ำสมุนไพร + กลิ่นหอมพิเศษ', 0.00, 0.00, 600.00, 1, '2025-10-27 07:31:18');

-- --------------------------------------------------------

--
-- Table structure for table `pets`
--

CREATE TABLE `pets` (
  `pet_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pet_name` varchar(100) DEFAULT NULL,
  `species` varchar(50) DEFAULT NULL,
  `breed` varchar(100) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `age` int(11) NOT NULL,
  `note` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pets`
--

INSERT INTO `pets` (`pet_id`, `user_id`, `pet_name`, `species`, `breed`, `gender`, `age`, `note`) VALUES
(3, 3, 'มาคัส', 'สุนัข', 'โกลเด้น', 'ตัวผู้', 2, ''),
(4, 13, 'ชูใจ', 'แมว', 'เปอร์เซีย', 'ตัวผู้', 1, 'ขี้กลัว'),
(5, 8, 'โตโต้', 'สุนัข', 'ผสมชิวาวา', 'ตัวผู้', 7, ''),
(6, 8, 'คอฟฟี่', 'สุนัข', 'ผสมชิวาวา', 'ตัวผู้', 10, ''),
(7, 8, 'ก๋วยเตี๋ยว', 'สุนัข', 'ไม่แน่ใจ', 'ตัวผู้', 2, '');

-- --------------------------------------------------------

--
-- Table structure for table `room_booking`
--

CREATE TABLE `room_booking` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `pet_name` varchar(100) DEFAULT NULL,
  `room_type_id` int(11) DEFAULT NULL,
  `checkin_date` date DEFAULT NULL,
  `checkout_date` date DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','confirmed','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room_booking`
--

INSERT INTO `room_booking` (`id`, `user_id`, `pet_name`, `room_type_id`, `checkin_date`, `checkout_date`, `total_price`, `status`, `created_at`) VALUES
(2, 7, 'สีหมอก', 3, '2025-10-28', '2025-11-01', 4000.00, 'completed', '2025-10-27 16:38:36'),
(3, 7, 'นปโป๊ะ', 1, '2025-10-28', '2025-10-29', 350.00, 'completed', '2025-10-27 16:43:15'),
(4, 7, 'นปโป๊ะ', 1, '2025-10-28', '2025-10-29', 350.00, 'confirmed', '2025-10-27 16:43:18'),
(5, 7, 'นปโป๊ะ', 1, '2025-10-28', '2025-10-29', 350.00, 'completed', '2025-10-27 16:44:06'),
(6, 7, 'นปโป๊ะ', 1, '2025-10-28', '2025-10-29', 350.00, 'confirmed', '2025-10-27 16:44:09'),
(7, 7, 'นปโป๊ะ', 1, '2025-10-28', '2025-10-29', 350.00, 'confirmed', '2025-10-27 16:47:01'),
(8, 7, 'นปโป๊ะ', 1, '2025-10-28', '2025-10-29', 350.00, 'pending', '2025-10-27 16:47:05'),
(9, 7, 'ชิบะ', 2, '2025-10-28', '2025-10-30', 1200.00, 'pending', '2025-10-27 16:48:31'),
(10, 7, 'ชิบะ', 2, '2025-10-28', '2025-10-30', 1200.00, 'pending', '2025-10-27 16:49:39'),
(11, 7, 'ชิบะ', 1, '2025-10-28', '2025-10-30', 700.00, 'pending', '2025-10-27 16:53:39'),
(12, 3, 'มาคัส', 2, '2025-10-29', '2025-10-31', 1200.00, 'pending', '2025-10-28 15:33:38'),
(13, 3, 'ตังเม', 1, '2025-11-04', '2025-11-07', 1050.00, 'pending', '2025-10-28 16:09:21'),
(14, 13, 'ชูใจ', 3, '2025-11-01', '2025-11-02', 1000.00, 'pending', '2025-10-30 20:17:07'),
(15, 8, 'โตโต้', 3, '2025-11-01', '2025-11-03', 2000.00, 'confirmed', '2025-10-31 03:14:52');

-- --------------------------------------------------------

--
-- Table structure for table `room_type`
--

CREATE TABLE `room_type` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price_night` decimal(10,2) NOT NULL DEFAULT 0.00,
  `price_week` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room_type`
--

INSERT INTO `room_type` (`id`, `name`, `description`, `price_night`, `price_week`) VALUES
(1, 'Standard Room', 'ห้องพักขนาดกะทัดรัด เหมาะสำหรับสัตว์เลี้ยงขนาดเล็ก-กลาง', 350.00, 2100.00),
(2, 'Deluxe Room', 'ห้องพักกว้างขวาง มีของเล่นและมุมปีนป่าย เหมาะกับสัตว์เลี้ยงพลังเยอะ', 600.00, 3800.00),
(3, 'VIP Suite', 'ห้องพักระดับพรีเมียม พร้อมกล้องวงจรปิดและดูแลใกล้ชิด', 1000.00, 6000.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `phone`, `address`, `created_at`, `status`) VALUES
(3, 'จุฑามาศ', '$2y$10$c4dO2AkSv2kneb4cbYtvtOKJtg7p2DLrc21Wb3wDHw0dJLQvPdP4C', '65010914608@msu.ac.th', '0651246565', '', '2025-10-26 17:46:37', 'active'),
(4, 'ชวิน', '$2y$10$cYL2zKO5rCEIAQqM7LrKB.CL8bsQBeF4X0.i2tNcW3dPtKj/ieKvm', '65010914626@msu.ac.th', '', '', '2025-10-26 17:52:45', 'active'),
(6, 'tonyong', '$2y$10$R3DHo11PtvEPO1gtcL.MjOPvQsj2FY1u7HjSdqKxErWFJflSsJcy2', 'thipnaree.ee1234@gmail.com', '', '', '2025-10-27 08:34:59', 'active'),
(7, 'ภาคภูมิ', '$2y$10$QuEIsjkdHZ7QMp1/M1CuJOTI7k8kTfvYvuei1lIk1CAudZ3x31p5.', '65010914606@msu.ac.th', '', '', '2025-10-27 16:38:06', 'active'),
(8, 'Thipnaree', '$2y$10$laEyVOjISo6czylK1WRSbOzqJYRKeZoXut6GotxUhl.SLiy4lU286', 'thipnaree.ee@gmail.com', '0652961246', '502 หมู่ 1 บ้านท่าขอนยาง วอยโคกหนองไผ่ 1 ต.ท่าขอนยาง อ.กันทรวิชัย จ.มหาสารคาม 44150', '2025-10-29 16:15:09', 'active'),
(9, 'otto', '$2y$10$Rc2Sj6FvAElxF4dAwDfqVOZXFkWfleP9cs48ZsaOXzACwhRxScfVq', 'otto@gmail.com', '', '', '2025-10-30 19:39:33', 'active'),
(10, 'กิตติยาพร', '$2y$10$CIV3ofOCMDSecaEZw.UIZ.ERAL/pZpnVcqu2nMXRyFxSxYlOAfUNK', 'giti@gmail.com', '', '', '2025-10-30 19:53:44', 'active'),
(11, 'oatthaphon', '$2y$10$WwxNMmCALXTLXayj5EwxHOhD.lI8Vsqg.EfCCDXwcXVc3Vz3j74fy', 'oat@gmail.com', '', '', '2025-10-30 20:02:29', 'active'),
(12, 'green', '$2y$10$lv8QohvDxvX0MzpqCxJ1t.bFHWFW3WLByktinAowYdrggL4FcmztK', 'green@as.com', '', '', '2025-10-30 20:08:14', 'active'),
(13, 'เกริกพล', '$2y$10$g/uxo5egIXz5u0Fc6E7NQuzsN8/7l0C34dlx/jg1yO/LvI7G3DlbO', 'get@gmail.com', '', '', '2025-10-30 20:15:18', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `veterinarians`
--

CREATE TABLE `veterinarians` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `specialization` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `experience_years` int(11) DEFAULT 0,
  `working_days` varchar(100) DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `photo` varchar(255) DEFAULT 'default_vet.png',
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `veterinarians`
--

INSERT INTO `veterinarians` (`id`, `fullname`, `specialization`, `phone`, `email`, `experience_years`, `working_days`, `start_time`, `end_time`, `photo`, `note`, `created_at`) VALUES
(1, 'น.สพ. ธีรพงศ์ ศรีสวัสดิ์', 'ศัลยกรรมทั่วไป', '0812345678', 'theerapong@elivet.com', 6, 'จันทร์-ศุกร์', '09:00:00', '17:00:00', 'vet1.jpg', 'เชี่ยวชาญการผ่าตัดสุนัขและแมวทุกขนาด', '2025-10-26 15:34:55'),
(2, 'น.สพ. วิภาดา พรหมแก้ว', 'ทำหมัน', '0823456789', 'wipada@elivet.com', 4, 'พุธ-อาทิตย์', '10:00:00', '18:00:00', 'vet2.jpg', 'ให้คำปรึกษาด้านสุขภาพทั่วไปและการดูแลสัตว์เลี้ยง', '2025-10-26 15:34:55'),
(3, 'น.สพ. ภูวเดช คำทอง', 'ทำหมัน', '0834567890', 'phuwadet@elivet.com', 5, 'อังคาร-เสาร์', '08:30:00', '16:30:00', 'vet3.jpg', 'ผู้เชี่ยวชาญด้านโรคผิวหนังและการแพ้ในสัตว์เลี้ยง', '2025-10-26 15:34:55'),
(4, 'น.สพ. ธนพร ใจดี', 'ฉีดวัคซีน', '0845678901', 'thanaporn@elivet.com', 3, 'จันทร์-ศุกร์', '09:30:00', '17:30:00', 'vet4.jpg', 'ดูแลช่องปากและฟันของสุนัขและแมว', '2025-10-26 15:34:55'),
(5, 'น.สพ. ปริญญา ศรีมงคล', 'ตรวจสุขภาพ', '0856789012', 'parinya@elivet.com', 8, 'พฤหัสบดี-อาทิตย์', '08:00:00', '15:30:00', 'vet5.jpg', 'ดูแลและรักษาปลาสวยงามและสัตว์น้ำเลี้ยง', '2025-10-26 15:34:55'),
(7, 'อรรถพล ชื่นบาน', 'ตรวจสุขภาพ', '0823009309', '65011310195@msu.ac.th', 4, 'จันทร์ อังคาร', '08:00:00', '16:30:00', '1761845497_baner-right-image-02.jpg', '', '2025-10-30 17:31:37');

-- --------------------------------------------------------

--
-- Table structure for table `vets`
--

CREATE TABLE `vets` (
  `vet_id` int(11) NOT NULL,
  `vet_name` varchar(100) DEFAULT NULL,
  `specialty` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vets`
--

INSERT INTO `vets` (`vet_id`, `vet_name`, `specialty`) VALUES
(1, 'น.สพ. ปริญญา ศรีมงคล', 'ผ่าตัด/ทำหมัน'),
(2, 'น.สพ. ธนพร ใจดี', 'ฉีดวัคซีน'),
(3, 'น.สพ. ภูวเดช คำทอง', 'ผ่าตัด/ทำหมัน'),
(4, 'น.สพ. วิภาดา พรหมแก้ว', 'ตรวจสุขภาพ'),
(5, 'น.สพ. ธีรพงศ์ ศรีสวัสดิ์', 'ศัลยกรรมทั่วไป');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`app_id`),
  ADD KEY `fk_vet` (`vet_id`),
  ADD KEY `fk_user` (`user_id`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `grooming_bookings`
--
ALTER TABLE `grooming_bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_gb_package` (`package_id`);

--
-- Indexes for table `grooming_packages`
--
ALTER TABLE `grooming_packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pets`
--
ALTER TABLE `pets`
  ADD PRIMARY KEY (`pet_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `room_booking`
--
ALTER TABLE `room_booking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_type_id` (`room_type_id`);

--
-- Indexes for table `room_type`
--
ALTER TABLE `room_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `veterinarians`
--
ALTER TABLE `veterinarians`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vets`
--
ALTER TABLE `vets`
  ADD PRIMARY KEY (`vet_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `app_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `grooming_bookings`
--
ALTER TABLE `grooming_bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `grooming_packages`
--
ALTER TABLE `grooming_packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pets`
--
ALTER TABLE `pets`
  MODIFY `pet_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `room_booking`
--
ALTER TABLE `room_booking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `room_type`
--
ALTER TABLE `room_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `veterinarians`
--
ALTER TABLE `veterinarians`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `vets`
--
ALTER TABLE `vets`
  MODIFY `vet_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_vet` FOREIGN KEY (`vet_id`) REFERENCES `vets` (`vet_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `room_type` (`id`);

--
-- Constraints for table `grooming_bookings`
--
ALTER TABLE `grooming_bookings`
  ADD CONSTRAINT `fk_gb_package` FOREIGN KEY (`package_id`) REFERENCES `grooming_packages` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `pets`
--
ALTER TABLE `pets`
  ADD CONSTRAINT `pets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `room_booking`
--
ALTER TABLE `room_booking`
  ADD CONSTRAINT `room_booking_ibfk_1` FOREIGN KEY (`room_type_id`) REFERENCES `room_type` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
