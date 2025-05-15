<?php
header('Content-Type: application/json');
require_once 'firebase-service.php';

try {
    $firebaseService = new FirebaseService();
    $result = $firebaseService->getAllNodesData();
    
    echo json_encode($result, JSON_PRETTY_PRINT);
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage(),
        'timestamp' => date('Y-m-d H:i:s')
    ]);
}