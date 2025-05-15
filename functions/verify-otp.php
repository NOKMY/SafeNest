<?php
require_once 'firebase-service.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $otp = htmlspecialchars($_POST['otp'], ENT_QUOTES, 'UTF-8');
    
    $firebaseService = new FirebaseService();
    
    // Fetch all users to find the user with the given email and OTP
    $users = $firebaseService->getAllUsers();
    $userFound = false;
    
    foreach ($users as $user) {
        if ($user['email'] === $email && $user['otp_code'] === $otp) {
            $userFound = true;
            break;
        }
    }
    
    if ($userFound) {
        echo json_encode(['status' => 'success', 'email' => $email]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid OTP']);
    }
}
?>