<?php
require '../db.php';
include '../header.php';

// เช็ก event_id
if (!isset($_GET['id'])) {
    echo "<div class='text-center text-red-500 mt-10 text-2xl font-bold'>ບໍ່ພົບຂໍ້ມູນງານ</div>";
    exit();
}
$event_id = $_GET['id'];

// ดึงข้อมูลงาน
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$event_id]);
$event = $stmt->fetch();

// ดึงพระที่ไปงาน
$stmt = $pdo->prepare("SELECT m.prefix, m.first_name, m.last_name 
                       FROM event_monk em
                       JOIN monks m ON em.monk_id = m.id
                       WHERE em.event_id = ?");
$stmt->execute([$event_id]);
$monks = $stmt->fetchAll();
?>

<div class="max-w-5xl mx-auto bg-white p-8 rounded-lg shadow mt-10">
    <h1 class="text-3xl font-bold text-indigo-700 text-center mb-8"><?= htmlspecialchars($event['event_name']) ?></h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-700">
        <div>
            <p><strong>ວັນທີ:</strong> <?= htmlspecialchars($event['event_date']) ?></p>
            <p><strong>ເວລາ:</strong> <?= htmlspecialchars($event['event_time']) ?></p>
            <p><strong>ສະຖານທີ່:</strong> <?= htmlspecialchars($event['location']) ?></p>
        </div>
    </div>

    <div class="mt-8">
        <h2 class="text-2xl font-bold mb-4 text-indigo-600">ລາຍຊື່ພຣະສົງທີ່ໄປຮ່ວມ:</h2>

        <?php if (count($monks) > 0): ?>
            <ul class="list-disc pl-6 space-y-2">
                <?php foreach ($monks as $monk): ?>
                    <li><?= htmlspecialchars($monk['prefix'] . ' ' . $monk['first_name'] . ' ' . $monk['last_name']) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="text-gray-500">- ບໍ່ມີການເພີ່ມພຣະສົງເຂົ້າຮ່ວມ -</p>
        <?php endif; ?>
    </div>

    <div class="text-center mt-10">
        <?php if (isAdmin()): ?>
        <a href="assign_monks.php?event_id=<?= $event_id ?>" class="bg-yellow-500 text-white px-6 py-2 rounded hover:bg-yellow-600">
            ➕ ເລືອກພຣະສົງເຂົ້າຮ່ວມ
        </a>
        <?php endif; ?>

        <a href="list_events.php" class="ml-4 text-indigo-600 underline">← ກັບໄປລາຍການ</a>
    </div>
</div>

<?php include '../footer.php'; ?>
