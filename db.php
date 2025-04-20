<?php
// ข้อมูลการเชื่อมต่อ
$host = 'localhost';       // หรือ 127.0.0.1
$dbname = 'monks';      // ชื่อฐานข้อมูลของคุณ
$username = 'root';         // ชื่อผู้ใช้ฐานข้อมูล
$password = '';             // รหัสผ่าน (เช่น ถ้า XAMPP ปกติจะเว้นว่าง)

// ตั้งค่า options สำหรับ PDO
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // ให้แสดง error แบบ exception
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // เวลา fetch จะได้ array แบบชื่อคอลัมน์
    PDO::ATTR_EMULATE_PREPARES => false, // ปิดการจำลอง prepared statements
];

try {
    // สร้างการเชื่อมต่อ
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, $options);
    // ถ้าเชื่อมต่อสำเร็จ สามารถใช้ $pdo ได้เลย
} catch (PDOException $e) {
    // ถ้าเชื่อมต่อไม่สำเร็จ ให้แสดง error
    die('การเชื่อมต่อฐานข้อมูลล้มเหลว: ' . $e->getMessage());
}
?>
