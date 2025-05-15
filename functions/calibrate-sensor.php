<?php
header('Content-Type: application/json');
require_once 'firebase-service.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}


$data = json_decode(file_get_contents('php://input'), true);

// Validate required fields
$required = ['High', 'Medium', 'Low', 'SensorHeight'];
foreach ($required as $field) {
    if (!isset($data[$field]) || !is_numeric($data[$field])) {
        http_response_code(400);
        echo json_encode(['error' => "Missing or invalid field: {$field}"]);
        exit;
    }
}

try {
    $firebase = new FirebaseService();
    $result = $firebase->updateSensorConfig($data);
    
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Sensor configuration updated successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update sensor configuration']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}