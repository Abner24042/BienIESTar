<?php
session_start();

// Si ya está logueado, redirigir al landing
if (isset($_SESSION['nombre'])) {
    header("Location: ../usuario/landingpage.php");
    exit();
}

require_once __DIR__ . '/../../controllers/google_config.php';
$google_login_url = $client->createAuthUrl();
?>
<!DOCTYPE html>
<html lang="es">    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="../styles/stylelogin.css">


</head>
<body>
    <div class="login-container">
        <header class="header">
            <h1>INICIAR SESIÓN</h1>
        </header>

        <!-- Formulario de inicio de sesión -->
        <form action="../../controllers/verificarlogin.php" method="post" class="login-form">
            <label for="email">INGRESE SU CORREO INSTITUCIONAL</label>
            <input type="email" id="email" name="email" required>

            <label for="password">INGRESA LA CONTRASEÑA</label>
            <input type="password" id="password" name="password" required>

            <button type="submit" class="submit-btn">INGRESAR</button>

            <?php if (isset($_GET['error'])): ?>
                <p style="color: red; margin-top: 10px;">⚠️ <?php echo htmlspecialchars($_GET['error']); ?></p>
            <?php endif; ?>
        </form>

        <!-- Opción para inicio de sesión con Google -->
        <div class="google-login" style="margin-top: 20px;">
            <a href="<?php echo $google_login_url; ?>" class="google-btn" style="display: flex; align-items: center; justify-content: center; padding: 10px 20px; border: 1px solid #ddd; border-radius: 5px; text-decoration: none;">
                <img src="360_F_518093233_bYlgthr8ZLyAUQ3WryFSSSn3ruFJLZHM-removebg-preview.png" alt="Google" style="width: 20px; margin-right: 10px;">
                Iniciar sesión con Google
            </a>
        </div>

        <!-- Enlace de recuperación de contraseña -->
        <div class="forgot-password" style="margin-top: 15px; font-size: 14px;">
            <a href="recuperacion.php">¿Olvidaste tu contraseña? Recupérala.</a>
        </div>


    </div>
</body>
</html>