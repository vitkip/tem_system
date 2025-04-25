<?php
require '../db.php';
include '../header.php';

// เช็กสิทธิ์
// ตรวจสอบสิทธิ์ก่อนทำการลบ
checkPermission();
checkAdminPermission();

// รับ event id
if (!isset($_GET['event_id'])) {
    header('Location: list_events.php');
    exit;
}

$event_id = intval($_GET['event_id']);

// ดึงข้อมูลงาน
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$event_id]);
$event = $stmt->fetch();

if (!$event) {
    echo "<div class='text-center text-red-500 p-10 text-2xl font-bold'>ບໍ່ພົບຂໍ້ມູນງານ</div>";
    include '../footer.php';
    exit;
}

// ดึงรายชื่อพระทั้งหมด
$monks = $pdo->query("SELECT * FROM monks ORDER BY first_name ASC")->fetchAll();

// ดึงรายชื่อพระที่เลือกไปแล้ว
$stmt = $pdo->prepare("SELECT monk_id FROM event_monk WHERE event_id = ?");
$stmt->execute([$event_id]);
$assignedMonks = array_column($stmt->fetchAll(), 'monk_id');

// ถ้ามีการส่งฟอร์ม
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedMonks = $_POST['monks'] ?? [];

    // ลบเก่าทิ้ง
    $pdo->prepare("DELETE FROM event_monk WHERE event_id = ?")->execute([$event_id]);

    // เพิ่มใหม่
    foreach ($selectedMonks as $monk_id) {
        $stmt = $pdo->prepare("INSERT INTO event_monk (event_id, monk_id) VALUES (?, ?)");
        $stmt->execute([$event_id, $monk_id]);
    }

    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
    Swal.fire({
        icon: 'success',
        title: 'ບັນທຶກການເລືອກພຣະສໍາເລັດ!',
        showConfirmButton: false,
        timer: 1500
    }).then(() => {
        window.location = 'list_events.php';
    });
    </script>";
    exit;
}
?>

<div class="max-w-5xl mx-auto mt-10 bg-white p-8 rounded-lg shadow">
    <h1 class="text-2xl font-bold mb-6 text-indigo-700">ເລືອກພຣະໄປງານ: <?= htmlspecialchars($event['event_name']) ?></h1>

    <!-- เพิ่มช่องค้นหา -->
    <div class="mb-6">
        <div class="flex space-x-4">
            <div class="flex-1">
                <input type="text" 
                       id="searchInput" 
                       placeholder="ຄົ້ນຫາລາຍຊື່ພຣະ..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>
            <select id="filterPrefix" 
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                <option value="">ທັງໝົດ</option>
                <option value="ພຣະ">ພຣະ</option>
                <option value="ຄຸນແມ່ຂາວ">ຄຸນແມ່ຂາວ</option>
                <option value="ສ.ນ">ສ.ນ</option>
                <option value="ສັງກະລີ">ສັງກະລີ</option>
            </select>
        </div>
        <div class="mt-2 text-sm text-gray-500">
            ພົບ: <span id="monkCount">0</span> ລາຍການ
        </div>
    </div>

    <form method="POST" class="space-y-6">
        <div id="monkList" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <?php foreach ($monks as $monk): ?>
                <label class="monk-item flex items-center space-x-2 border p-3 rounded hover:bg-gray-100" 
                       data-name="<?= strtolower(htmlspecialchars($monk['first_name'] . ' ' . $monk['last_name'])) ?>"
                       data-prefix="<?= htmlspecialchars($monk['prefix']) ?>">
                    <input type="checkbox" 
                           name="monks[]" 
                           value="<?= $monk['id'] ?>" 
                           <?= in_array($monk['id'], $assignedMonks) ? 'checked' : '' ?>
                           class="h-5 w-5 text-indigo-600 border-gray-300 rounded">
                    <span class="monk-name">
                        <?= htmlspecialchars($monk['prefix'] . ' ' . $monk['first_name'] . ' ' . $monk['last_name']) ?>
                    </span>
                </label>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-8">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded">
                💾 ບັນທຶກການເລືອກ
            </button>
            <a href="list_events.php" class="ml-4 text-indigo-600 underline">← ກັບໄປລາຍງານ</a>
        </div>

    </form>
</div>

<!-- เพิ่ม JavaScript สำหรับการค้นหา -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const filterPrefix = document.getElementById('filterPrefix');
    const monkItems = document.querySelectorAll('.monk-item');
    const monkCount = document.getElementById('monkCount');

    function updateMonkCount() {
        const visibleMonks = document.querySelectorAll('.monk-item:not(.hidden)');
        monkCount.textContent = visibleMonks.length;
    }

    function filterMonks() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedPrefix = filterPrefix.value;

        monkItems.forEach(item => {
            const name = item.dataset.name;
            const prefix = item.dataset.prefix;
            const matchesSearch = name.includes(searchTerm);
            const matchesPrefix = selectedPrefix === '' || prefix === selectedPrefix;

            if (matchesSearch && matchesPrefix) {
                item.classList.remove('hidden');
            } else {
                item.classList.add('hidden');
            }
        });

        updateMonkCount();
    }

    // เพิ่ม event listeners
    searchInput.addEventListener('input', filterMonks);
    filterPrefix.addEventListener('change', filterMonks);

    // แสดงจำนวนรายการเริ่มต้น
    updateMonkCount();
});
</script>

<?php include '../footer.php'; ?>
