<?php
session_start();
require_once 'firebase-service.php';

$firebaseService = new FirebaseService();

if (isset($_SESSION['user_id']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    
    $username = $_POST['username'];
    $fname = $_POST['fname'];
    $middle = $_POST['middle'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $mobile_number = $_POST['mobile_number'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];
    
    $userData = [
        'username' => $username,
        'fname' => $fname,
        'middle' => $middle,
        'lname' => $lname,
        'email' => $email,
        'mobile_number' => $mobile_number,
        'contact' => $contact,
        'address' => $address
    ];
    
    if ($firebaseService->updateUser($user_id, $userData)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update profile']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>