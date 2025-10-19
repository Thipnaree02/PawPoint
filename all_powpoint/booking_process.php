<!DOCTYPE html>
<html lang="th">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ฟอร์มจองคิวตรวจสุขภาพสัตว์เลี้ยง</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@400;500;600&display=swap" rel="stylesheet">

  <style>
    body {
      background: linear-gradient(180deg, #f0fdfa 0%, #ffffff 100%);
      font-family: 'Prompt', sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .booking-card {
      max-width: 650px;
      width: 100%;
      background: #fff;
      padding: 40px;
      border-radius: 25px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
      transition: 0.3s;
    }

    .booking-card:hover {
      transform: translateY(-3px);
    }

    .form-label {
      font-weight: 500;
      color: #333;
    }

    h3 {
      color: #2b7a78;
      font-weight: 600;
      margin-bottom: 20px;
    }

    .btn-confirm {
      background-color: #3fb6a8;
      color: white;
      border-radius: 30px;
      padding: 10px 25px;
      font-size: 17px;
      font-weight: 500;
      border: none;
      transition: 0.3s;
    }

    .btn-confirm:hover {
      background-color: #339c91;
    }

    .btn-cancel {
      background-color: #dc3545;
      color: white;
      border-radius: 30px;
      padding: 10px 25px;
      font-size: 17px;
      font-weight: 500;
      margin-left: 10px;
      text-decoration: none;
      display: inline-block;
      transition: 0.3s;
    }

    .btn-cancel:hover {
      background-color: #b82a38;
      color: white;
    }

    .logo-box {
      text-align: center;
      margin-bottom: 15px;
    }

    .logo-box img {
      width: 100px;
      height: 100px;
      object-fit: contain;
    }

    .header-line {
      width: 60%;
      height: 3px;
      background-color: #a0d9d2;
      margin: 15px auto 25px auto;
      border-radius: 10px;
    }
  </style>
</head>

<body>

  <div class="booking-card">
    <!-- 🔹 โลโก้ร้าน -->
    <div class="logo-box">
      <img src="images/logo.png" alt="โลโก้ร้าน" id="clinicLogo">
    </div>

    <!-- 🔹 หัวข้อ -->
    <h3 class="text-center">จองคิวตรวจสุขภาพสัตว์เลี้ยง</h3>
    <div class="header-line"></div>

    <form action="booking_process.php" method="POST">

      <!-- เลือกประเภทบริการ -->
      <div class="mb-4">
        <label for="service_type" class="form-label">เลือกประเภทบริการ</label>
        <select id="service_type" name="service_type" class="form-select text-center" required>
          <option value="">-- กรุณาเลือกบริการ --</option>
          <option value="health">ตรวจสุขภาพ</option>
          <option value="vaccine">ฉีดวัคซีน</option>
          <option value="surgery">ผ่าตัด / ทำหมัน</option>
          <option value="grooming">อาบน้ำ / ตัดขน</option>
        </select>
      </div>

      <!-- เลือกสัตว์เลี้ยง -->
      <div class="mb-3">
        <label for="pet_name" class="form-label">สัตว์เลี้ยงของคุณ</label>
        <input type="text" class="form-control" id="pet_name" name="pet_name"
          placeholder="เช่น หมาชื่อโบโบ้ / แมวชื่อเหมียวจัง" required>
      </div>

      <!-- วันที่ / เวลา -->
      <div class="mb-3">
        <label for="date" class="form-label">วันที่จอง</label>
        <input type="date" class="form-control" id="date" name="date" required>
      </div>

      <div class="mb-3">
        <label for="time" class="form-label">เวลา</label>
        <input type="time" class="form-control" id="time" name="time" required>
      </div>

      <!-- เลือกหมอ -->
      <div class="mb-3">
        <label for="vet" class="form-label">เลือกสัตวแพทย์ (ไม่บังคับ)</label>
        <select class="form-select" id="vet" name="vet_id">
          <option value="">-- ไม่เลือกหมอเฉพาะ --</option>
          <option value="1">น.สพ. วิชัย ใจดี</option>
          <option value="2">น.สพ. พิชญา รักสัตว์</option>
          <option value="3">น.สพ. ธนกร หัวใจอ่อนโยน</option>
        </select>
      </div>

      <!-- อาการเบื้องต้น -->
      <div class="mb-4">
        <label for="symptom" class="form-label">อาการเบื้องต้น</label>
        <textarea class="form-control" id="symptom" name="symptom" rows="3"
          placeholder="ระบุอาการ เช่น ซึม ไม่กินอาหาร เดินกะเผลก..."></textarea>
      </div>

      <!-- ปุ่ม -->
      <div class="text-center">
        <button type="submit" class="btn btn-confirm">ยืนยันการจอง</button>
        <a href="index.php" class="btn btn-cancel">ยกเลิก</a>
      </div>

    </form>
  </div>

</body>
</html>
