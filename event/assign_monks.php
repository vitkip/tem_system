<?php
require '../db.php';
include '../header.php';

// เช็กสิทธิ์
if (!isAdmin()) {
    echo "<div class='text-center text-red-500 p-10 text-2xl font-bold'>ເຂົ້າໃຊ້ໄດ້ສໍາລັບແອັດມິນເທົ່ານັ້ນ</div>";
    include '../footer.php';
    exit;
}

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

    <form method="POST" class="space-y-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <?php foreach ($monks as $monk): ?>
                <label class="flex items-center space-x-2 border p-3 rounded hover:bg-gray-100">
                    <input type="checkbox" name="monks[]" value="<?= $monk['id'] ?>" 
                        <?= in_array($monk['id'], $assignedMonks) ? 'checked' : '' ?>
                        class="h-5 w-5 text-indigo-600 border-gray-300 rounded">
                    <span><?= htmlspecialchars($monk['prefix'] . ' ' . $monk['first_name'] . ' ' . $monk['last_name']) ?></span>
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

<?php include '../footer.php'; ?>
