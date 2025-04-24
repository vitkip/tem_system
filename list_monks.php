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
                        <th>ຊື່ຕາມບັນປະຈຳຕົວ</th>
                        <th>ນາມສະກຸນ</th>
                        <th>ບ້ານເກີດ</th>
                        <th>ແຂວງເກີດ</th>
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
                        <td><?= htmlspecialchars($monk['last_name']) ?></td>
                        <td><?= htmlspecialchars($monk['birthplace_village']) ?></td>
                        <td><?= htmlspecialchars($monk['birthplace_province']) ?></td>
                        
                        <td class="text-center space-x-2">
                        <?php if (isAdmin()): ?>
                         <!-- View Button -->
                          <button onclick="viewProfile(
                                '<?= htmlspecialchars($monk['prefix'] . ' ' . $monk['first_name']) ?>', 
                                '<?= htmlspecialchars($monk['current_temple']) ?>',
                                '<?= htmlspecialchars($monk['prefix']) ?>',
                                '<?= htmlspecialchars($monk['first_name']) ?>',
                                '<?= htmlspecialchars($monk['last_name']) ?>',
                                '<?= htmlspecialchars($monk['birth_date']) ?>',
                                '<?= htmlspecialchars($monk['nationality']) ?>',
                                '<?= htmlspecialchars($monk['ethnicity']) ?>',
                                '<?= htmlspecialchars($monk['birthplace_village']) ?>',
                                '<?= htmlspecialchars($monk['birthplace_district']) ?>',
                                '<?= htmlspecialchars($monk['birthplace_province']) ?>',
                                '<?= htmlspecialchars($monk['father_name']) ?>',
                                '<?= htmlspecialchars($monk['mother_name']) ?>',
                                '<?= htmlspecialchars($monk['ordination_date']) ?>',
                                '<?= htmlspecialchars($monk['age_pansa']) ?>',
                                '<?= htmlspecialchars($monk['certificate_number']) ?>',
                                '<?= $monk['photo'] ? 'uploads/' . htmlspecialchars(basename($monk['photo'])) : '' ?>'
                            )" 
                            class="inline-flex items-center px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-xs font-medium rounded shadow">
                                ເບິ່ງ
                            </button>

                            <!-- Edit Button -->
                                <a href="edit_monk.php?id=<?= $monk['id'] ?>" 
                                class="inline-flex items-center px-3 py-1.5 bg-yellow-400 hover:bg-yellow-500 text-white text-xs font-medium rounded shadow">
                                    ແກ້ໄຂ
                                </a>
                            <!-- Delete Button -->
                                    <button onclick="confirmDelete(<?= $monk['id'] ?>)" 
                                            class="inline-flex items-center px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-medium rounded shadow">
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
<script src="js/vfs_fonts_custom.js"></script>

<style>
.custom-swal-container {
    z-index: 9999;
}

.custom-swal-popup {
    border-radius: 1rem;
    padding: 0;
}

.swal2-close:focus {
    box-shadow: none;
}
</style>

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
        dom: '<"flex flex-col md:flex-row justify-between items-center gap-4 mb-4"Bl>rt<"flex flex-col md:flex-row justify-between items-center gap-4 mt-4"ip>',
        buttons: [
            {
                extend: 'pdfHtml5',
                text: 'ໂຫຼດ PDF',
                className: 'bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg',
                orientation: 'landscape',
                pageSize: 'A4',
                customize: function(doc) {
                    // ตั้งค่าฟอนต์
                    doc.defaultStyle = {
                        font: 'NotoSansLao',
                        fontSize: 12
                    };
                    
                    // กำหนดความกว้างคอลัมน์
                    doc.content[1].table.widths = Array(doc.content[1].table.body[0].length).fill('*');
                    
                    // ตั้งค่าหัวตาราง
                    doc.content[1].table.headerRows = 1;
                    doc.content[1].table.body[0].forEach(function(header) {
                        header.alignment = 'center';
                        header.fontSize = 13;
                        header.bold = true;
                    });
                },
                exportOptions: {
                    columns: [0, 2, 3, 4, 5], // ลำดับ, ชื่อ, นามสกุล, บ้านเกิด, แขวง
                    format: {
                        body: function(data, row, column, node) {
                            // ถ้าเป็นคอลัมน์รูปภาพ ให้ข้าม
                            if (column === 1) return '';
                            // ลบ HTML tags
                            return data.replace(/<.*?>/g, '');
                        }
                    }
                },
                title: 'ລາຍການຂໍ້ມູນພຣະສົງ',
                filename: 'monks_data_' + new Date().toISOString().slice(0,10)
            },
            {
                extend: 'excelHtml5',
                text: 'ໂຫຼດ Excel',
                className: 'bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg',
                exportOptions: {
                    columns: [0, 2, 3, 4, 5] // ลำดับ, ชื่อ, นามสกุล, บ้านเกิด, แขวง
                },
                title: 'ລາຍການຂໍ້ມູນພຣະສົງ',
                filename: 'monks_data_' + new Date().toISOString().slice(0,10)
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
    const val = this.value;
    if (val) {
        table.search(val).draw();
    } else {
        table.search('').draw();
    }
});

    $('#searchInput').on('keyup', function() {
        table.search(this.value).draw();
    });
});

function viewProfile(name, temple, prefix, firstName, lastName, birthDate, nationality, ethnicity, birthplaceVillage, birthplaceDistrict, birthplaceProvince, fatherName, motherName, ordinationDate, agePansa, certificateNumber, photoUrl) {
    Swal.fire({
        html: `
            <div class="min-w-[600px]">
                <!-- Header with Gradient -->
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 -mt-8 -mx-8 p-8 mb-6">
                    <div class="flex flex-col items-center">
                        <!-- Profile Image -->
                        <div class="relative">
                            ${photoUrl 
                                ? `<img src="${photoUrl}" class="h-32 w-32 rounded-full object-cover border-4 border-white shadow-lg" alt="${name}">` 
                                : `<div class="h-32 w-32 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border-4 border-white">
                                    <i class="fas fa-user-circle text-white text-5xl"></i>
                                   </div>`
                            }
                        </div>
                        <h2 class="mt-4 text-2xl font-bold text-white">${name}</h2>
                        <p class="text-indigo-100 mt-1">${temple}</p>
                    </div>
                </div>

                <!-- Content Grid -->
                <div class="grid grid-cols-2 gap-6 px-2">
                    <!-- Personal Information -->
                    <div class="bg-gray-50 rounded-xl p-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-user-circle text-indigo-600 mr-2"></i>
                            ຂໍ້ມູນສ່ວນຕົວ
                        </h3>
                        <div class="space-y-3">
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-id-card w-6 text-indigo-500"></i>
                                <span class="font-medium mr-2">ປະເພດ:</span> ${prefix}
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-calendar-day w-6 text-indigo-500"></i>
                                <span class="font-medium mr-2">ວັນເກີດ:</span> ${birthDate}
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-flag w-6 text-indigo-500"></i>
                                <span class="font-medium mr-2">ສັນຊາດ:</span> ${nationality}
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-users w-6 text-indigo-500"></i>
                                <span class="font-medium mr-2">ຊົນເຜົ່າ:</span> ${ethnicity}
                            </div>
                        </div>
                    </div>

                    <!-- Birth Place Information -->
                    <div class="bg-gray-50 rounded-xl p-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-map-marked-alt text-indigo-600 mr-2"></i>
                            ບ່ອນເກີດ
                        </h3>
                        <div class="space-y-3">
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-home w-6 text-indigo-500"></i>
                                <span class="font-medium mr-2">ບ້ານ:</span> ${birthplaceVillage}
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-city w-6 text-indigo-500"></i>
                                <span class="font-medium mr-2">ເມືອງ:</span> ${birthplaceDistrict}
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-map w-6 text-indigo-500"></i>
                                <span class="font-medium mr-2">ແຂວງ:</span> ${birthplaceProvince}
                            </div>
                        </div>
                    </div>

                    <!-- Family Information -->
                    <div class="bg-gray-50 rounded-xl p-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-users text-indigo-600 mr-2"></i>
                            ຂໍ້ມູນຄອບຄົວ
                        </h3>
                        <div class="space-y-3">
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-male w-6 text-indigo-500"></i>
                                <span class="font-medium mr-2">ຊື່ພໍ່:</span> ${fatherName}
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-female w-6 text-indigo-500"></i>
                                <span class="font-medium mr-2">ຊື່ແມ່:</span> ${motherName}
                            </div>
                        </div>
                    </div>

                    <!-- Ordination Information -->
                    <div class="bg-gray-50 rounded-xl p-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-pray text-indigo-600 mr-2"></i>
                            ຂໍ້ມູນການບວດ
                        </h3>
                        <div class="space-y-3">
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-calendar-alt w-6 text-indigo-500"></i>
                                <span class="font-medium mr-2">ວັນບວດ:</span> ${ordinationDate}
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-history w-6 text-indigo-500"></i>
                                <span class="font-medium mr-2">ພັນສາ:</span> ${calculatePansa(ordinationDate)} ພັນສາ
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-id-card-alt w-6 text-indigo-500"></i>
                                <span class="font-medium mr-2">ເລກໃບສຸດທິ:</span> ${certificateNumber}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `,
        showCloseButton: true,
        showConfirmButton: false,
        width: '800px',
        padding: '2em',
        background: '#fff',
        customClass: {
            container: 'custom-swal-container',
            popup: 'custom-swal-popup'
        }
    });
}

function calculatePansa(ordinationDate) {
    if (!ordinationDate) return 0;

    const ordDate = new Date(ordinationDate);
    const now = new Date();

    let pansa = now.getFullYear() - ordDate.getFullYear();

    // ถ้ายังไม่ถึงวันครบรอบบวชในปีนี้ ให้ลบออก 1
    const notYetAnniversary = (
        now.getMonth() < ordDate.getMonth() ||
        (now.getMonth() === ordDate.getMonth() && now.getDate() < ordDate.getDate())
    );
    if (notYetAnniversary) pansa--;

    return Math.max(0, pansa); // ไม่ให้ติดลบ
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
