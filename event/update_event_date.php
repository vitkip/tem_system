<?php
require '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $new_date = $_POST['new_date'];

    $stmt = $pdo->prepare("UPDATE events SET event_date = ? WHERE id = ?");
    $stmt->execute([$new_date, $id]);
}
?>
