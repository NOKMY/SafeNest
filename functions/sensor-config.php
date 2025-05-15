<?php
header('Content-Type: application/json');
require_once 'firebase-service.php';

$firebase = new FirebaseService();
$SensorConfig = $firebase->getSensorConfig();


echo json_encode([
    'SensorConfig' => $SensorConfig
]);