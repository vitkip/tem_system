<?php
session_start();
require 'db.php';
require 'functions.php';

header('Content-Type: application/json');

if (!isAdmin()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

if (!isset($_POST['monk_id']) || !isset($_POST['current_status'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required data']);
    exit;
}

try {
    $monk_id = intval($_POST['monk_id']);
    $current_status = $_POST['current_status'];
    $new_status = ($current_status === 'active') ? 'retired' : 'active';
    
    $stmt = $pdo->prepare("UPDATE monks SET status = ? WHERE id = ?");
    $result = $stmt->execute([$new_status, $monk_id]);
    
    if ($result) {
        echo json_encode([
            'success' => true,
            'newStatus' => $new_status,
            'message' => 'Status updated successfully'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to update status'
        ]);
    }

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}