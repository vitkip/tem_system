<?php
require '../db.php';
include '../header.php';

// ดึงข้อมูล Event จากฐานข้อมูล
$stmt = $pdo->query("SELECT id, event_name, event_date, event_time FROM events ORDER BY event_date ASC");
$events = $stmt->fetchAll();

// เตรียมข้อมูลสำหรับ Calendar
$events_for_calendar = [];
foreach ($events as $event) {
    $events_for_calendar[] = [
        'id' => $event['id'],
        'title' => $event['event_name'],
        'start' => $event['event_date'] . (!empty($event['event_time']) ? 'T' . $event['event_time'] : ''),
        'allDay' => empty($event['event_time']),
    ];
}
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-indigo-700">ປະຕິທິນງານກິດນິມົນ</h1>
    <?php if (isAdmin()): ?>
        <a href="/tem_system/event/add_event.php" class="bg-indigo-600 text-white px-5 py-2 rounded hover:bg-indigo-700">
            ➕ ເພີ່ມງານກິດ
        </a>
    <?php endif; ?>
</div>

<!-- Calendar Container -->
<div id="calendar" class="bg-white p-6 rounded-lg shadow-lg"></div>

<!-- FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet" />

<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<!-- Calendar Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: "auto",
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: <?= json_encode($events_for_calendar) ?>,
        eventClick: function(info) {
            window.location.href = 'view_event.php?id=' + info.event.id;
        }
    });

    calendar.render();
});
</script>

<?php include '../footer.php'; ?>
