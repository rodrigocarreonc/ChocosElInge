<?php
include ('config/keys.php');

//Get All Flavors
$urlTastes = $server .'/api/taste/sabores';
$options = array(
    'http' => array(
        'header'  => "Content-Type: application/json\r\n" .
                     "Authorization: Bearer " . $_SESSION['access_token'] . "\r\n",
        'method'  => 'GET',
    ),
);

$context  = stream_context_create($options);
$resultTastes = file_get_contents($urlTastes, false, $context);

if ($resultTastes === FALSE) {
    $error_message = "Error fetching products.";
} else {
    $flavors = json_decode($resultTastes, true);
}