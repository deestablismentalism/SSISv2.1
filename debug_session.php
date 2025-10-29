<?php
session_start();
header('Content-Type: application/json');

$debug = [
    'session_status' => session_status(),
    'session_id' => session_id(),
    'session_data' => $_SESSION,
    'session_name' => session_name(),
    'cookie_params' => session_get_cookie_params()
];

echo json_encode($debug, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
?>