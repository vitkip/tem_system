<?php
require '../db.php';
include '../header.php';

if (!isAdmin()) {
    echo "<div class='text-center text-red-500 mt-10 text-2xl font-bold'>ເຂົ້າໄດ້ສະເພາະແອັດມິນເທົ່ານັ້ນ</div>";
    exit();
}

if (!isset($_GET['id'])) {
    die('<div class="text-center text-red-600 mt-10 text-xl font-bold">ບໍ່ພົບຂໍ້ມູນເພື່ອແກ້ໄຂ</div>');
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$id]);
$event = $stmt->fetch();

if (!$event) {
    die('<div class="text-center text-red-600 mt-10 text-xl font-bold">ບໍ່ພົບຂໍ້ມູນໃນຖານຂໍ້ມູນ</div>');
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_name = trim($_POST['event_name']);
    $event_date = trim($_POST['event_date']);
    $event_time = trim($_POST['event_time']);
    $location = trim($_POST['location']);

    if (empty($event_name)) $errors[] = "ກະລຸນາໃສ່ຊື່ງານ.";
    if (empty($event_date)) $errors[] = "ກະລຸນາເລືອກວັນທີ.";
    if (empty($event_time)) $errors[] = "ກະລຸນາໃສ່ເວລາ.";
    if (empty($location)) $errors[] = "ກະລຸນາໃສ່ສະຖານທີ່.";

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE events SET event_name=?, event_date=?, event_time=?, location=? WHERE id=?");
        $stmt->execute([$event_name, $event_date, $event_time, $location, $id]);

        $success = "🎉 ແກ້ໄຂງານສໍາເລັດ!";
    }
}
?>

<div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow mt-10">
    <h1 class="text-3xl font-bold text-center text-indigo-700 mb-6">ແກ້ໄຂຂໍ້ມູນງານ</h1>

    <?php if (!empty($errors)): ?>
        <div class="bg-red-100 text-red-700 p-4 rounded mb-6">
            <ul class="list-disc pl-5">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="bg-green-100 text-green-700 p-4 rounded mb-6"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" class="space-y-6">
        <div>
            <label class="block mb-2 text-gray-700">ຊື່ງານ:</label>
            <input type="text" name="event_name" value="<?= htmlspecialchars($event['event_name']) ?>" required class="w-full border rounded px-3 py-2">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block mb-2 text-gray-700">ວັນທີຈັດງານ:</label>
                <input type="date" name="event_date" value="<?= htmlspecialchars($event['event_date']) ?>" required class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block mb-2 text-gray-700">ເວລາຈັດງານ:</label>
                <input type="time" name="event_time" value="<?= htmlspecialchars($event['event_time']) ?>" required class="w-full border rounded px-3 py-2">
            </div>
        </div>

        <div>
            <label class="block mb-2 text-gray-700">ສະຖານທີ່:</label>
            <input type="text" name="location" value="<?= htmlspecialchars($event['location']) ?>" required class="w-full border rounded px-3 py-2">
        </div>

        <div class="text-center mt-6">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700">💾 ບັນທຶກຂໍ້ມູນ</button>
            <a href="list_events.php" class="ml-4 text-indigo-600 underline">← ກັບໄປ</a>
        </div>
    </form>
</div>

<?php include '../footer.php'; ?>
