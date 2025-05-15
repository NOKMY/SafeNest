<?php
require_once 'firebase-service.php';

$firebaseService = new FirebaseService();

$user = [
    'fname' => 'Brandon Kyle',
    'lname' => 'Rojas',
    'middle' => 'Craft',
    'email' => 'brandonkylerojas2@gmail.com',
    'roles' => '2',
    'password' => 'brandon123',
    'username' => 'brandonkyle',
    'status' => 'active',
    'address' => 'lunzuran',
    'contact' => '123-456-7890',
    'avatar' => 'path/to/avatar.png',
    'code' => '',
    'otp_code' => '',
    'mobile_number' => '09060440131',
    'lock_status' => 'none'
];

if ($firebaseService->createUser($user)) {
    echo "User created successfully.";
} else {
    echo "Failed to create user.";
}
?>