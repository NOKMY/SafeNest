<?php
function getWeatherData() {
    $apiKey = "a69cdf43f8e3214c5216e301bed7ca8d";

    $lat = "6.9103";
    $lon = "122.0739";
    $city = "Zamboanga City";

    $apiUrl = "https://api.openweathermap.org/data/2.5/weather?lat={$lat}&lon={$lon}&appid={$apiKey}&units=metric";

    try {
        $response = file_get_contents($apiUrl);
        
        if ($response === false) {
            return array('error' => 'Failed to fetch weather data');
        }

        $weatherData = json_decode($response, true);

        if ($weatherData && isset($weatherData['main'])) {
            return array(
                'success' => true,
                'city' => $city,
                'temperature' => round($weatherData['main']['temp']),
                'description' =>  ucwords($weatherData['weather'][0]['description']), 
                'humidity' => $weatherData['main']['humidity'],
                'windSpeed' => $weatherData['wind']['speed']
            );
        } else {
            return array('error' => 'Invalid weather data format');
        }
    } catch (Exception $e) {
        return array('error' => 'Error fetching weather data');
    }
}

$weather = getWeatherData();
if (isset($weather['error'])) {
    http_response_code(500);
    echo json_encode(['error' => $weather['error']]);
} else {
    http_response_code(200);
    echo json_encode($weather);
}
?>