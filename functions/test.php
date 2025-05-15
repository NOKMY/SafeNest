<?php
$url = "https://safenest-database-default-rtdb.asia-southeast1.firebasedatabase.app/WaterLevel/Distance.json";
$response = file_get_contents($url);
echo $response;