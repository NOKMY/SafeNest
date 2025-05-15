<?php
require_once 'firebase-service.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $role_id = $_POST['role_id'];
    $role_name = $_POST['role_name'];
    
    $firebaseService = new FirebaseService();
    
    $updateData = ['role_name' => $role_name];
    
    if ($firebaseService->updateRole($role_id, $updateData)) {
        echo json_encode(['status' => 'success', 'message' => 'Role updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update role']);
    }
}
?>