<?php
require_once 'firebase-service.php';

header('Content-Type: application/json');

try {
    $firebase = new FirebaseService();
    
    // Check if a value was posted
    if (isset($_POST['value'])) {
        $value = (int)$_POST['value'];
        $value = ($value == 1) ? 1 : 0;
    } else {
        // Toggle the current value if no value was provided
        $currentValue = $firebase->getMessageSwitch();
        
        // If current value is null, default to 0
        if ($currentValue === null) {
            $currentValue = 0;
        }
        
        $value = ($currentValue == 1) ? 0 : 1;
    }
    
    // Update the value
    $result = $firebase->setMessageSwitch($value);
    
    if ($result) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Message switch ' . ($value == 1 ? 'disabled' : 'enabled'),
            'value' => $value
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to update message switch'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>