<?php
// filepath: /f:/xampp/htdocs/safenest/barangay/functions/send-message.php

// Include Firebase service
require_once 'firebase-service.php';

// Initialize Firebase service
$firebaseService = new FirebaseService();

// Get status and water level from the request
$status = isset($_POST['status']) ? $_POST['status'] : 'Unknown';
$waterLevel = isset($_POST['waterLevel']) ? $_POST['waterLevel'] : 'Unknown';

// Define different messages for each status
switch ($status) {
    case 'Low':
        $message = "SAFENEST ADVISORY: Water level is currently Low at {$waterLevel}cm. No immediate action required, but please stay informed.";
        break;
    
    case 'Medium':
        $message = "SAFENEST ADVISORY: Water level has reached Medium at {$waterLevel}cm. Please be advised to prepare emergency supplies and stay alert for updates.";
        break;
    
    case 'High':
        $message = "SAFENEST URGENT ALERT: Water level has reached High at {$waterLevel}cm. Please prepare for possible evacuation and secure your belongings. Stay tuned for evacuation notices.";
        break;
    
    case 'Overflow':
        $message = "SAFENEST EMERGENCY ALERT: Water has OVERFLOWED at {$waterLevel}cm! MANDATORY EVACUATION is in effect. Proceed immediately to designated evacuation centers. DO NOT delay.";
        break;
    
    default:
        $message = "SAFENEST ALERT: Water level status is {$status} at {$waterLevel}cm. Please stay informed and follow safety guidelines.";
}

// Get all mobile numbers from Firebase
$mobileNumbers = $firebaseService->getAllUserMobileNumbers();

$responses = [];

if (empty($mobileNumbers)) {
    echo json_encode(['error' => 'No mobile numbers found']);
    exit;
}

// API endpoint
$url = 'https://sms.iprogtech.com/api/v1/sms_messages';

// Loop through each mobile number and send the SMS
foreach ($mobileNumbers as $mobileNumber) {
    $data = [
        'api_token' => 'c290eaf073d030942f94d6fe3dac0ec7c7f77fd5',
        'message' => $message,
        'phone_number' => $mobileNumber,
        'sms_provider' => '1',
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded'
    ]);
    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        $error = curl_error($ch);
        $responses[$mobileNumber] = ['error' => $error];
    } else {
        $responses[$mobileNumber] = json_decode($response, true);
    }

    curl_close($ch);
}

echo json_encode([
    'success' => true,
    'message' => 'SMS alerts sent',
    'details' => $responses
]);
?>