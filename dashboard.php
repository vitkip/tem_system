<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: register/login.php');
    exit();
}

// รับค่าปีและเดือนจาก GET หรือใช้ปี/เดือนปัจจุบัน
$selected_year = $_GET['year'] ?? date('Y');
$selected_month = $_GET['month'] ?? date('m');

// สร้างเงื่อนไข WHERE สำหรับปีและเดือน
$where = "YEAR(created_at) = ? AND MONTH(created_at) = ?";

// นับจำนวนพระ
$stmt = $pdo->prepare("SELECT COUNT(*) FROM monks WHERE prefix = 'พระ' AND $where");
$stmt->execute([$selected_year, $selected_month]);
$countMonk = $stmt->fetchColumn();

// นับจำนวนแม่ชี
$stmt = $pdo->prepare("SELECT COUNT(*) FROM monks WHERE prefix = 'แม่ชี' AND $where");
$stmt->execute([$selected_year, $selected_month]);
$countNun = $stmt->fetchColumn();

// นับจำนวนเณร
$stmt = $pdo->prepare("SELECT COUNT(*) FROM monks WHERE prefix = 'เณร' AND $where");
$stmt->execute([$selected_year, $selected_month]);
$countNovice = $stmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<?php include 'header.php'; ?>

<div class="p-8 max-w-6xl mx-auto space-y-8">

    <h1 class="text-3xl font-bold mb-6">แดชบอร์ด</h1>

    <!-- ฟอร์มเลือกปี/เดือน -->
    <form method="GET" class="bg-white p-4 rounded-lg shadow-md flex flex-wrap gap-4 items-center mb-6">
        <div>
            <label class="block text-gray-700 font-medium">เลือกปี</label>
            <select name="year" class="border px-3 py-2 rounded">
                <?php
                $current_year = date('Y');
                for ($y = $current_year; $y >= $current_year - 5; $y--) {
                    echo "<option value='$y'" . ($selected_year == $y ? " selected" : "") . ">$y</option>";
                }
                ?>
            </select>
        </div>

        <div>
            <label class="block text-gray-700 font-medium">เลือกเดือน</label>
            <select name="month" class="border px-3 py-2 rounded">
                <?php
                for ($m = 1; $m <= 12; $m++) {
                    $month_text = str_pad($m, 2, '0', STR_PAD_LEFT);
                    echo "<option value='$month_text'" . ($selected_month == $month_text ? " selected" : "") . ">$month_text</option>";
                }
                ?>
            </select>
        </div>

        <div>
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 mt-6">
                ค้นหา
            </button>
        </div>
    </form>
        <!-- สรุปจำนวนพระ-แม่ชี-เณร -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-indigo-100 p-6 rounded-lg shadow text-center">
                <div class="text-4xl font-bold text-indigo-700"><?= $countMonk ?></div>
                <div class="text-gray-600 mt-2">พระสงฆ์</div>
            </div>
            <div class="bg-green-100 p-6 rounded-lg shadow text-center">
                <div class="text-4xl font-bold text-green-700"><?= $countNun ?></div>
                <div class="text-gray-600 mt-2">แม่ชี</div>
            </div>
            <div class="bg-yellow-100 p-6 rounded-lg shadow text-center">
                <div class="text-4xl font-bold text-yellow-700"><?= $countNovice ?></div>
                <div class="text-gray-600 mt-2">เณร</div>
            </div>
        </div>

    <!-- กราฟแท่ง -->
    <div id="barChart" class="bg-white p-6 rounded-lg shadow-md"></div>

    <!-- กราฟวงกลม -->
    <div id="pieChart" class="bg-white p-6 rounded-lg shadow-md mt-8"></div>

</div>

<!-- Script วาดกราฟ -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const monks = <?= $countMonk ?>;
        const nuns = <?= $countNun ?>;
        const novices = <?= $countNovice ?>;

        Highcharts.chart('barChart', {
            chart: { type: 'column' },
            title: { text: 'จำนวนพระสงฆ์ แม่ชี เณร (ปี <?= $selected_year ?> เดือน <?= $selected_month ?>)' },
            xAxis: { categories: ['พระ', 'แม่ชี', 'เณร'] },
            yAxis: { min: 0, title: { text: 'จำนวน (รูป)' } },
            series: [{ name: 'จำนวน', data: [monks, nuns, novices] }]
        });

        Highcharts.chart('pieChart', {
            chart: { type: 'pie' },
            title: { text: 'สัดส่วนพระสงฆ์ แม่ชี เณร (ปี <?= $selected_year ?> เดือน <?= $selected_month ?>)' },
            series: [{
                name: 'จำนวน',
                colorByPoint: true,
                data: [
                    { name: 'พระ', y: monks },
                    { name: 'แม่ชี', y: nuns },
                    { name: 'เณร', y: novices }
                ]
            }]
        });
    });
</script>


<?php include 'footer.php'; ?>
