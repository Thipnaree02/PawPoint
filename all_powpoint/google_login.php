<?php
session_start();

require_once 'vendor/autoload.php'; // ต้องติดตั้ง Google Client Library ก่อน (ดูด้านล่าง)

// ตั้งค่าข้อมูลจาก Google Cloud Console
$clientID = 'YOUR_GOOGLE_CLIENT_ID';
$clientSecret = 'YOUR_GOOGLE_CLIENT_SECRET';
$redirectUri = 'http://localhost/google_login.php';

// สร้าง Client Object
$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

// ถ้ายังไม่ได้รับโค้ด (ยังไม่ได้ล็อกอิน)
if (!isset($_GET['code'])) {
    $authUrl = $client->createAuthUrl(); // สร้างลิงก์ไปหน้า Google Login
    header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
    exit();
} else {
    // เมื่อผู้ใช้กดยืนยันจาก Google แล้ว Google จะส่ง "code" กลับมา
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    
    if (!isset($token["error"])) {
        $client->setAccessToken($token['access_token']);
        $google_service = new Google_Service_Oauth2($client);
        $data = $google_service->userinfo->get();

        // เก็บข้อมูลผู้ใช้ลง session
        $_SESSION['user_email'] = $data['email'];
        $_SESSION['user_name']  = $data['name'];
        $_SESSION['user_picture'] = $data['picture'];

        // สามารถเชื่อมต่อฐานข้อมูลเพื่อตรวจสอบหรือบันทึกผู้ใช้ได้ เช่น
        /*
        include('config/db.php');
        $email = $data['email'];
        $name  = $data['name'];
        $check = $conn->query("SELECT * FROM users WHERE email='$email'");
        if ($check->num_rows == 0) {
            $conn->query("INSERT INTO users (name,email,login_type) VALUES ('$name','$email','google')");
        }
        */

        header('Location: dashboard.php'); // ไปยังหน้าหลังล็อกอิน
        exit();
    } else {
        echo "เกิดข้อผิดพลาดในการล็อกอินผ่าน Google!";
        exit();
    }
}
?>
