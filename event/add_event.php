<?php
require '../db.php';
include '../header.php';

// เช็กสิทธิ์ แค่ admin เพิ่มได้
if (!isAdmin()) {
    echo "<div class='text-center text-red-500 mt-10 text-2xl font-bold'>ເຂົ້າໄດ້ສະເພາະແອັດມິນເທົ່ານັ້ນ</div>";
    exit();
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_name = trim($_POST['event_name']);
    $event_date = trim($_POST['event_date']);
    $event_time = trim($_POST['event_time']);
    $location = trim($_POST['location']);

    // Validation
    if (empty($event_name)) $errors[] = "ກະລຸນາໃສ່ຊື່ງານ.";
    if (empty($event_date)) $errors[] = "ກະລຸນາເລືອກວັນທີ.";
    if (empty($event_time)) $errors[] = "ກະລຸນາໃສ່ເວລາ.";
    if (empty($location)) $errors[] = "ກະລຸນາໃສ່ສະຖານທີ່.";

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO events (event_name, event_date, event_time, location) VALUES (?, ?, ?, ?)");
        $stmt->execute([$event_name, $event_date, $event_time, $location]);

        $success = "🎉 ບັນທຶກງານກິດນິມນຕໍາເລັດ!";
    }
}
?>

<div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow mt-10">
    <h1 class="text-3xl font-bold text-center text-indigo-700 mb-6">ເພີ່ມງານກິດນິມົນ</h1>

    <form method="POST" id="eventForm" class="space-y-6">
        <div>
            <label class="block mb-2 text-gray-700">ຊື່ງານ:</label>
            <input type="text" name="event_name" required class="w-full border rounded px-3 py-2">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block mb-2 text-gray-700">ວັນທີຈັດງານ:</label>
                <input type="date" name="event_date" required class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block mb-2 text-gray-700">ເວລາຈັດງານ:</label>
                <input type="time" name="event_time" required class="w-full border rounded px-3 py-2">
            </div>
        </div>

        <div>
            <label class="block mb-2 text-gray-700">ສະຖານທີ່:</label>
            <input type="text" name="location" required class="w-full border rounded px-3 py-2">
        </div>

        <div class="text-center mt-6">
            <button type="button" onclick="confirmSubmit()" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700">
                💾 ບັນທຶກຂໍ້ມູນ
            </button>
            <a href="<?= BASE_URL ?>event/list_events.php" class="ml-4 text-indigo-600 underline">← ກັບໄປລາຍການງານ</a>
        </div>
    </form>
</div>

<!-- Add SweetAlert2 CDN in the head section or before closing body -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Add JavaScript for SweetAlert2 -->
<script>
function confirmSubmit() {
    Swal.fire({
        title: 'ຢືນຢັນການບັນທຶກ?',
        text: 'ທ່ານຕ້ອງການບັນທຶກຂໍ້ມູນງານກິດນິມົນນີ້ແທ້ບໍ່?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#4F46E5',
        cancelButtonColor: '#EF4444',
        confirmButtonText: 'ບັນທຶກ',
        cancelButtonText: 'ຍົກເລີກ'
    }).then((result) => {
        if (result.isConfirmed) {
            // Check form validation
            const form = document.getElementById('eventForm');
            if (form.checkValidity()) {
                form.submit();
            } else {
                Swal.fire({
                    title: 'ກະລຸນາກວດສອບຂໍ້ມູນ!',
                    text: 'ກະລຸນາຕື່ມຂໍ້ມູນໃຫ້ຄົບຖ້ວນ',
                    icon: 'warning',
                    confirmButtonColor: '#4F46E5'
                });
            }
        }
    });
}

// Show success message if exists
<?php if (!empty($success)): ?>
    Swal.fire({
        title: 'ສໍາເລັດ!',
        text: '<?= $success ?>',
        icon: 'success',
        confirmButtonColor: '#4F46E5'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?= BASE_URL ?>event/list_events.php';
        }
    });
<?php endif; ?>

// Show error message if exists
<?php if (!empty($errors)): ?>
    Swal.fire({
        title: 'ເກີດຂໍ້ຜິດພາດ!',
        html: '<?= implode("<br>", array_map("htmlspecialchars", $errors)) ?>',
        icon: 'error',
        confirmButtonColor: '#4F46E5'
    });
<?php endif; ?>
</script>

<?php include '../footer.php'; ?>
