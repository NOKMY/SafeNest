<?php
require_once 'firebase-service.php';
$firebaseService = new FirebaseService();

// Create a new role
$roleName = 'Tanod'; // Replace with the actual role name
if ($firebaseService->createRole($roleName)) {
    echo "Role created successfully.";
} else {
    echo "Failed to create role.";
}
