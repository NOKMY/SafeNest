<?php
// filepath: d:\XAMPP\htdocs\safenest\barangay\functions\past-hour-reading.php

// Start measuring execution time
$startTime = microtime(true);

// Set maximum execution time (in seconds)
$maxExecutionTime = 30; // 30 seconds, adjust as needed
set_time_limit($maxExecutionTime); // This will override PHP's default time limit

// Add memory limit if needed
ini_set('memory_limit', '256M'); // Increase memory limit if processing large datasets

header('Content-Type: application/json');
require_once 'firebase-service.php';

try {
    $firebase = new FirebaseService();
    $Past24Hours = $firebase->getPast24Hours();

    $currentTimestamp = time();
    $twentyFourHoursAgo = $currentTimestamp - (24 * 60 * 60);

    $hourlyData = [];
    $processedReadings = 0;

    // Process Firebase data
    foreach ($Past24Hours as $date => $hours) {
        foreach ($hours as $hourKey => $readings) {
            foreach ($readings as $readingKey => $reading) {
                // Check if we've exceeded our time limit
                if ((microtime(true) - $startTime) > ($maxExecutionTime * 0.9)) {
                    throw new Exception("Script approaching timeout, processing incomplete");
                }
                
                $processedReadings++;
                
                if (!isset($reading['Date']) || !isset($reading['Time']) || !isset($reading['Distance'])) {
                    continue;
                }

                $readingTimestamp = strtotime($reading['Date'] . ' ' . $reading['Time']);
                if ($readingTimestamp >= $twentyFourHoursAgo && $readingTimestamp <= $currentTimestamp) {
                    $hourFormatted = date("Y-m-d H:00:00", $readingTimestamp);

                    if (!isset($hourlyData[$hourFormatted])) {
                        $hourlyData[$hourFormatted] = [];
                    }

                    $waterLevel = floatval(str_replace(" cm", "", $reading['Distance']));
                    $hourlyData[$hourFormatted][] = $waterLevel;
                }
            }
        }
    }

    // Compute hourly averages
    $filteredData = [];
    foreach ($hourlyData as $hour => $levels) {
        $filteredData[] = [
            'time' => $hour,
            'avgWaterLevel' => round(array_sum($levels) / count($levels), 2)
        ];
    }

    // Calculate execution time
    $executionTime = microtime(true) - $startTime;

    // Send JSON response
    echo json_encode([
        'status' => 'success', 
        'data' => $filteredData,
        'meta' => [
            'execution_time' => round($executionTime, 3) . ' seconds',
            'readings_processed' => $processedReadings,
            'hours_found' => count($hourlyData)
        ]
    ]);
    
} catch (Exception $e) {
    // Handle timeouts or errors gracefully
    $executionTime = microtime(true) - $startTime;
    
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage(),
        'meta' => [
            'execution_time' => round($executionTime, 3) . ' seconds',
            'readings_processed' => $processedReadings ?? 0,
            'hours_found' => count($hourlyData ?? [])
        ]
    ]);
}
?>