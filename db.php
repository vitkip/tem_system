<?php
// ຂໍ້ມູນການເຊື່ອມຕໍ່
$host = 'localhost';        // ຫຼື 127.0.0.1
$dbname = 'monks';          // ຊື່ຖານຂໍ້ມູນຂອງເຈົ້າ
$username = 'root';         // ຊື່ຜູ້ໃຊ້ຖານຂໍ້ມູນ
$password = '';             // ລະຫັດຜ່ານ (ຖ້າແມ່ນ XAMPP ປົກກະຕິຈະເປັນຄ່າຫວ່າງ)

// ກຳນົດ BASE_URL ສຳລັບໃຊ້ໃນລະບົບ
define('BASE_URL', 'http://localhost/tem_system/');

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // ໃຫ້ສະແດງ error ແບບ exception
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // fetch ເປັນ array ທີ່ໃຊ້ຊື່ column
    PDO::ATTR_EMULATE_PREPARES => false, // ປິດການຈໍາລອງ prepared statement
];

try {
    // ສ້າງການເຊື່ອມຕໍ່
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, $options);
    // ຖ້າເຊື່ອມສໍາເລັດ ສາມາດໃຊ້ $pdo ໄດ້ເລີຍ
} catch (PDOException $e) {
    // ຖ້າເຊື່ອມບໍ່ສໍາເລັດ ໃຫ້ສະແດງ error
    die('ເຊື່ອມຕໍ່ຖານຂໍ້ມູນລົ້ມເຫຼວ: ' . $e->getMessage());
}
?>
