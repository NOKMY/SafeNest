<?php
header('Content-Type: application/json');
require_once 'firebase-service.php';

$firebase = new FirebaseService();
$waterLevel = $firebase->getWaterLevelData();
$waterIndicator = $firebase->getWaterIndicator();
$SensorConfig = $firebase->getSensorConfig();
$HistoricalRecords = $firebase->getHistoricalRecords();
$SensorStatus = $firebase->getSensorStatus();
$DailyRecords = $firebase->getDailyRecords(); // Fetch DailyRecords

// Get the latest date
$latestDate = array_key_last($DailyRecords);
$latestRecords = $DailyRecords[$latestDate] ?? [];

// Fetch the latest 20 records (preserving structure)
$latestData = array_slice($latestRecords, -20, 20, true);

echo json_encode([
    'waterLevel' => $waterLevel,
    'waterIndicator' => $waterIndicator,
    'SensorConfig' => $SensorConfig,
    'HistoricalRecords' => $HistoricalRecords,
    'DailyRecords' => $latestData, // Include latest 20 records
    'SensorStatus' => $SensorStatus
]);
?>
