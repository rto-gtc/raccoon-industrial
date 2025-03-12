<?php
// Przykładowa wartość zużycia energii (możesz pobierać ją z formularza lub innego źródła)
$energy = 100; // wartość w kWh

// Przygotowanie danych do wysłania (przykład dla kalkulacji emisji dla elektryczności)
$data = [
    "type" => "electricity",
    "electricity_unit" => "kwh",
    "electricity_value" => $energy
];

$jsonData = json_encode($data);

// Ustawienia URL oraz klucza API
$url = "https://api.carboninterface.com/v1/estimates";
$apiKey = "X9LSCPuL9RAIUfvr5okhA";

// Przygotowanie nagłówków zgodnie z wymaganiami
$headers = [
    "Authorization: Bearer $apiKey",
    "Content-Type: application/json",
    "Referer: https://raccoon24.pl/",
    "sec-ch-ua: \"Not(A:Brand\";v=\"99\", \"Google Chrome\";v=\"133\", \"Chromium\";v=\"133\"",
    "sec-ch-ua-mobile: ?0",
    "sec-ch-ua-platform: \"Windows\""
];

// Inicjalizacja cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// Wykonanie zapytania
$response = curl_exec($ch);

// Obsługa ewentualnych błędów
if (curl_errno($ch)) {
    echo "cURL error: " . curl_error($ch);
} else {
    $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    echo "HTTP status: " . $httpStatus . "\n";
    echo "Response: " . $response;
}

curl_close($ch);
?>
