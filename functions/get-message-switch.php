<?php
require_once 'firebase-service.php';

header('Content-Type: application/json');

try {
    $firebase = new FirebaseService();
    $value = $firebase->getMessageSwitch();
    
    // If value is null, default to 0
    if ($value === null) {
        $value = 0;
        // Initialize it in Firebase
        $firebase->setMessageSwitch(0);
    }
    
    echo json_encode([
        'status' => 'success',
        'value' => $value
    ]);
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>