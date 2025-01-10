<?php
include ('config/keys.php');

// Login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Configura el endpoint de autenticación
    $url = $server.'/api/auth/login';

    // Datos a enviar en formato JSON
    $data = [
        'username' => $username,
        'password' => $password
    ];

    // Inicializa cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
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

        if ($http_code === 200 && isset($result['access_token'])) {
            $_SESSION['access_token'] = $result['access_token'];
            header('Location: index.php');
            exit;
        } elseif ($http_code === 401) {
            $error = 'Credenciales inválidas';
        } else {
            $error = 'Error en el servidor';
        }
    }
}