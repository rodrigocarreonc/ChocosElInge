<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chocos "El Inge" - Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #fbbf24, #f59e0b);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        h1 {
            text-align: center;
            color: #1f2937;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-top: 1rem;
            color: #4b5563;
        }
        input {
            padding: 0.5rem;
            margin-top: 0.25rem;
            border: 1px solid #d1d5db;
            border-radius: 4px;
        }
        button {
            margin-top: 1rem;
            padding: 0.5rem;
            background-color: #f59e0b;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #d97706;
        }
        .error {
            color: #dc2626;
            margin-top: 1rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Chocos "El Inge"</h1>
        <form action="login.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Login</button>
        </form>
        <?php
        session_start();
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $_POST['username'];
            $password = $_POST['password'];
            
            // This is a simple check. In a real application, you'd validate against a database.
            if ($username === 'admin' && $password === 'password') {
                $_SESSION['user'] = $username;
                header("Location: sales.php");
                exit();
            } else {
                echo "<p class='error'>Invalid username or password</p>";
            }
        }
        ?>
    </div>
</body>
</html>