<?php
include ('config/keys.php');

//Get all sales
$token = $_SESSION['access_token'];

$url = 'http://127.0.0.1:8000/api/sale/ventas';
$options = array(
    'http' => array(
        'header'  => "Content-Type: application/json\r\n" .
                     "Authorization: Bearer " . $token . "\r\n",
        'method'  => 'GET',
    ),
);

$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);

if ($result === FALSE) {
    $error_message = "Error fetching sales.";
} else {
    $sales = json_decode($result, true);
}