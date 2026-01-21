<?php
session_start();
$usuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Usuario';
$paginaActual = basename($_SERVER['PHP_SELF']);

$conn = new mysqli("localhost", "root", "", "sistema_usuarios");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$mensajeExito = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $apellido = $conn->real_escape_string($_POST['apellido']);
    $correo = $conn->real_escape_string($_POST['correo']);
    $mensaje = $conn->real_escape_string($_POST['mensaje']);

    $sql = "INSERT INTO mensajes_contacto (nombre, apellido, correo, mensaje)
            VALUES ('$nombre', '$apellido', '$correo', '$mensaje')";

    if ($conn->query($sql)) {
        $mensajeExito = "¡Tu mensaje fue enviado con éxito!";
    } else {
        $mensajeExito = "Hubo un error al enviar tu mensaje.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nosotros | Bienestar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            background-color: #fff;
            color: #111;
        }

        header {
            background-color: #ff6b00;
            color: white;
            padding: 10px 20px;
            text-align: center;
        }

        header h1 span {
            color: black;
        }

        .navbar-links {
            display: flex;
            gap: 30px;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            margin-top: 10px;
        }

        .navbar-links a {
            color: black;
            text-decoration: none;
            font-weight: 500;
            padding: 6px 14px;
            border-radius: 6px;
            transition: background 0.3s;
        }

        .navbar-links a.activo {
            background-color: #000;
            color: white;
        }

        .navbar-links a:hover:not(.activo) {
            background-color: rgba(0,0,0,0.1);
        }

        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .about-section {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            align-items: flex-start;
        }

        .about-text {
            flex: 1;
            min-width: 280px;
        }

        .about-logo {
            flex: 1;
            min-width: 250px;
        }

        .about-logo img {
            width: 100%;
            max-width: 300px;
            border-radius: 10px;
        }

        h2 {
            font-size: 36px;
            color: #ff6b00;
            margin-bottom: 10px;
        }

        .contact-form {
            margin-top: 40px;
        }

        .contact-form h3 {
            font-size: 22px;
            margin-bottom: 20px;
        }

        .form-row {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .form-group {
            flex: 1;
            min-width: 200px;
            margin-bottom: 20px;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        textarea {
            resize: vertical;
            height: 120px;
        }

        button {
            background-color: black;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
        }

        .mensaje-exito {
            background: #e0ffe0;
            border: 1px solid #00aa00;
            color: #006600;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 6px;
        }

        footer {
            text-align: center;
            padding: 30px 20px 20px;
            font-size: 14px;
            color: #555;
        }

        .socials {
            margin-top: 10px;
            font-size: 18px;
        }

        @media (max-width: 768px) {
            .about-section {
                flex-direction: column;
                text-align: center;
            }
        }
        /* Botón hamburguesa */
.hamburger {
  display: none;
  width: 40px;
  height: 34px;
  position: absolute;
  left: 14px;
  top: 14px;
  background: transparent;
  border: 0;
  padding: 0;
  cursor: pointer;
  z-index: 1002;
}
.hamburger span {
  display: block;
  height: 3px;
  margin: 6px 0;
  background: #000;
  border-radius: 2px;
  transition: transform .25s, opacity .25s;
}

/* Menú lateral */
.side-nav {
  position: fixed;
  inset: 0 auto 0 0;
  width: 260px;
  background: #fff;
  border-right: 1px solid #eee;
  transform: translateX(-100%);
  transition: transform .3s ease;
  padding: 60px 16px; /* <— Aumenta padding-top para bajar los links */
  z-index: 1001;
  overflow-y: auto;
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  gap: 18px; /* Espaciado entre opciones */
}
.side-nav a {
  display: block;
  padding: 12px 8px;
  border-radius: 8px;
  text-decoration: none;
  color: #111;
  font-weight: 600;
}
.side-nav a:hover { background: rgba(0,0,0,.06); }

/* Fondo difuminado */
.overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,.35);
  opacity: 0;
  visibility: hidden;
  transition: opacity .25s, visibility .25s;
  z-index: 1000;
}

/* Estado abierto */
body.menu-open .side-nav { transform: translateX(0); }
body.menu-open .overlay { opacity: 1; visibility: visible; }
body.menu-open { overflow: hidden; }

/* Mostrar botón en móvil */
@media (max-width: 900px) {
  .navbar-links { display: none !important; }
  .hamburger { display: block; }
}

/* Animación del botón */
body.menu-open .hamburger span:nth-child(1) {
  transform: translateY(9px) rotate(45deg);
}
body.menu-open .hamburger span:nth-child(2) {
  opacity: 0;
}
body.menu-open .hamburger span:nth-child(3) {
  transform: translateY(-9px) rotate(-45deg);
}
    </style>
</head>
<body>

<header>
    <h1><b>BIEN<span>IEST</span>AR</b></h1>
    <div class="navbar-links">
        <a href="landingpage.php" class="<?= $paginaActual == 'landingpage.php' ? 'activo' : '' ?>">Home</a>
        <a href="alimentacion.php" class="<?= $paginaActual == 'alimentacion.php' ? 'activo' : '' ?>">Alimentación</a>
        <a href="saludmental.php" class="<?= $paginaActual == 'saludmental.php' ? 'activo' : '' ?>">Salud Mental</a>
        <a href="ejercicio.php" class="<?= $paginaActual == 'ejercicio.php' ? 'activo' : '' ?>">Ejercicio</a>
        <a href="noticias.php" class="<?= $paginaActual == 'noticias.php' ? 'activo' : '' ?>">Noticias</a>
        <a href="perfil.php" class="<?= $paginaActual == 'perfil.php' ? 'activo' : '' ?>">Perfil</a>
        <a href="logout.php">Cerrar Sesión</a>
    </div>
</header>

<div class="container">
    <div class="about-section">
        <div class="about-text">
            <h2>NOSOTROS</h2>
            <p><strong>Tu bienestar es nuestra prioridad. ¡Explora, aprende y crece con nosotros!</strong></p>
            <p>
                En BIENIESTAR, nos preocupamos por el bienestar integral de nuestra comunidad. 
                Nuestra plataforma está diseñada especialmente para los trabajadores del IEST Anáhuac, 
                brindando acceso a recursos y herramientas en tres áreas clave:
            </p>
            <ul>
                <li><strong>Alimentación:</strong> Recetas saludables, nutrición balanceada y consejos prácticos.</li>
                <li><strong>Salud Mental:</strong> Estrategias para el estrés, bienestar emocional y equilibrio personal.</li>
                <li><strong>Deporte y Movilidad:</strong> Rutinas de ejercicio, movilidad y vida activa.</li>
            </ul>
            <p>
                En BIEN IEST AR, creemos que el bienestar no es un destino, sino un camino que se recorre día a día. 
                Por eso, ponemos a tu disposición herramientas prácticas y contenido de valor para ayudarte a mejorar 
                tu calidad de vida.
            </p>
        </div>
        <div class="about-logo">
            <img src="../../img/nosotros.png" alt="Logo Bienestar">
        </div>
    </div>

    <div class="contact-form">
        <h3>Contáctanos</h3>

        <?php if ($mensajeExito): ?>
            <div class="mensaje-exito"><?= $mensajeExito ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-row">
                <div class="form-group">
                    <input type="text" placeholder="Nombre" name="nombre" required>
                </div>
                <div class="form-group">
                    <input type="text" placeholder="Apellido" name="apellido" required>
                </div>
            </div>
            <div class="form-group">
                <input type="email" placeholder="Dirección de Correo" name="correo" required>
            </div>
            <div class="form-group">
                <textarea name="mensaje" placeholder="Escribe tu mensaje o duda" required></textarea>
            </div>
            <button type="submit">ENVIAR</button>
        </form>
    </div>
</div>

<footer>
    BIEN IEST AR
</footer>

</body>
</html>