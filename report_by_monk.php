<?php
session_start();
require 'db.php';
require 'functions.php';
include 'header.php';
require 'vendor/autoload.php'; // เพิ่ม TCPDF

checkPermission();

// ฟังก์ชันสร้างบัตรประจำตัว
function generateMonkCard($monk) {
    // สร้าง PDF ขนาดบัตร
    $pdf = new TCPDF('L', 'mm', array(85.6, 54), true, 'UTF-8');
    $pdf->SetCreator('TEM System');
    $pdf->SetAuthor('Temple Management System');
    $pdf->SetTitle('Monk ID Card');

    // ตั้งค่าหน้ากระดาษ
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->SetMargins(0, 0, 0);
    $pdf->AddPage();

    // เพิ่ม background
    $pdf->Image('assets/card-bg.png', 0, 0, 85.6, 54);

    // เพิ่มรูปภาพ
    if ($monk['photo']) {
        $pdf->Image('uploads/' . $monk['photo'], 5, 10, 25, 30);
    }

    // เพิ่มข้อมูล
    $pdf->SetFont('notosanslao', '', 12);
    $pdf->SetXY(35, 10);
    $pdf->Cell(0, 6, $monk['prefix'] . ' ' . $monk['first_name'] . ' ' . $monk['last_name']);
    
    $pdf->SetFont('notosanslao', '', 10);
    $pdf->SetXY(35, 18);
    $pdf->Cell(0, 5, 'ວັດ: ' . $monk['current_temple']);
    
    $pdf->SetXY(35, 24);
    $pdf->Cell(0, 5, 'ວັນເດືອນປີເກີດ: ' . $monk['birth_date']);
    
    $pdf->SetXY(35, 30);
    $pdf->Cell(0, 5, 'ເລກບັດ: ' . $monk['certificate_number']);

    // QR Code
    $style = array(
        'border' => false,
        'vpadding' => 'auto',
        'hpadding' => 'auto',
        'fgcolor' => array(0,0,0),
        'bgcolor' => false
    );
    $pdf->write2DBarcode(
        'ID:' . $monk['id'] . '|' . $monk['certificate_number'], 
        'QRCODE,L', 
        60, 
        30, 
        20, 
        20, 
        $style
    );

    return $pdf;
}
?>

<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50 py-12 px-4">
    <div class="max-w-4xl mx-auto">
        <!-- Search Form -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-bold text-indigo-600 mb-6">ຄົ້ນຫາພຣະ</h2>
            <form method="GET" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">ຊື່-ນາມສະກຸນ</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="<?= htmlspecialchars($_GET['name'] ?? '') ?>" 
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               placeholder="ປ້ອນຊື່ຫຼືນາມສະກຸນ">
                    </div>
                    <div>
                        <label for="temple" class="block text-sm font-medium text-gray-700 mb-2">ວັດ</label>
                        <input type="text" 
                               id="temple" 
                               name="temple" 
                               value="<?= htmlspecialchars($_GET['temple'] ?? '') ?>"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               placeholder="ປ້ອນຊື່ວັດ">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" 
                                class="w-full bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 
                                       transition-colors flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <span>ຄົ້ນຫາ</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Results -->
        <?php
        if (!empty($_GET['name']) || !empty($_GET['temple'])) {
            $where = [];
            $params = [];
            
            if (!empty($_GET['name'])) {
                $where[] = "(first_name LIKE ? OR last_name LIKE ?)";
                $params[] = "%" . $_GET['name'] . "%";
                $params[] = "%" . $_GET['name'] . "%";
            }
            
            if (!empty($_GET['temple'])) {
                $where[] = "current_temple LIKE ?";
                $params[] = "%" . $_GET['temple'] . "%";
            }
            
            $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";
            $stmt = $pdo->prepare("SELECT * FROM monks {$whereClause}");
            $stmt->execute($params);
            $monks = $stmt->fetchAll();
            
            if ($monks): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php foreach ($monks as $monk): ?>
                        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
                            <div class="flex items-start space-x-4">
                                <?php if ($monk['photo']): ?>
                                    <img src="uploads/<?= htmlspecialchars($monk['photo']) ?>" 
                                         class="w-24 h-24 rounded-lg object-cover" alt="Photo">
                                <?php endif; ?>
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-800">
                                        <?= htmlspecialchars($monk['prefix'] . ' ' . $monk['first_name'] . ' ' . $monk['last_name']) ?>
                                    </h3>
                                    <p class="text-gray-600">ວັດ: <?= htmlspecialchars($monk['current_temple']) ?></p>
                                    <p class="text-gray-600">ວັນເດືອນປີເກີດ: <?= htmlspecialchars($monk['birth_date']) ?></p>
                                    
                                    <!-- Export Button -->
                                    <!-- เปลี่ยนจาก form เป็น link และเปิดในหน้าต่างใหม่ -->
                                    <a href="export_card.php?monk_id=<?= $monk['id'] ?>" 
                                       target="_blank"
                                       class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg 
                                              hover:bg-green-700 transition-colors focus:outline-none focus:ring-2 
                                              focus:ring-offset-2 focus:ring-green-500">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        ພິມບັດ
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center text-gray-500 py-8">
                    ບໍ່ພົບຂໍໍາູນ
                </div>
            <?php endif;
        }
        ?>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
// เพิ่ม keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // กด Enter ที่ช่องค้นหาให้ submit form
    if (e.key === 'Enter' && (document.activeElement.id === 'name' || document.activeElement.id === 'temple')) {
        e.preventDefault();
        document.querySelector('form[method="GET"]').submit();
    }
});

// Clear form
document.addEventListener('DOMContentLoaded', function() {
    const clearButton = document.createElement('button');
    clearButton.type = 'button';
    clearButton.className = 'text-gray-600 hover:text-gray-800 text-sm mt-2';
    clearButton.textContent = 'ລ້າງຂໍ້ມູນການຄົ້ນຫາ';
    clearButton.onclick = function() {
        document.getElementById('name').value = '';
        document.getElementById('temple').value = '';
        document.querySelector('form[method="GET"]').submit();
    };
    document.querySelector('.space-y-4').appendChild(clearButton);
});
</script>