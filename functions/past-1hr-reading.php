<?php
header('Content-Type: application/json');
require_once 'firebase-service.php';

$firebase = new FirebaseService();
$Past1Hour = $firebase->getPast24Hours(); // Fetch latest data

$currentTimestamp = time(); // Current time (e.g., 6:34 AM)
$oneHourAgo = $currentTimestamp - (60 * 60); // 1 hour ago (e.g., 5:34 AM)

$threeMinData = [];
$totalSum = 0;
$totalCount = 0;

// Loop through past hour records
foreach ($Past1Hour as $date => $hours) { // Handling "Hour_XX" keys
    foreach ($hours as $hourKey => $readings) { // Loop through "Hour_XX"
        foreach ($readings as $readingKey => $reading) { // Loop through individual readings
            if (!isset($reading['Date']) || !isset($reading['Time']) || !isset($reading['Distance'])) {
                continue; // Skip invalid records
            }

            $readingTimestamp = strtotime("{$reading['Date']} {$reading['Time']}");

            // Keep only readings from the last 1 hour
            if ($readingTimestamp >= $oneHourAgo && $readingTimestamp <= $currentTimestamp) {
                // Group readings into 3-minute intervals
                $threeMinKey = date("Y-m-d H:i", floor($readingTimestamp / (3 * 60)) * (3 * 60)); // Round to 3-minute intervals

                if (!isset($threeMinData[$threeMinKey])) {
                    $threeMinData[$threeMinKey] = [];
                }

                // Convert "19.43 cm" to float
                $waterLevel = floatval(str_replace(" cm", "", $reading['Distance']));
                $threeMinData[$threeMinKey][] = $waterLevel;

                // Sum up for overall average
                $totalSum += $waterLevel;
                $totalCount++;
            }
        }
    }
}

// Compute 3-minute interval averages
$intervalData = [];
foreach ($threeMinData as $time => $levels) {
    $intervalData[] = [
        'time' => $time,
        'avgWaterLevel' => round(array_sum($levels) / count($levels), 2)
    ];
}

// Sort intervals from oldest to newest
usort($intervalData, function ($a, $b) {
    return strtotime($a['time']) - strtotime($b['time']);
});

// Select only the latest 20 intervals
$filteredData = array_slice($intervalData, -20);

// Calculate overall average
$overallAverage = $totalCount > 0 ? round($totalSum / $totalCount, 2) : null;

// Return JSON response
echo json_encode([
    'status' => 'success',
    'data' => $filteredData,
    'averageReading' => $overallAverage
]);
?>
