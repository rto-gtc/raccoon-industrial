<?php
header("Content-Type: application/json");

$query = isset($_GET["q"]) ? trim($_GET["q"]) : "";

if (empty($query) || strlen($query) < 3) {
    echo json_encode([]);
    exit;
}

// Pobieranie wszystkich plików HTML w katalogu
$files = glob("*.html");
$results = [];

foreach ($files as $file) {
    $content = file_get_contents($file);
    $lowerContent = mb_strtolower($content, "UTF-8");
    $lowerQuery = mb_strtolower($query, "UTF-8");

    // Sprawdzenie, czy fraza występuje w pliku
    if (strpos($lowerContent, $lowerQuery) !== false) {
        // Pobranie fragmentu zdania zawierającego frazę
        $pattern = "/(.{0,50}" . preg_quote($query, "/") . ".{0,50})/ui";
        if (preg_match($pattern, $content, $matches)) {
            $snippet = htmlspecialchars($matches[1]); // Oczyszczenie HTML

            $results[] = [
                "snippet" => "... " . $snippet . " ...",
                "url" => $file
            ];
        }
    }
}

// Zwrócenie wyników jako JSON
echo json_encode($results);
?>
