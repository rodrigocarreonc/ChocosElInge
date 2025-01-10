<?php
include ('config/keys.php');

//Get Balance
$urlBalance = $server .'/api/finance/balance';
$options = array(
    'http' => array(
        'header'  => "Content-Type: application/json\r\n" .
                     "Authorization: Bearer " . $_SESSION['access_token'] . "\r\n",
        'method'  => 'GET',
    ),
);

$context  = stream_context_create($options);
$resultBalance = file_get_contents($urlBalance, false, $context);

if ($resultBalance === FALSE) {
    $error_message = "Error fetching products.";
} else {
    $balance = json_decode($resultBalance, true);
}

//Get All Outflows
$urlOutflow = $server .'/api/finance/egreso';
$options = array(
    'http' => array(
        'header'  => "Content-Type: application/json\r\n" .
                     "Authorization: Bearer " . $_SESSION['access_token'] . "\r\n",
        'method'  => 'GET',
    ),
);

$context  = stream_context_create($options);
$resultOutflows = file_get_contents($urlOutflow, false, $context);

if ($resultOutflows === FALSE) {
    $error_message = "Error fetching products.";
} else {
    $outflows = json_decode($resultOutflows, true);
}

// Create Outflow
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $concepto = $_POST['concepto'];
    $monto = $_POST['monto'];

    // Configura el endpoint de egreso
    $url = $server.'/api/finance/egreso';

    // Datos a enviar en formato JSON
    $data = [
        'concepto' => $concepto,
        'monto' => $monto
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
            header('Location: finance.php');
        } else {
            $error = 'Error al crear el egreso';
        }
    }
}