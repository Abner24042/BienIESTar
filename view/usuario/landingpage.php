<?php
session_start();
$usuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Usuario';
$paginaActual = basename($_SERVER['PHP_SELF']);

// Incluir el header
include __DIR__ . '/../partials/header.php';
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
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    justify-content: center;
  }

  .card-link {
    display: block;
    width: 300px;
    text-align: center;
    border: 1px solid #ccc;
    border-radius: 10px;
    padding: 10px;
    text-decoration: none;
    color: inherit;
    transition: box-shadow 0.3s ease;
  }

  .card-link:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
  }

  .card-link img {
    max-width: 100%;
    border-radius: 10px;
    margin-bottom: 8px;
  }

  .card-link p {
    font-weight: bold;
    font-size: 14px;
  }

  .btn-link {
    display: inline-block;
    padding: 10px 20px;
    background-color: #ff6b00;
    color: white;
    border-radius: 6px;
    text-decoration: none;
    font-weight: bold;
    transition: background-color 0.3s ease;
  }

  .btn-link:hover {
    background-color: #e55a00;
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

<!-- Botón hamburguesa -->
<button id="menuToggle" class="hamburger" aria-label="Abrir menú" aria-expanded="false">
  <span></span><span></span><span></span>
</button>

<!-- Menú lateral -->
<nav id="sideNav" class="side-nav" aria-hidden="true">
  <a href="landingpage.php">Menú</a>
  <a href="alimentacion.php">Alimentación</a>
  <a href="saludmental.php">Salud Mental</a>
  <a href="ejercicio.php">Ejercicio</a>
  <a href="noticias.php">Noticias</a>
  <a href="perfil.php">Perfil</a>
</nav>

<!-- Fondo difuminado -->
<div id="overlay" class="overlay"></div>


<div class="container">
  <!-- Aquí inicia el contenido visual de la página -->

  <div class="grid">
    <a href="https://www.loveandlemons.com/french-toast/" class="card-link">
      <img src="../../img/frenchlp1.png" alt="French Toast">
      <p>FRENCH TOAST</p>
    </a>
    <a href="https://www.pizcadesabor.com/calabacitas-gratinadas-rellenas-de-vegetales/" class="card-link">
      <img src="../../img/calabazalp2.png" alt="Calabacitas">
      <p>CALABACITAS CON QUESO Y VERDURAS</p>
    </a>
    <a href="https://www.dir.cat/blog/es/recetas-tacos-wraps-saludables/" class="card-link">
      <img src="../../img/tacuacheslp3.png" alt="Tacos de pescado">
      <p>TACOS DE PESCADO</p>
    </a>
  </div>

  <h2>Test Psicológico</h2>
  <div class="test-section" style="display:flex; align-items:center; gap:20px; flex-wrap:wrap;">
    <div style="flex:1; min-width: 250px;">
      <p><strong>¿Qué mide este test?</strong></p>
      <p>Este test está diseñado para evaluar diferentes aspectos de tu personalidad, emociones y estilo de pensamiento.</p>
      <p><strong>¿Cómo funciona?</strong></p>
      <p>El test consta de una serie de preguntas en las que debes seleccionar la opción que mejor represente tu forma de pensar o actuar en determinadas situaciones.</p>
      <p><strong>¿Para quién es este test?</strong></p>
      <p>Este test es útil para cualquier persona interesada en conocer más sobre su mundo interior, mejorar su bienestar emocional o desarrollar su crecimiento personal.</p>
      <a href="https://www.mind-diagnostics.org/" class="btn-link">Realizar Test</a>
    </div>
    <img src="../../img/psicologialp1.png" alt="Test Psicológico" style="max-width: 300px; border-radius: 10px;">
  </div>

  <h2>Ejercicios del Día</h2>
  <div class="grid">
    <a href="https://www.youtube.com/watch?v=bGLP0oiYYdc" class="card-link">
      <img src="../../img/imagen_ejercicio_lp1.png" alt="Cuerpo Completo - Rutina 7 Min">
      <p>CUERPO COMPLETO - RUTINA 7 MIN</p>
    </a>
    <a href="https://www.youtube.com/watch?v=AUTqIj21X7g" class="card-link">
      <img src="../../img/imagen_ejercicio_lp2.png" alt="Cardio en casa sin equipo">
      <p>CARDIO EN CASA SIN EQUIPO</p>
    </a>
  </div>
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
<script>
(function () {
  const btn = document.getElementById('menuToggle');
  const nav = document.getElementById('sideNav');
  const overlay = document.getElementById('overlay');

  if (!btn || !nav || !overlay) return;

  // Funciones para abrir/cerrar
  const openMenu = () => {
    document.body.classList.add('menu-open');
    btn.setAttribute('aria-expanded', 'true');
    nav.setAttribute('aria-hidden', 'false');
  };

  const closeMenu = () => {
    document.body.classList.remove('menu-open');
    btn.setAttribute('aria-expanded', 'false');
    nav.setAttribute('aria-hidden', 'true');
  };

  const toggleMenu = () => {
    document.body.classList.contains('menu-open') ? closeMenu() : openMenu();
  };

  // Eventos
  btn.addEventListener('click', toggleMenu);
  overlay.addEventListener('click', closeMenu);
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeMenu();
  });

  // Cierra al hacer clic en un enlace
  nav.addEventListener('click', (e) => {
    if (e.target.closest('a')) closeMenu();
  });
})();
</script>
<footer>
    BIENIESTAR © 2025
</footer>

</body>
</html>
 