<?php
require_once 'firebase-service.php';
$firebaseService = new FirebaseService();

// Fetch all users
$users = $firebaseService->getAllUsers();
if ($users) {
    echo json_encode($users, JSON_PRETTY_PRINT);
} else {
    echo "No users found.";
}
