<?php
require 'db.php';
include 'header.php';
// นับงานทั้งหมด
$stmt = $pdo->query("SELECT COUNT(*) FROM events");
$total_events = $stmt->fetchColumn();

// งานที่เสร็จแล้ว (วันที่ผ่านไปแล้ว)
$stmt = $pdo->query("SELECT COUNT(*) FROM events WHERE event_date < CURDATE()");
$past_events = $stmt->fetchColumn();

// งานที่กำลังจะมาถึง (7 วันนับจากวันนี้)
$stmt = $pdo->query("SELECT COUNT(*) FROM events WHERE event_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)");
$upcoming_events = $stmt->fetchColumn();

// เตรียมข้อมูลทำกราฟ
$chartData = [];
for ($month = 1; $month <= 12; $month++) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM events WHERE MONTH(event_date) = ? AND YEAR(event_date) = YEAR(CURDATE())");
    $stmt->execute([$month]);
    $chartData[] = (int) $stmt->fetchColumn();
}

// ดึงสรุปข้อมูล
$countMonk = $pdo->query("SELECT COUNT(*) FROM monks WHERE prefix = 'ພຣະ'")->fetchColumn();
$countNun = $pdo->query("SELECT COUNT(*) FROM monks WHERE prefix = 'ຄຸນແມ່ຂາວ'")->fetchColumn();
$countNovice = $pdo->query("SELECT COUNT(*) FROM monks WHERE prefix = 'ສ.ນ'")->fetchColumn();
$countSangkhali = $pdo->query("SELECT COUNT(*) FROM monks WHERE prefix = 'ສັງກະລີ'")->fetchColumn();
?>

<div class="max-w-7xl mx-auto p-6 space-y-8">

    <!-- Header + ปุ่มเพิ่ม -->
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-indigo-700">ໜ້າ Dashboard</h1>
        <?php if (isAdmin()): ?>
        <a href="add_monk.php" class="inline-flex items-center bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
            ➕ ເພີ່ມພຣະ
        </a>
        <?php endif; ?>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-indigo-100 p-6 rounded-lg shadow hover:scale-105 transform transition">
            <div class="flex items-center space-x-4">
                <i class="fas fa-user fa-2x text-indigo-700"></i>
                <div>
                    <p class="text-2xl font-bold"><?= $countMonk ?></p>
                    <p class="text-gray-600">ຈໍານວນພຣະ</p>
                </div>
            </div>
        </div>
        <div class="bg-green-100 p-6 rounded-lg shadow hover:scale-105 transform transition">
            <div class="flex items-center space-x-4">
                <i class="fas fa-female fa-2x text-green-700"></i>
                <div>
                    <p class="text-2xl font-bold"><?= $countNun ?></p>
                    <p class="text-gray-600">ຈໍານວນແມ່ຂາວ</p>
                </div>
            </div>
        </div>
        <div class="bg-yellow-100 p-6 rounded-lg shadow hover:scale-105 transform transition">
            <div class="flex items-center space-x-4">
                <i class="fas fa-child fa-2x text-yellow-700"></i>
                <div>
                    <p class="text-2xl font-bold"><?= $countNovice ?></p>
                    <p class="text-gray-600">ຈໍານວນເນນ</p>
                </div>
            </div>
        </div>
        <div class="bg-pink-100 p-6 rounded-lg shadow hover:scale-105 transform transition">
            <div class="flex items-center space-x-4">
                <i class="fas fa-users fa-2x text-pink-700"></i>
                <div>
                    <p class="text-2xl font-bold"><?= $countSangkhali ?></p>
                    <p class="text-gray-600">ສັງກະລີ</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div id="barChart" class="bg-white p-6 rounded-lg shadow"></div>
        <div id="pieChart" class="bg-white p-6 rounded-lg shadow"></div>
    </div>

    <!-- กิจกรรม -->
    <div class="bg-white p-6 rounded-lg shadow">

        <div class="text-center text-gray-500 py-10">
        <h1 class="text-3xl font-bold text-indigo-700 mb-6">Dashboard ງານກິດນິມນຕ໌</h1>

<!-- การ์ดสรุปสถิติ -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-blue-100 p-6 rounded-lg shadow text-center">
        <div class="text-4xl font-bold text-blue-700"><?= $total_events ?></div>
        <div class="mt-2 text-gray-600">ຈຳນວນງານທັງໝົດ</div>
    </div>
    <div class="bg-green-100 p-6 rounded-lg shadow text-center">
        <div class="text-4xl font-bold text-green-700"><?= $past_events ?></div>
        <div class="mt-2 text-gray-600">ງານທີ່ຜ່ານແລ້ວ</div>
    </div>
    <div class="bg-yellow-100 p-6 rounded-lg shadow text-center">
        <div class="text-4xl font-bold text-yellow-700"><?= $upcoming_events ?></div>
        <div class="mt-2 text-gray-600">ງານໃໝ່ (7 ມື້ໜ້າ)</div>
    </div>
</div>

<!-- กราฟแสดงสถิติตามเดือน -->
<div id="eventChart" class="bg-white p-6 rounded-lg shadow mt-8"></div>
        </div>
    </div>

</div>

<!-- Chart Script -->
<script src="https://code.highcharts.com/highcharts.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    Highcharts.chart('barChart', {
        chart: { type: 'column', animation: true },
        title: { text: 'ຂໍ້ມູນພຣະສົງຕາມປະເພດ' },
        xAxis: { categories: ['ພຣະ', 'ແມ່ຂາວ', 'ເນນ', 'ສັງກະລີ'] },
        yAxis: { min: 0, title: { text: 'ຈໍານວນ' }},
        series: [{
            name: 'ຈໍານວນ',
            data: [<?= $countMonk ?>, <?= $countNun ?>, <?= $countNovice ?>, <?= $countSangkhali ?>]
        }]
    });

    Highcharts.chart('pieChart', {
        chart: { type: 'pie', animation: true },
        title: { text: 'ສັດສ່ວນພຣະສົງ' },
        series: [{
            name: 'ຈໍານວນ',
            colorByPoint: true,
            data: [
                { name: 'ພຣະ', y: <?= $countMonk ?> },
                { name: 'ແມ່ຂາວ', y: <?= $countNun ?> },
                { name: 'ເນນ', y: <?= $countNovice ?> },
                { name: 'ສັງກະລີ', y: <?= $countSangkhali ?> }
            ]
        }]
    });

    Highcharts.chart('eventChart', {
        chart: { type: 'column' },
        title: { text: 'ສະຖິຕິຈຳນວນງານຕໍ່ເດືອນ (ໃນປີນີ້)' },
        xAxis: {
            categories: ['ມ.ກ.', 'ກ.ພ.', 'ມ.ນ.', 'ເມ.ສ.', 'ພ.ພ.', 'ມິ.ຖ.', 'ກ.ລ.', 'ສ.ຫ.', 'ກ.ຍ.', 'ຕ.ລ.', 'ພ.ຈ.', 'ທ.ວ.']
        },
        yAxis: {
            title: { text: 'ຈຳນວນງານ' }
        },
        series: [{
            name: 'ຈຳນວນງານ',
            data: <?= json_encode($chartData) ?>
        }]
    });
});

</script>

<?php include 'footer.php'; ?>
