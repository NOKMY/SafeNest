<?php
require_once 'firebase-service.php';

$firebaseService = new FirebaseService();

if (isset($_POST['role_id'])) {
    $role_id = $_POST['role_id'];
    
    $role = $firebaseService->getRoleById($role_id);
    
    if ($role) {
        echo json_encode($role);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Role not found']);
    }
}
?>