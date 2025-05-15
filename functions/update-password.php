<?php
require_once 'firebase-service.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $firebaseService = new FirebaseService();
    
    // Fetch all users to find the user with the given email
    $users = $firebaseService->getAllUsers();
    $userFound = false;
    
    foreach ($users as $userId => $user) {
        if ($user['email'] === $email) {
            $userFound = true;
            $updateData = [
                'password' => $password,
                'otp_code' => null
            ];
            
            if ($firebaseService->updateUser($userId, $updateData)) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update password']);
            }
            break;
        }
    }
    
    if (!$userFound) {
        echo json_encode(['status' => 'error', 'message' => 'User not found']);
    }
}
?>