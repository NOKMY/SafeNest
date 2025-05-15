<?php
session_start();
require_once 'firebase-service.php';

$firebaseService = new FirebaseService();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    error_log(json_encode([
        'type' => 'login_attempt',
        'email' => $email,
        'password_length' => strlen($password)
    ]));
    
    $result = $firebaseService->verifyUserLogin($email, $password);
    
    if ($result['status'] === 'success') {
        $_SESSION['user_id'] = $result['user']['user_id'];
        $_SESSION['role'] = $result['user']['roles'];
        echo json_encode(['status' => 'success', 'message' => 'Login successful']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $result['message']]);
    }
}
?>