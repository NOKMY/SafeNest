<?php
require_once 'firebase-service.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $roleName = $_POST['roleName'];
    $roleType = isset($_POST['roleType']) ? $_POST['roleType'] : 'non-admin';
    
    $firebaseService = new FirebaseService();
    $roles = $firebaseService->getAllRoles();
    
    // Check if trying to add an admin role
    if ($roleType === 'admin') {
        // Check if there's already a role with role_id 1
        $adminExists = false;
        foreach ($roles as $role) {
            if (isset($role['role_id']) && $role['role_id'] === 1) {
                $adminExists = true;
                break;
            }
        }
        
        if ($adminExists) {
            // Return error if admin role already exists
            echo json_encode([
                'status' => 'error', 
                'message' => 'An admin role already exists. Only one admin role is allowed.'
            ]);
            exit;
        }
        
        // Create admin role with role_id 1
        if ($firebaseService->createRoleWithId($roleName, 1, true)) {
            echo json_encode([
                'status' => 'success', 
                'message' => 'Admin role added successfully'
            ]);
        } else {
            echo json_encode([
                'status' => 'error', 
                'message' => 'Error adding admin role'
            ]);
        }
    } else {
        // For non-admin roles, continue with auto-increment
        if ($firebaseService->createRole($roleName, false)) {
            echo json_encode([
                'status' => 'success', 
                'message' => 'Role added successfully'
            ]);
        } else {
            echo json_encode([
                'status' => 'error', 
                'message' => 'Error adding role'
            ]);
        }
    }
}
?>