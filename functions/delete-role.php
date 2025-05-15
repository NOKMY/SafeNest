<?php
require_once 'firebase-service.php';

if (isset($_POST['role_id'])) {
    $role_id = $_POST['role_id'];
    
    $firebaseService = new FirebaseService();
    
    if ($firebaseService->deleteRole($role_id)) {
        echo json_encode(['status' => 'success', 'message' => 'Role deleted successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete role']);
    }
}
?>