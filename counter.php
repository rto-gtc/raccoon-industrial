<?php
$counterFile = 'counter.json';

$userIP = $_SERVER['REMOTE_ADDR'];
$cookieName = 'visitor_id';
$visitorID = isset($_COOKIE[$cookieName]) ? $_COOKIE[$cookieName] : null;

if (file_exists($counterFile)) {
    $data = json_decode(file_get_contents($counterFile), true);
} else {
    $data = [
        'total_visits' => 0,
        'unique_visitors' => 0,
        'returning_visitors' => 0,
        'ips' => []
    ];
}

$data['total_visits']++;

if (!isset($data['ips'][$userIP])) {
    $data['ips'][$userIP] = time();
    $data['unique_visitors']++;
} else {
    if (!$visitorID) {
        $data['returning_visitors']++;
    }
}

if (!$visitorID) {
    setcookie($cookieName, uniqid(), time() + (30 * 24 * 60 * 60), "/");
}

file_put_contents($counterFile, json_encode($data));

header('Content-Type: application/json');
echo json_encode($data);
?>
