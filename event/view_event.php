<?php
require '../db.php';
include '../header.php';

// เช็ก event_id
if (!isset($_GET['id'])) {
    echo "<div class='text-center text-red-500 mt-10 text-2xl font-bold'>ບໍ່ພົບຂໍ້ມູນງານ</div>";
    exit();
}
$event_id = $_GET['id'];

// ดึงข้อมูลงาน
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$event_id]);
$event = $stmt->fetch();

// ดึงพระที่ไปงาน
$stmt = $pdo->prepare("SELECT m.prefix, m.first_name, m.last_name 
                       FROM event_monk em
                       JOIN monks m ON em.monk_id = m.id
                       WHERE em.event_id = ?");
$stmt->execute([$event_id]);
$monks = $stmt->fetchAll();

// Add Font Awesome for icons
echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">';
?>

<div class="max-w-5xl mx-auto px-4 py-8">
    <!-- Event Card -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Header Section with Gradient -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-8 text-white">
            <h1 class="text-3xl font-bold text-center mb-4">
                <?= htmlspecialchars($event['event_name']) ?>
            </h1>
        </div>

        <!-- Event Details -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Left Column: Event Info -->
                <div class="space-y-4">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">ລາຍລະອຽດງານ</h2>
                    
                    <div class="flex items-center space-x-3 text-gray-700">
                        <i class="fas fa-calendar-alt text-indigo-600"></i>
                        <span><strong>ວັນທີ:</strong> <?= date('d/m/Y', strtotime($event['event_date'])) ?></span>
                    </div>

                    <div class="flex items-center space-x-3 text-gray-700">
                        <i class="fas fa-clock text-indigo-600"></i>
                        <span><strong>ເວລາ:</strong> <?= htmlspecialchars($event['event_time']) ?></span>
                    </div>

                    <div class="flex items-center space-x-3 text-gray-700">
                        <i class="fas fa-map-marker-alt text-indigo-600"></i>
                        <span><strong>ສະຖານທີ່:</strong> <?= htmlspecialchars($event['location']) ?></span>
                    </div>
                </div>

                <!-- Right Column: Monks List -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-users text-indigo-600 mr-2"></i>
                        ລາຍຊື່ພຣະສົງທີ່ໄປຮ່ວມ
                    </h2>

                    <?php if (count($monks) > 0): ?>
                        <div class="space-y-3">
                            <?php foreach ($monks as $monk): ?>
                                <div class="flex items-center space-x-3 p-2 hover:bg-gray-100 rounded-lg transition-colors">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-user-circle text-gray-400 text-xl"></i>
                                    </div>
                                    <span class="text-gray-700">
                                        <?= htmlspecialchars($monk['prefix'] . ' ' . $monk['first_name'] . ' ' . $monk['last_name']) ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-users-slash text-4xl mb-3"></i>
                            <p>ບໍ່ມີການເພີ່ມພຣະສົງເຂົ້າຮ່ວມ</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-4 mt-8">
                <?php if (isAdmin()): ?>
                    <a href="<?= BASE_URL ?>event/assign_monks.php?event_id=<?= $event_id ?>" 
                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-yellow-500 to-yellow-600 
                              text-white rounded-lg hover:from-yellow-600 hover:to-yellow-700 
                              transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-user-plus mr-2"></i>
                        ເລືອກພຣະສົງເຂົ້າຮ່ວມ
                    </a>
                <?php endif; ?>
                
                <a href="<?= BASE_URL ?>event/list_events.php" 
                   class="inline-flex items-center px-6 py-3 border-2 border-indigo-600 
                          text-indigo-600 rounded-lg hover:bg-indigo-50 
                          transition-all duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    ກັບໄປລາຍການ
                </a>
            </div>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>
