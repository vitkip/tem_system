<?php
require '../db.php';
include '../header.php';

$stmt = $pdo->query("SELECT * FROM events ORDER BY event_date DESC");
$events = $stmt->fetchAll();
?>

<div class="max-w-7xl mx-auto p-6">
    <h1 class="text-3xl font-bold text-center text-indigo-700 mb-6">ລາຍການງານກິດນິມນຕ໌</h1>

    <div class="bg-white p-6 rounded-lg shadow">
        <table id="eventsTable" class="w-full table-auto">
            <thead class="bg-indigo-50">
                <tr>
                    <th class="px-4 py-2">ຊື່ງານ</th>
                    <th class="px-4 py-2">ວັນທີ</th>
                    <th class="px-4 py-2">ເວລາ</th>
                    <th class="px-4 py-2">ສະຖານທີ່</th>
                    <th class="px-4 py-2">ຈັດການ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $event): ?>
                    <tr class="border-t">
                        <td class="px-4 py-2"><?= htmlspecialchars($event['event_name']) ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($event['event_date']) ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($event['event_time']) ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($event['location']) ?></td>
                        <td class="px-4 py-2 space-x-2">
                            <a href="view_event.php?id=<?= $event['id'] ?>" class="text-blue-500 underline">ເບິ່ງ</a>
                            <a href="edit_event.php?id=<?= $event['id'] ?>" class="text-yellow-500 underline">ແກ້ໄຂ</a>
                            <button onclick="confirmDelete(<?= $event['id'] ?>)" class="text-red-500 underline">ລົບ</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="text-center mt-6">
            <a href="add_event.php" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700">➕ ເພີ່ມງານ</a>
        </div>
    </div>
</div>

<!-- JS สำหรับ SweetAlert และ DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>

<script>
$(document).ready(function() {
    $('#eventsTable').DataTable({
        responsive: true,
        dom: 'Bfrtip',
        buttons: ['excel', 'pdf', 'print'],
        language: {
            search: "ຄົ້ນຫາ:",
            lengthMenu: "ສະແດງ _MENU_ ລາຍການ",
            info: "ສະແດງ _START_ ຫາ _END_ ຈາກ _TOTAL_ ລາຍການ",
            paginate: {
                first: "ໜ້າທໍາອິດ",
                last: "ໜ້າສຸດທ້າຍ",
                next: "ຖັດໄປ",
                previous: "ກ່ອນໜ້າ"
            }
        }
    });
});

// SweetAlert2 ຢືນຢັນກ່ອນລົບ
function confirmDelete(id) {
    Swal.fire({
        title: 'ຢືນຢັນການລົບ?',
        text: "ຂໍ້ມູນຈະຖືກລົບຖາວອນ!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'ລົບເລີຍ!',
        cancelButtonText: 'ຍົກເລີກ'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'delete_event.php?id=' + id;
        }
    })
}
</script>

<?php include '../footer.php'; ?>
