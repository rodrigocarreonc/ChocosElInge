<?php
include('config/keys.php');
// Obtener datos del usuario
$urlUser = $server.'/api/auth/me';
$chUser = curl_init($urlUser);
curl_setopt($chUser, CURLOPT_RETURNTRANSFER, true);
curl_setopt($chUser, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $token,
]);
$responseUser = curl_exec($chUser);
curl_close($chUser);
$user = json_decode($responseUser, true);

// Verificar si el token es válido si no redirigir a login
if (isset($user['error']) || !$user) {
    header('Location: login.php');
    exit;
}