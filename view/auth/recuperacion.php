<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperación de Contraseña</title>
    <link rel="stylesheet" href="../styles/stylerecuperacion.css">
</head>
<body>
    <div class="recuperacion-container">
        <header class="header">
            <h1>RECUPERACIÓN DE CONTRASEÑA</h1>
        </header>

        <form action="enviarCorreo.php" method="post" class="recuperacion-form">
            <p>Ingresa tu correo institucional para enviarte la recuperación.</p>
            <label for="email">Correo Institucional</label>
            <input type="email" id="email" name="email" placeholder="Ingrese su correo institucional" required>

            <button type="submit" class="submit-btn">ENVIAR</button>
        </form>

        <div class="confirmation-message" style="display:none;">
            <p>CORREO ENVIADO, REVISE SU BANDEJA DE ENTRADA.</p>
        </div>

        <script>
            const form = document.querySelector('form');
            const confirmationMessage = document.querySelector('.confirmation-message');
            
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                confirmationMessage.style.display = 'block';
                setTimeout(function() {
                    window.location.href = 'login.php';
                }, 3000);
            });
        </script>
    </div>
</body>
</html>