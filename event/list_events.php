<?php
require '../db.php';
include '../header.php';

// ดึงข้อมูล Event
$events = $pdo->query("SELECT * FROM events ORDER BY event_date DESC")->fetchAll();
?>

<!-- DataTables CSS + Responsive + Buttons -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

<!-- Script -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="max-w-7xl mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-indigo-700">📅 ລາຍການງານກິດນິມົນ</h1>
        <?php if (isAdmin()): ?>
            <a href="<?= BASE_URL ?>event/add_event.php" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow">
                ➕ ເພີ່ມງານກິດ
            </a>
        <?php endif; ?>
    </div>

    <div class="bg-white rounded-lg shadow p-6 overflow-x-auto">
        <table id="eventsTable" class="display responsive nowrap w-full text-left">
            <thead class="bg-indigo-50">
                <tr>
                    <th>ລໍາດັບ</th>
                    <th>ຊື່ງານ</th>
                    <th>ວັນທີ</th>
                    <th>ເວລາ</th>
                    <th>ສະຖານທີ່</th>
                    <th>ຈັດການ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $index => $event): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($event['event_name']) ?></td>
                    <td><?= htmlspecialchars($event['event_date']) ?></td>
                    <td><?= htmlspecialchars($event['event_time']) ?></td>
                    <td><?= htmlspecialchars($event['location']) ?></td>
                    <td class="space-x-1 text-sm">
                        <a href="<?= BASE_URL ?>event/view_event.php?id=<?= $event['id'] ?>" class="inline-flex items-center bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">
                            👁️ ເບິ່ງ
                        </a>
                        <?php if (isAdmin()): ?>
                        <a href="<?= BASE_URL ?>event/edit_event.php?id=<?= $event['id'] ?>" class="inline-flex items-center bg-yellow-400 text-white px-2 py-1 rounded hover:bg-yellow-500">
                            ✏️ ແກ້ໄຂ
                        </a>
                        <button onclick="deleteEvent(<?= $event['id'] ?>)" class="inline-flex items-center bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">
                            🗑️ ລຶບ
                        </button>
                        <a href="<?= BASE_URL ?>event/assign_monks.php?event_id=<?= $event['id'] ?>" class="inline-flex items-center bg-indigo-500 text-white px-2 py-1 rounded hover:bg-indigo-600">
                            🙏 ເລືອກພຣະ
                        </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    
$(document).ready(function() {
    $('#eventsTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthChange: false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                text: '📥 ດາວໂຫຼດ Excel',
                exportOptions: {
                    columns: [1, 2, 3, 4] // ไม่รวมปุ่ม action
                }
            },
            {
                extend: 'pdfHtml5',
                text: '📥 ດາວໂຫຼດ PDF',
                exportOptions: {
                    columns: [1, 2, 3, 4]
                },
                orientation: 'landscape',
                pageSize: 'A4'
            }
        ],
        language: {
            search: "ຄົ້ນຫາ:",
            info: "ສະແດງ _START_ ຫາ _END_ ຈາກ _TOTAL_ ລາຍການ",
            paginate: {
                first: "ໜ້າທໍາອິດ",
                last: "ໜ້າສຸດທ້າຍ",
                next: "ຖັດໄປ",
                previous: "ກ່ອນໜ້າ"
            },
            zeroRecords: "ບໍ່ພົບຂໍ້ມູນ",
            emptyTable: "ບໍ່ມີຂໍ້ມູນໃນຕາຕະລາງ"
        }
    });
});

// SweetAlert ຕອນລຶບ
function deleteEvent(id) {
    Swal.fire({
        title: 'ຕ້ອງການລຶບງານນີ້ບໍ?',
        text: "ຂໍ້ມູນທີ່ຖືກລຶບຈະບໍ່ສາມາດກູ້ຄືນໄດ້",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'ລຶບ',
        cancelButtonText: 'ຍົກເລີກ'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?= BASE_URL ?>event/delete_event.php?id=' + id;
        }
    });
}
</script>

<?php include '../footer.php'; ?>
