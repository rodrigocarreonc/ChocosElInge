<?php
include 'keys.php';

//Get All Products
$urlProducts = $server .'/api/product/productos';
$options = array(
    'http' => array(
        'header'  => "Content-Type: application/json\r\n" .
                     "Authorization: Bearer " . $_SESSION['access_token'] . "\r\n",
        'method'  => 'GET',
    ),
);

$context  = stream_context_create($options);
$resultProducts = file_get_contents($urlProducts, false, $context);

if ($resultProducts === FALSE) {
    $error_message = "Error fetching products.";
} else {
    $products = json_decode($resultProducts, true);
}