<?php
require 'db.php';
include 'header.php';

// Fetch monks data
$stmt = $pdo->query("SELECT * FROM monks ORDER BY id DESC");
$monks = $stmt->fetchAll();
?>

<div class="min-h-screen bg-gray-100 py-10">
    <div class="container mx-auto px-6">

        <!-- Filter & Search -->
        <div class="bg-white p-6 rounded-lg shadow mb-8 space-y-6">
            <div class="flex flex-col md:flex-row md:space-x-6 space-y-4 md:space-y-0">
                
                <!-- Filter Dropdown -->
                <div class="flex-1">
                    <label for="filterPrefix" class="block text-sm font-medium text-gray-700 mb-2">ເລືອກປະເພດ:</label>
                    <select id="filterPrefix" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">-- ເບິ່ງທັງໝົດ --</option>
                        <option value="ພຣະ">ພຣະ</option>
                        <option value="ຄຸນແມ່ຂາວ">ຄຸນແມ່ຂາວ</option>
                        <option value="ສ.ນ">ສ.ນ</option>
                        <option value="ສັງກະລີ">ສັງກະລີ</option>
                    </select>
                </div>

                <!-- Search Input -->
                <div class="flex-1">
                    <label for="searchInput" class="block text-sm font-medium text-gray-700 mb-2">ຄົ້ນຫາ:</label>
                    <input type="text" id="searchInput" placeholder="ຄົ້ນຫາ..." class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                </div>

                <!-- Add Button -->
                <?php if (isAdmin()): ?>
                <div class="flex items-end">
                    <a href="add_monk.php" class="inline-flex items-center px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-md shadow">
                        ➕ ເພີ່ມຂໍ້ມູນ
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- DataTable -->
        <div class="bg-white p-6 rounded-lg shadow overflow-x-auto">
            <table id="monkTable" class="stripe hover w-full">
                <thead class="bg-indigo-50">
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">ຮູບ</th>
                        <th>ຊື່</th>
                        <th>ວັດປະຈຸບັນ</th>
                        <th>ປະເພດ</th>
                        <th class="text-center">ຈັດການ</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <?php foreach ($monks as $index => $monk): ?>
                    <tr>
                        <td class="text-center"><?= $index + 1 ?></td>
                        <td class="text-center">
                            <?php if (!empty($monk['photo'])): ?>
                                <img src="uploads/<?= htmlspecialchars(basename($monk['photo'])) ?>" class="h-12 w-12 rounded-full object-cover border" alt="monk photo" />
                            <?php else: ?>
                                <div class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center text-gray-400">-</div>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($monk['prefix'] . ' ' . $monk['first_name']) ?></td>
                        <td><?= htmlspecialchars($monk['current_temple']) ?></td>
                        <td><?= htmlspecialchars($monk['prefix']) ?></td>
                        <td class="text-center space-x-2">
                        <?php if (isAdmin()): ?>
                         <!-- View Button -->
                          <button onclick="viewProfile(
                                '<?= htmlspecialchars($monk['prefix'] . ' ' . $monk['first_name']) ?>', 
                                '<?= htmlspecialchars($monk['current_temple']) ?>',
                                '<?= htmlspecialchars($monk['prefix']) ?>',
                                '<?= $monk['photo'] ? 'uploads/' . htmlspecialchars(basename($monk['photo'])) : '' ?>'
                            )" 
                            class="inline-flex items-center px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-xs font-medium rounded shadow">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7s-8.268-2.943-9.542-7z" />
                                </svg>
                                ເບິ່ງ
                            </button>

                       
                            <!-- Edit Button -->
                                <a href="edit_monk.php?id=<?= $monk['id'] ?>" 
                                class="inline-flex items-center px-3 py-1.5 bg-yellow-400 hover:bg-yellow-500 text-white text-xs font-medium rounded shadow">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            d="M15.232 5.232l3.536 3.536M9 11l6 6M3 21h18" />
                                    </svg>
                                    ແກ້ໄຂ
                                </a>
                            <!-- Delete Button -->
                                    <button onclick="confirmDelete(<?= $monk['id'] ?>)" 
                                            class="inline-flex items-center px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-medium rounded shadow">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        ລົບ
                                    </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<!-- Include Scripts -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
pdfMake.fonts = {
    NotoSansLao: {
        normal: '/tem_system/fonts/NotoSansLao-Regular.ttf',
        bold: '/tem_system/fonts/NotoSansLao-Bold.ttf',
        italics: '/tem_system/fonts/NotoSansLao-Regular.ttf',
        bolditalics: '/tem_system/fonts/NotoSansLao-Bold.ttf'
    }
};

$(function() {
    var table = $('#monkTable').DataTable({
        responsive: true,
        dom: '<"flex justify-between items-center mb-4"Bl>rt<"flex justify-between items-center mt-4"ip>',
        buttons: [
            {
                extend: 'pdfHtml5',
                text: 'ໂຫຼດ PDF',
                customize: function(doc) {
                    doc.defaultStyle = { font: 'NotoSansLao', fontSize: 14 };
                },
                exportOptions: { columns: [2, 3, 4] }
            },
            {
                extend: 'excelHtml5',
                text: 'ໂຫຼດ Excel',
                exportOptions: { columns: [2, 3, 4] }
            }
        ],
        language: {
            search: "ຄົ້ນຫາ:",
            lengthMenu: "ສະແດງ _MENU_ ລາຍການ",
            info: "ສະແດງ _START_ ຫາ _END_ ຈາກ _TOTAL_ ລາຍການ",
            paginate: {
                first: "ທໍາອິດ",
                last: "ສຸດທ້າຍ",
                next: "ຖັດໄປ",
                previous: "ກ່ອນໜ້າ"
            }
        }
    });

    $('#filterPrefix').on('change', function() {
        table.column(4).search(this.value).draw();
    });

    $('#searchInput').on('keyup', function() {
        table.search(this.value).draw();
    });
});

function viewProfile(name, temple, prefix, photoUrl) {
    Swal.fire({
        title: name,
        html: `
            <div class="flex flex-col items-center space-y-4">
                ${photoUrl ? `<img src="${photoUrl}" class="w-32 h-32 rounded-full object-cover border" alt="Photo">` 
                            : `<div class="w-32 h-32 rounded-full bg-gray-200 flex items-center justify-center text-gray-400">-</div>`}
                <div class="text-left space-y-2">
                   
                      
           
                <div><span class="font-semibold text-gray-600">ປະເພດ:</span> <?= htmlspecialchars($monk['prefix']) ?></div>
                <div><span class="font-semibold text-gray-600">ຊື່:</span> <?= htmlspecialchars($monk['first_name']) ?> <?= htmlspecialchars($monk['last_name']) ?></div>
                <div><span class="font-semibold text-gray-600">ວັນເກີດ:</span> <?= htmlspecialchars($monk['birth_date']) ?></div>
                <div><span class="font-semibold text-gray-600">ສັນຊາດ:</span> <?= htmlspecialchars($monk['nationality']) ?></div>
                <div><span class="font-semibold text-gray-600">ຊົນເຜົ່າ:</span> <?= htmlspecialchars($monk['ethnicity']) ?></div>
                <div><span class="font-semibold text-gray-600">ວັດປະຈຸບັນ:</span> <?= htmlspecialchars($monk['current_temple']) ?></div>
            

            
                <div><span class="font-semibold text-gray-600">ບ້ານເກີດ:</span> <?= htmlspecialchars($monk['birthplace_village']) ?>, ເມືອງ <?= htmlspecialchars($monk['birthplace_district']) ?>, ແຂວງ <?= htmlspecialchars($monk['birthplace_province']) ?></div>
                <div><span class="font-semibold text-gray-600">ຊື່ພໍ່:</span> <?= htmlspecialchars($monk['father_name']) ?></div>
                <div><span class="font-semibold text-gray-600">ຊື່ແມ່:</span> <?= htmlspecialchars($monk['mother_name']) ?></div>
                <div><span class="font-semibold text-gray-600">ວັນບວດ:</span> <?= htmlspecialchars($monk['ordination_date']) ?></div>
                <div><span class="font-semibold text-gray-600">ພັນສາ:</span> <?= htmlspecialchars($monk['age_pansa']) ?> ພັນສາ</div>
                <div><span class="font-semibold text-gray-600">ເລກໃບສຸດທິ:</span> <?= htmlspecialchars($monk['certificate_number']) ?></div>
        
        

                </div>
            </div>
        `,
        showCloseButton: true,
        showConfirmButton: false,
        width: '400px',
        padding: '2em',
        background: '#fff',
        backdrop: `
            rgba(0,0,0,0.4)
            left top
            no-repeat
        `
    });
}

function confirmDelete(id) {
    Swal.fire({
        title: 'ທ່ານແນ່ໃຈບໍ?',
        text: "ຂໍ້ມູນນີ້ຈະຖືກລົບຖາວອນ!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e3342f',
        cancelButtonColor: '#6c757d',
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
