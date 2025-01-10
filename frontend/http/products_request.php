<?php
include ('config/keys.php');

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

//Create Product
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];

    // Configura el endpoint de egreso
    $url = $server.'/api/product/productos';

    // Datos a enviar en formato JSON
    $data = [
        'nombre' => $nombre,
        'precio' => $precio,
        'stock' => $stock
    ];

    // Obtén el token de la sesión
    $token = $_SESSION['access_token'];

    // Inicializa cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    // Ejecuta la solicitud y obtiene la respuesta
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Decodifica la respuesta JSON
    if ($response === false) {
        $error = 'Error en el servidor';
    } else {
        $result = json_decode($response, true);

        if ($http_code === 201) {
            $message = $result['message'];
            $egreso = $result['egreso'];
            // Aquí puedes manejar la respuesta exitosa, por ejemplo, mostrar un mensaje o redirigir
            header('Location: admin.php');
        } else {
            $error = 'Error al crear el egreso';
        }
    }
}