<?php
include 'keys.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $url = $server .'/api/auth/login';
    $data = array('username' => $username, 'password' => $password);

    $options = array(
        'http' => array(
            'header'  => "Content-Type: application/json\r\n",
            'method'  => 'POST',
            'content' => json_encode($data),
        ),
    );

    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result === FALSE) {
        $error_message = "Error logging in.";
    } else {
        $response = json_decode($result, true);
        if (isset($response['access_token'])) {
            $_SESSION['access_token'] = $response['access_token'];
            header('Location: index.php');
            exit();
        } else {
            $error_message = "Invalid username or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chocos "El Inge" - Login</title>
    <link rel="stylesheet" href="style/login.css">
</head>
<body>
    <div class="login-container">
        <h1>Chocos <br> "El Inge"</h1>
        <form id="login-form" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Login</button>
        </form>
        <p id="error-message" class="error">
            <?php if (isset($error_message)) echo $error_message; ?>
        </p>
    </div>
</body>
</html>