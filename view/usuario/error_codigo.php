<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Error - Código no recibido</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #fff;
            color: #111;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .error-container {
            max-width: 400px;
            text-align: center;
        }
        .error-message {
            color: red;
            margin-top: 10px;
            font-weight: bold;
            font-size: 1.1rem;
        }
        .button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #ff6b00;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s ease;
        }
        .button:hover {
            background-color: #e55a00;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>Error</h1>
        <p class="error-message">No se recibió el código de autorización.</p>
        <a href="../auth/login.php" class="button">Volver al login</a>
    </div>
</body>
</html>
