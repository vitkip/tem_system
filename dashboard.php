<?php
require 'db.php';
include 'header.php';


// รวมจำนวนพระ / แม่ชี / สามเณร / สังฆะลี
$countMonk = $pdo->query("SELECT COUNT(*) FROM monks WHERE prefix = 'ພຣະ'")->fetchColumn();
$countNun = $pdo->query("SELECT COUNT(*) FROM monks WHERE prefix = 'ຄຸນແມ່ຂາວ'")->fetchColumn();
$countNovice = $pdo->query("SELECT COUNT(*) FROM monks WHERE prefix = 'ສ.ນ'")->fetchColumn();
//$countSangkhali = $pdo->query("SELECT COUNT(*) FROM monks WHERE prefix = 'ສັງກະລີ'")->fetchColumn();
$total_monk_all = $countMonk + $countNun + $countNovice;

// SQL
$stmt = $pdo->query("
     SELECT m.id, CONCAT(m.prefix, ' ', m.first_name, ' ', m.last_name) AS monk_name, COUNT(em.event_id) AS event_count
    FROM monks m
    LEFT JOIN event_monk em ON m.id = em.monk_id
    GROUP BY m.id
    ORDER BY event_count DESC
");

$monkStats = $stmt->fetchAll();

$monkData = []; // 👈 สำหรับ JS
foreach ($monkStats as $row) {
    $monkData[] = [
        'name' => $row['monk_name'],
        'y' => (int)$row['event_count'],
        'url' => BASE_URL . "view_monk.php?id=" . $row['id']
    ];
}


// เตรียมข้อมูลสำหรับกราฟ
//$monkNames = [];
$monkCounts = [];

foreach ($monkStats as $row) {
    $monkNames[] = $row['monk_name'];
    $monkCounts[] = (int)$row['event_count'];
}

//ນັບຈຳນວນພຣະສົງໃນລາຍງານ
$data = $pdo->query("
    SELECT e.event_name, COUNT(am.monk_id) AS monk_count
    FROM event_monk am
    JOIN events e ON am.event_id = e.id
    GROUP BY e.event_name
    ORDER BY monk_count DESC
    LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);

$categories = array_column($data, 'event_name');
$monkCounts = array_column($data, 'monk_count');
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
// หา Event ภายใน 3 วัน
$stmt = $pdo->prepare("SELECT * FROM events WHERE event_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 DAY) ORDER BY event_date ASC");
$stmt->execute();
$upcoming_events = $stmt->fetchAll();



// ดึงสรุปข้อมูล
$countMonk = $pdo->query("SELECT COUNT(*) FROM monks WHERE prefix = 'ພຣະ'")->fetchColumn();
$countNun = $pdo->query("SELECT COUNT(*) FROM monks WHERE prefix = 'ຄຸນແມ່ຂາວ'")->fetchColumn();
$countNovice = $pdo->query("SELECT COUNT(*) FROM monks WHERE prefix = 'ສ.ນ'")->fetchColumn();
$countSangkhali = $pdo->query("SELECT COUNT(*) FROM monks WHERE prefix = 'ສັງກະລີ'")->fetchColumn();
?>

<div class="max-w-7xl mx-auto p-6 space-y-8">

    <!-- Header + ปุ่มเพิ่ม -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-indigo-700">ໜ້າສະຫຼຸບລາຍງານ|ແດງກຣາບ|ງານກິດນິມນ|ແຈ້ງເຕືອນ|</h1>
        <?php if (isAdmin()): ?>
        <a href="<?= BASE_URL ?>add_monk.php" class="inline-flex items-center bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
            ➕ ເພີ່ມ
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
                    <p class="text-gray-600">ຈໍານວນ ສາມະເນນ</p>
                </div>
            </div>
        </div>
        <div class="bg-pink-100 p-6 rounded-lg shadow hover:scale-105 transform transition">
            <div class="flex items-center space-x-4">
                <i class="fas fa-users fa-2x text-pink-700"></i>
                
                <div>
                    <p class="text-2xl font-bold"><?= $total_monk_all ?></p>
                    <p class="text-gray-600">ຈໍານວນພຣະ|ແມ່ຂາວ|ສາມະເນນ</p>
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
        <h1 class="text-2xl font-bold text-indigo-700 mb-6">ສະແດງ ງານກິດນິມນ</h1>

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
    <?php if (!empty($upcoming_events)): ?>
<div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6">
    <p class="font-bold mb-2">ແຈ້ງເຕືອນ: ງານທີ່ຈະມາເຖິງມໍ່ໆນີ້</p>
    <hr>
    <ul class="list-disc ml-5">
        <?php foreach ($upcoming_events as $event): ?>
            <li><?= htmlspecialchars($event['event_name']) ?> ວັນທີ <?= htmlspecialchars($event['event_date']) ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>
       
    </div>
</div>

<!-- กราฟแสดงสถิติตามเดือน -->
<div id="eventChart" class="bg-white p-6 rounded-lg shadow mt-8"></div>
<div id="monkPerEventChart" class="bg-white p-6 rounded-lg shadow"></div>
<div id="monkActivityChart" class="bg-white p-6 rounded-lg shadow mt-8"></div>
        </div>
    </div>

</div>

<!-- Chart Script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    Highcharts.chart('barChart', {
        chart: { type: 'column', animation: true },
        title: { text: 'ຂໍ້ມູນພຣະສົງຕາມປະເພດ' },
        xAxis: { categories: ['ພຣະ', 'ແມ່ຂາວ', 'ສາມະເນນ', 'ສັງກະລີ'] },
        yAxis: { min: 0, title: { text: 'ຈໍານວນ' }},
        series: [{
            name: 'ຈໍານວນ',
            data: [<?= $countMonk ?>, <?= $countNun ?>, <?= $countNovice ?>, <?= $countSangkhali ?>],
            colorByPoint: true,
            data: [
                { name: 'ພຣະ', y: <?= $countMonk ?>, color: '#6366F1' },
                { name: 'ແມ່ຂາວ', y: <?= $countNun ?>, color: '#10B981' },
                { name: 'ສາມະເນນ', y: <?= $countNovice ?>, color: '#F59E0B' },
                { name: 'ສັງກະລີ', y: <?= $countSangkhali ?>, color: '#EF4444' }
            ]
        }]
    });

    Highcharts.chart('monkPerEventChart', {
    chart: { type: 'column' },
    title: { text: 'ຈໍານວນພຣະທີ່ໄປລ່ວມງານ (Top 10)' },
    xAxis: { categories: <?= json_encode($categories) ?> },
    yAxis: { title: { text: 'ຈໍານວນພຣະ' } },
    series: [{
        name: 'ຈໍານວນພຣະ',
        data: <?= json_encode($monkCounts) ?>
         }]
    });
    Highcharts.chart('pieChart', {
        chart: { type: 'pie', animation: true },
        title: { text: 'ສັດສ່ວນພຣະສົງ' },
        series: [{
            name: 'ຈໍານວນ',
            colorByPoint: true,
            data: [
                { name: 'ພຣະ', y: <?= $countMonk ?>, color: '#6366F1' },
                { name: 'ແມ່ຂາວ', y: <?= $countNun ?>, color: '#10B981' },
                { name: 'ສາມະເນນ', y: <?= $countNovice ?>, color: '#F59E0B' },
                { name: 'ສັງກະລີ', y: <?= $countSangkhali ?>, color: '#EF4444' }
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
            data: <?= json_encode($chartData) ?>,
            color: '#F59E0B'
        }]
    });
});
Highcharts.chart('monkActivityChart', {
    chart: { type: 'column' },
    title: { text: 'ຈຳນວນງານກິດນິມນທີ່ພຣະ ແລະ ສາມະເນນໄປຮ່ວມ' },
    xAxis: { type: 'category', title: { text: 'ລາຍຊື່ຜູ້ທີ່ໄປອອກງານ' } },
    yAxis: {
        min: 0,
        max: <?= max(array_column($monkData, 'y')) ?>,
        scrollbars:{
            enabled: true,
        },
        title: { text: 'ຈຳນວນງານ' }
    },
    legend: { enabled: false },
    plotOptions: {
        series: {
            cursor: 'pointer',
            point: {
                events: {
                    click: function () {
                        window.location.href = this.options.url;
                    }
                }
            }
        }
    },
    tooltip: {
        pointFormat: 'ໄປຮ່ວມງານ: <b>{point.y}</b> ຄັ້ງ'
    },
    series: [{
        name: 'ພຣະ/ສາມະເນນ',
        colorByPoint: true,
        data: <?= json_encode(array_filter($monkData, function($item) {
            // Only show monks with prefix ພຣະ or ສ.ນ
            return strpos($item['name'], 'ພຣະ') === 0 || strpos($item['name'], 'ສ.ນ') === 0;
        }), JSON_UNESCAPED_UNICODE) ?>
    }]
});



Highcharts.setOptions({
    colors: ['#6366F1', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#3B82F6']
});
</script>
<?php if (!empty($upcoming_events)): ?>
<script>
Swal.fire({
    icon: 'info',
    title: 'ແຈ້ງເຕືອນງານທີ່ຈະມາເຖີງນີ້!',
    html: `
        <ul class="text-left">
            <?php foreach ($upcoming_events as $event): ?>
                <li><strong><?= htmlspecialchars($event['event_name']) ?></strong> ໃນວັນທີ <?= htmlspecialchars($event['event_date']) ?></li>
            <?php endforeach; ?>
        </ul>
    `,
    confirmButtonText: 'ຮັບຮູ້ແລ້ວ',
});
</script>
<?php endif; ?>
<?php include 'footer.php'; ?>
