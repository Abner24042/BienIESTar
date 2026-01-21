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
    <meta charset="UTF-8" />
    <title>Noticias | Bienestar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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

        /* Hero with full background image */
        .hero {
            position: relative;
            height: 300px;
            background-image: url('../../img/noticias_header.jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-size: 40px;
            font-weight: bold;
            text-align: center;
        }
        .hero::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.45);
            z-index: 1;
        }
        .hero h1 {
            position: relative;
            z-index: 2;
            text-shadow: 1px 1px 6px rgba(0,0,0,0.8);
            margin: 0;
        }

        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .noticia-doble {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 40px;
        }

        .noticia-doble a.noticia {
            flex: 1;
            min-width: 280px;
            background-color: #f7f7f7;
            border-radius: 10px;
            overflow: hidden;
            color: inherit;
            text-decoration: none;
            transition: box-shadow 0.3s;
            display: flex;
            flex-direction: column;
        }
        .noticia-doble a.noticia:hover {
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }

        .noticia img {
            width: 100%;
            height: 160px;
            object-fit: cover;
        }

        .noticia-content {
            padding: 15px;
            flex-grow: 1;
        }

        .noticia-content h3 {
            margin: 0 0 10px 0;
        }

        .noticia-content p {
            margin: 0 0 10px 0;
        }

        .noticia-content button {
            background-color: black;
            color: white;
            border: none;
            padding: 6px 14px;
            border-radius: 6px;
            cursor: pointer;
        }

        .galeria-ejercicios {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .galeria-ejercicios a {
            display: block;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
            transition: transform 0.2s ease, box-shadow 0.3s ease;
            text-decoration: none;
            color: inherit;
        }

        .galeria-ejercicios a:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            cursor: pointer;
        }

        .galeria-ejercicios img {
            width: 100%;
            height: 140px;
            object-fit: cover;
            border-radius: 10px;
            display: block;
        }

        .salud-mental {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 40px;
        }

        .salud-mental a.noticia-sm {
            background-color: #f1f1f1;
            padding: 15px;
            border-radius: 10px;
            color: inherit;
            text-decoration: none;
            display: block;
            transition: box-shadow 0.3s;
        }

        .salud-mental a.noticia-sm:hover {
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }

        .salud-mental h4 {
            margin-top: 0;
        }

        .frase-final {
            font-size: 18px;
            text-align: center;
            font-weight: bold;
            color: #ff6a00;
            margin-bottom: 50px;
        }

        footer {
            text-align: center;
            padding: 20px;
            font-size: 14px;
            color: #555;
        }

        @media (max-width: 768px) {
            .salud-mental {
                grid-template-columns: 1fr;
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

<div class="hero">
    <h1>Noticias</h1>
</div>

<div class="container">

    <p style="text-align:center; margin-bottom: 40px;">
        Aquí podrás ver las noticias de la semana sobre Alimentación, Ejercicio y sobre Salud Mental.
    </p>

    <div class="noticia-doble">
        <a href="https://www.healthline.com/nutrition/best-diet-for-gut-health" class="noticia">
            <img src="../../img/notal1.png" alt="Noticia 1 alimentos" />
            <div class="noticia-content">
                <h3>La importancia de una dieta equilibrada para la salud intestinal</h3>
                <p>Resumen rápido de la noticia</p>
            </div>
        </a>
        <a href="https://www.medicalnewstoday.com/articles/323490" class="noticia">
            <img src="../../img/notal2.png" alt="Noticia 2 alimentos" />
            <div class="noticia-content">
                <h3>Tendencias en alimentación saludable: alimentos fermentados</h3>
                <p>Habla sobre los beneficios de alimentos fermentados como el kéfir, chucrut y kimchi para la digestión y la salud general.</p>
            </div>
        </a>
    </div>

    <h3 style="margin-bottom: 20px;">Noticias de Ejercicios</h3>
    <div class="galeria-ejercicios">
        <a href="https://www.cdc.gov/physicalactivity/basics/strength-training/index.htm" target="_blank">
            <img src="../../img/notej1.png" alt="Ejercicio 1" />
        </a>
        <a href="https://www.health.harvard.edu/exercise-and-fitness/the-benefits-of-exercise" target="_blank">
            <img src="../../img/notej2.png" alt="Ejercicio 2" />
        </a>
        <a href="https://www.mayoclinic.org/healthy-lifestyle/fitness/in-depth/flexibility-exercises/art-20047931" target="_blank">
            <img src="../../img/notej3.png" alt="Ejercicio 3" />
        </a>
    </div>

    <h3 style="margin-bottom: 20px;">Noticias sobre Salud Mental</h3>
    <div class="salud-mental">
        <a href="https://www.nimh.nih.gov/health/topics/coping-with-traumatic-events" class="noticia-sm">
            <h4>Cómo la meditación puede reducir el estrés y la ansiedad</h4>
            <p>Explica cómo la meditación y prácticas de mindfulness ayudan a disminuir niveles de estrés y mejoran la salud emocional.</p>
        </a>
        <a href="https://www.who.int/news-room/fact-sheets/detail/mental-health-strengthening-our-response" class="noticia-sm">
            <h4>El impacto del ejercicio físico en la salud mental</h4>
            <p>La actividad física regular se asocia con la reducción de síntomas de depresión y ansiedad.</p>
        </a>
        <a href="https://www.sleepfoundation.org/mental-health" class="noticia-sm">
            <h4>La importancia del sueño en la salud mental</h4>
            <p>Un buen descanso nocturno es fundamental para mantener el equilibrio emocional y prevenir trastornos mentales.</p>
        </a>
        <a href="https://www.apa.org/topics/resilience" class="noticia-sm">
            <h4>Estrategias para mejorar la resiliencia emocional en tiempos difíciles</h4>
            <p>Detalla técnicas para desarrollar resiliencia, como mantener conexiones sociales y cultivar pensamientos positivos.</p>
        </a>
    </div>

    <div class="frase-final">
        “Un enfoque integral para cuidar cada aspecto de tu salud, porque tu bienestar es lo primero.”
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
