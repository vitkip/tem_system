<?php
require 'db.php';
include 'header.php';

// ດຶງຂໍ້ມູນພະ/ແມ່ຊີ/ເນນຈາກຖານຂໍ້ມູນ
$stmt = $pdo->query("SELECT * FROM monks");
$monks = $stmt->fetchAll();
?>

<div class="min-h-screen bg-gray-100 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- ຫົວຂໍ້ແລະປຸ່ມເພີ່ມ -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-indigo-700">ລາຍການຂໍ້ມູນພະ/ແມ່ຊີ/ເນນ</h1>
            <?php if (isAdmin()): ?>
            <a href="add_monk.php" class="bg-indigo-600 text-white px-5 py-2 rounded-lg hover:bg-indigo-700 transition shadow">
                ➕ ເພີ່ມຂໍ້ມູນພະ/ແມ່ຊີ/ເນນ
            </a>
            <?php endif; ?>

        </div>

        <!-- ຕາຕະລາງຂໍ້ມູນ -->
        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <div class="p-6">
                <table id="monkTable" class="w-full">
                    <thead class="bg-indigo-50">
                        <tr>
                            <th>ລໍາດັບ</th>
                            <th>ຮູບຖ່າຍ</th>
                            <th>ຊື່</th>
                            <th>ວັດປະຈຸບັນ</th>
                            <th>ປະເພດ</th>
                            <th>ຈັດການ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($monks as $index => $monk): ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td><?= $index + 1 ?></td>
                            <td>
                                <?php if ($monk['photo']): ?>
                                    <img src="uploads/<?= htmlspecialchars($monk['photo']) ?>" class="h-12 w-12 rounded-full object-cover border">
                                <?php else: ?>
                                    <div class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center text-gray-400">-</div>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($monk['prefix']) . ' ' . htmlspecialchars($monk['first_name']) . ' ' . htmlspecialchars($monk['last_name']) ?></td>
                            <td><?= htmlspecialchars($monk['current_temple']) ?></td>
                            <td><?= htmlspecialchars($monk['prefix']) ?></td>
                            <td class="space-x-2">
                                <a href="view_monk.php?id=<?= $monk['id'] ?>" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 text-sm">ເບິ່ງ</a>
                                <?php if (isAdmin()): ?>
                                <a href="edit_monk.php?id=<?= $monk['id'] ?>" class="bg-yellow-400 text-white px-3 py-1 rounded hover:bg-yellow-500 text-sm">ແກ້ໄຂ</a>
                                <button onclick="confirmDelete(<?= $monk['id'] ?>)" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm">ລົບ</button>
                                <?php endif; ?>

                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ໂຫຼດຟາຍທີ່ຈໍາເປັນ -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    pdfMake.fonts = {
    NotoSansLao: {
        normal: 'NotoSansLao-Regular.ttf',
        bold: 'NotoSansLao-Bold.ttf',
        italics: 'NotoSansLao-Regular.ttf',
        bolditalics: 'NotoSansLao-Bold.ttf'
    }
};

$(document).ready(function () {
    var table = $('#monkTable').DataTable({
        responsive: true,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'pdfHtml5',
                text: 'ສົ່ງອອກ PDF',
                customize: function (doc) {
                    doc.defaultStyle = {
                        font: 'NotoSansLao',
                        fontSize: 16
                    };
                },
                exportOptions: {
                    columns: [2, 3, 4]
                }
            },
            {
                extend: 'excelHtml5',
                text: 'ສົ່ງອອກ Excel',
                exportOptions: {
                    columns: [2, 3, 4]
                }
            }
        ],
        language: {
            search: "ຄົ້ນຫາ:",
            lengthMenu: "ສະແດງ _MENU_ ລາຍການຕໍ່ໜ້າ",
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

// ໂຕຢືນກ່ອນລົບ
function confirmDelete(id) {
    Swal.fire({
        title: 'ທ່ານແນ່ໃຈບໍ?',
        text: "ຂໍ້ມູນນີ້ຖ້າລົບແລ້ວຈະກູ້ຄືນບໍ່ໄດ້!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'ຕົກລົງ',
        cancelButtonText: 'ຍົກເລີກ'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'delete_monk.php?id=' + id;
        }
    });
}
</script>

<?php include 'footer.php'; ?>

<?php
// ຟັງຊັນດຶງ logo ໃສ່ base64
function getLogoBase64() {
    $path = 'assets/logo.png'; // ໂລໂກ້ຂອງວັດ
    if (file_exists($path)) {
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
    return '';
}
?>
