<?php
session_start();
include ('http/auth_request.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chocos "El Inge" - Login</title>
    <link rel="stylesheet" href="style/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <div class="login-container">
        <h1>Chocos <br> "El Inge"</h1>
        <form id="login-form" method="POST">
            <label for="username">Username:</label>
            <input class="user" type="text" id="username" name="username" required>
            
            <div class="password-box">
                <label for="password">Password:</label><br>
                <input class="pass" type="password" id="password" name="password" required>
                <i class="fa fa-eye" style="font-size: 20px;"></i>
            </div>
            <button type="submit">Login</button>
        </form>
        <p id="error-message" class="error">
            <?php if (isset($error)) echo $error; ?>
        </p>
    </div>
    <script>
        const eye = document.querySelector('.fa-eye');
        const password = document.querySelector('input[type="password"]');

        eye.addEventListener('click', () => {
            if (password.type === 'password') {
                password.type = 'text';
                eye.classList.add('fa-eye-slash');
                eye.classList.remove('fa-eye');
            } else {
                password.type = 'password';
                eye.classList.remove('fa-eye-slash');
                eye.classList.add('fa-eye');
            }
        });
    </script>
</body>
</html>