<?php
require_once 'firebase-service.php';

$firebaseService = new FirebaseService();

try {
    $roles = $firebaseService->getAllRoles();
    
    // Return all roles without filtering
    echo json_encode(array_values($roles));
} catch (\Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>