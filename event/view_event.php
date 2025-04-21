<?php
require '../db.php';
include '../header.php';

if (!isset($_GET['id'])) {
    die('<div class="text-center text-red-600 mt-10 text-xl font-bold">ບໍ່ພົບຂໍ້ມູນງານ</div>');
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$id]);
$event = $stmt->fetch();

if (!$event) {
    die('<div class="text-center text-red-600 mt-10 text-xl font-bold">ບໍ່ພົບຂໍ້ມູນໃນຖານຂໍ້ມູນ</div>');
}
?>

<div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow mt-10">
    <h1 class="text-3xl font-bold text-center text-indigo-700 mb-6"><?= htmlspecialchars($event['event_name']) ?></h1>

    <div class="space-y-4">
        <p><strong>📅 ວັນທີ:</strong> <?= htmlspecialchars($event['event_date']) ?></p>
        <p><strong>⏰ ເວລາ:</strong> <?= htmlspecialchars($event['event_time']) ?></p>
        <p><strong>📍 ສະຖານທີ່:</strong> <?= htmlspecialchars($event['location']) ?></p>
    </div>

    <div class="flex space-x-4 justify-center mt-6">
        <a href="edit_event.php?id=<?= $event['id'] ?>" class="bg-yellow-500 text-white px-6 py-2 rounded hover:bg-yellow-600">ແກ້ໄຂ</a>
        <a href="delete_event.php?id=<?= $event['id'] ?>" onclick="return confirm('ຕ້ອງການລົບແທ້ບໍ?')" class="bg-red-500 text-white px-6 py-2 rounded hover:bg-red-600">ລົບ</a>
        <a href="list_events.php" class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600">ກັບໄປ</a>
    </div>
</div>

<?php include '../footer.php'; ?>
