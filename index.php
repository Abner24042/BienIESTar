<?php
session_start();
$usuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Usuario';
$paginaActual = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bienestar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { box-sizing: border-box; }
body {
    font-family: 'Segoe UI', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #fff;
    color: #111;
}
header {
    background-color: #ff6b00;
    color: white;
    padding: 10px 20px;
    text-align: center;
}
header h1 span { color: black; }
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
.user-info {
    text-align: right;
    font-size: 14px;
    margin: 5px 20px 10px;
    color: #444;
}

/* Contenedor centrado con ancho limitado */
.container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 20px;
}

.section h2 {
    margin-top: 40px;
    margin-bottom: 20px;
}

.hero {
    width: 100%;
    border-radius: 8px;
    margin-top: 15px;
}

.grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 20px;
}

.card {
    background-color: #f7f7f7;
    border-radius: 8px;
    overflow: hidden;
    text-align: center;
}

.card img {
    width: 100%;
    height: 170px;
    object-fit: cover;
}

.card p {
    margin: 10px;
    font-weight: bold;
}

/* --- TEST SECTION MODIFIED --- */
.test-section {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    max-width: 1000px;
    margin: auto;
}

.test-section > div {
    flex: 1;
}

.test-section img {
    width: 450px;
    height: auto;
    border-radius: 10px;
    object-fit: contain;
}

.test-section button {
    margin-top: 10px;
    padding: 10px 20px;
    background-color: black;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

footer {
    text-align: center;
    font-size: 14px;
    padding: 20px;
    color: #555;
}

@media screen and (max-width: 768px) {
    .test-section {
        flex-direction: column;
        text-align: center;
    }
}

.card-button {
    background-color: #f7f7f7;
    border: none;
    border-radius: 8px;
    padding: 0;
    cursor: pointer;
    text-align: center;
    overflow: hidden;
    width: 100%;
    box-shadow: 0 0 8px rgba(0,0,0,0.1);
    transition: box-shadow 0.3s ease;
}

.card-button:hover {
    box-shadow: 0 0 15px rgba(0,0,0,0.2);
}

.card-button img {
    width: 100%;
    height: auto;
    object-fit: contain;
    object-position: center;
    display: block;
    border-radius: 8px 8px 0 0;
    background-color: #f7f7f7; /* Opcional */
}

.card-button p {
    margin: 10px 0;
    font-weight: bold;
    color: #111;
    padding: 0 10px 15px;
}

    </style>
</head>
<body>

<header>
    <h1><b>BIEN<span>IEST</span>AR</b></h1>
    <div class="navbar-links">
        <a href="/view\auth\login.php" class="<?= $paginaActual == 'alimentacion.php' ? 'activo' : '' ?>">Alimentación</a>
        <a href="/view\auth\login.php" class="<?= $paginaActual == 'saludmental.php' ? 'activo' : '' ?>">Salud Mental</a>
        <a href="/view\auth\login.php" class="<?= $paginaActual == 'ejercicio.php' ? 'activo' : '' ?>">Ejercicio</a>
        <a href="/view\auth\login.php" class="<?= $paginaActual == 'noticias.php' ? 'activo' : '' ?>">Noticias</a>
    </div>
</header>


<div class="container">
    <!-- Aquí inicia el contenido visual de la página -->
    <div class="grid">
    <button class="card-button" onclick="window.location.href='/view/auth/login.php'">
            <img src="/img\frenchlp1.png" alt="French Toast">
            <p>FRENCH TOAST</p>
        </button>
        <button class="card-button" onclick="window.location.href='/view/auth/login.php'">
            <img src="/img\calabazalp2.png" alt="Calabacitas">
            <p>CALABACITAS CON TANTITA QUESITO Y VERDURAS</p>
        </button>
        <button class="card-button" onclick="window.location.href='/view/auth/login.php'">
            <img src="/img\tacuacheslp3.png" alt="Tacos de pescado">
            <p>TACOS DE PESCADO</p>
        </button>
    </div>

    <h2>Test Psicológico</h2>
    <div class="test-section">
        <div>
            <p><strong>¿Qué mide este test?</strong></p>
            <p>Este test está diseñado para evaluar diferentes aspectos de tu personalidad, emociones y estilo de pensamiento.</p>
            <p><strong>¿Cómo funciona?</strong></p>
            <p>El test consta de una serie de preguntas en las que debes seleccionar la opción que mejor represente tu forma de pensar o actuar en determinadas situaciones.</p>
            <p><strong>¿Para quién es este test?</strong></p>
            <p>Este test es útil para cualquier persona interesada en conocer más sobre su mundo interior, mejorar su bienestar emocional o desarrollar su crecimiento personal.</p>
            <button onclick="location.href='../view/auth/login.php'">Realizar Test</button>
        </div>
        <img src="/img\psicologialp1.png" alt="Test Psicológico">
    </div>

    <h2>Ejercicios del Día</h2>
    <div class="grid">
    <button class="card-button" onclick="window.location.href='/view/auth/login.php'">
        <img src="/img\imagen_ejercicio_lp1.png" alt="Cuerpo Completo - Rutina 7 Min">
        <p>CUERPO COMPLETO - RUTINA 7 MIN</p>
    </button>
    <button class="card-button" onclick="window.location.href='/view/auth/login.php'">
        <img src="/img\imagen_ejercicio_lp2.png" alt="Cardio en casa sin equipo">
        <p>CARDIO EN CASA SIN EQUIPO</p>
    </button>
</div>

</div>

<div style="text-align: center; margin-top: 60px; margin-bottom: 30px;">
    <a href="about.php" style="
        background-color: #ff6a00;
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        font-size: 16px;
        display: inline-block;
        transition: background 0.3s;
    " onmouseover="this.style.background='#e55a00'" onmouseout="this.style.background='#ff6a00'">
        ¡CONÓCENOS!
    </a>
</div>

<footer>
    BIENIESTAR © 2025
</footer>

</body>
</html>
 