<?php
require_once 'firebase-service.php';

$firebaseService = new FirebaseService();
$count = $firebaseService->getUsersCount();
echo json_encode(['count' => $count]);
?>