<?php
require_once 'firebase-service.php';

$firebaseService = new FirebaseService();

function reindexArray($array) {
    if (!is_array($array)) {
        return $array;
    }
    
    // If it's an associative array (object-like), preserve keys
    if (array_keys($array) !== range(0, count($array) - 1)) {
        $result = [];
        foreach ($array as $key => $value) {
            if ($value !== null) {
                $result[$key] = reindexArray($value);
            }
        }
        return $result;
    }
    
    // For sequential arrays, remove nulls and reindex
    $filtered = array_values(array_filter($array, function($value) {
        return $value !== null;
    }));
    
    return array_map(function($value) {
        return is_array($value) ? reindexArray($value) : $value;
    }, $filtered);
}

// Handle both GET and POST requests
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $startDate = $_POST['startDate'] ?? '';
    $endDate = $_POST['endDate'] ?? '';
    
    $structure = $firebaseService->getNodeStructure('DailyRecords');
    $dbStructure = reindexArray($structure);

    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'success',
        'data' => ['DailyRecords' => $dbStructure]
    ], JSON_PRETTY_PRINT);
} else {
    // Original code for GET requests
    $nodes = [
        'DailyRecords'
    ];

    $dbStructure = [];

    foreach ($nodes as $node) {
        $structure = $firebaseService->getNodeStructure($node);
        $dbStructure[$node] = reindexArray($structure);
    }

    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'success',
        'data' => $dbStructure
    ], JSON_PRETTY_PRINT);
}