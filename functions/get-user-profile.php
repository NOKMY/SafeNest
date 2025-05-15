<?php
session_start();
require_once 'firebase-service.php';

$firebaseService = new FirebaseService();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    $user = $firebaseService->getUserById($user_id);
    
    if ($user) {
        unset($user['password']); 
        unset($user['user_id']);
        echo json_encode($user);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'User not found']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
}
?>