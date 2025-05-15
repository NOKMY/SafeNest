<?php
require_once 'firebase-service.php';

function getUserAvatar($userId) {
    $firebaseService = new FirebaseService();
    $user = $firebaseService->getUserAvatar($userId);
    
    return $user['avatar'] ?? '../static/assets/img/blank-profile-picture-973460_640.png';
}
?>