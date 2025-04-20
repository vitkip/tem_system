<?php
require 'db.php';
include 'header.php';

// ดึงข้อมูลพระ/แม่ชี/เณรจากฐานข้อมูล
$stmt = $pdo->query("SELECT * FROM monks");
$monks = $stmt->fetchAll();
?>

<div class="p-8 max-w-7xl mx-auto">
    <h1 class="text-3xl font-bold mb-6 text-center text-indigo-700">รายการข้อมูลพระ/แม่ชี/เณร</h1>
   <!-- ปุ่มเพิ่มพระใหม่ -->
   <div class="flex justify-end mb-6">
        <a href="add_monk.php" class="bg-indigo-600 text-white px-5 py-2 rounded hover:bg-indigo-700">
            ➕ เพิ่มพระ/แม่ชี/เณร
        </a>
    </div
    <div class="bg-white p-6 rounded-lg shadow-md">
        <table id="monkTable" class="display w-full">
            <thead class="bg-indigo-50">
                <tr>
                    <th class="px-4 py-2 text-left">ลำดับ</th>
                    <th class="px-4 py-2 text-left">รูปถ่าย</th>
                    <th class="px-4 py-2 text-left">ชื่อ</th>
                    <th class="px-4 py-2 text-left">วัดปัจจุบัน</th>
                    <th class="px-4 py-2 text-left">ประเภท</th>
                    <th class="px-4 py-2 text-left">การทำงาน</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($monks as $index => $monk): ?>
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-2"><?= $index + 1 ?></td>
                    <td class="px-4 py-2">
                        <?php if ($monk['photo']): ?>
                            <img src="uploads/<?= htmlspecialchars($monk['photo']) ?>" class="h-12 w-12 rounded-full object-cover">
                        <?php else: ?>
                            <span class="text-gray-400">ไม่มีรูป</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-2"><?= htmlspecialchars($monk['prefix']) . ' ' . htmlspecialchars($monk['first_name']) . ' ' . htmlspecialchars($monk['last_name']) ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($monk['current_temple']) ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($monk['prefix']) ?></td>
                    <td class="px-4 py-2 space-x-2">
                        <a href="view_monk.php?id=<?= $monk['id'] ?>" class="inline-block bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 text-sm">ดูข้อมูล</a>
                        <a href="edit_monk.php?id=<?= $monk['id'] ?>" class="inline-block bg-yellow-400 text-white px-3 py-1 rounded hover:bg-yellow-500 text-sm">แก้ไข</a>
                        <button onclick="confirmDelete(<?= $monk['id'] ?>)" class="inline-block bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm">ลบ</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Script DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        $('#monkTable').DataTable({
            responsive: true,
            language: {
                "lengthMenu": "แสดง _MENU_ รายการต่อหน้า",
                "zeroRecords": "ไม่พบข้อมูล",
                "info": "แสดงหน้า _PAGE_ จาก _PAGES_",
                "infoEmpty": "ไม่มีข้อมูล",
                "infoFiltered": "(กรองจากทั้งหมด _MAX_ รายการ)",
                "search": "ค้นหา:",
                "paginate": {
                    "first": "หน้าแรก",
                    "last": "หน้าสุดท้าย",
                    "next": "ถัดไป",
                    "previous": "ก่อนหน้า"
                },
            }
        });
    });

    function confirmDelete(id) {
        Swal.fire({
            title: 'คุณแน่ใจหรือไม่?',
            text: "ลบข้อมูลนี้แล้วจะไม่สามารถกู้คืนได้!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'ใช่, ลบเลย!',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'delete_monk.php?id=' + id;
            }
        })
    }
</script>

<?php include 'footer.php'; ?>
