<?php
require_once 'firebase-service.php';

$firebaseService = new FirebaseService();
$userId = 22;

// Delete specific user
if ($firebaseService->deleteUser($userId)) {
    echo json_encode(['status' => 'success', 'message' => 'User deleted successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to delete user']);
}