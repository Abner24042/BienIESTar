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
    <title>Alimentación | Bienestar</title>
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

        .titulo-principal {
            font-size: 36px;
            color: #ff6b00;
            margin-bottom: 10px;
        }

        .subtitulo {
            font-size: 18px;
            margin-bottom: 30px;
        }

        .imagen-principal {
            width: 100%;
            border-radius: 10px;
            margin-bottom: 40px;
        }

        h3 {
            text-align: center;
            color: #ff6b00;
            margin-bottom: 20px;
        }

        .planes {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 40px;
    padding-left: 40px;
}

.planes > ul > li {
    list-style: none;
    font-weight: bold;
    margin-bottom: 5px;
}

.planes ul ul li {
    list-style-type: disc;
    margin-left: 20px;
}


.imagenes-secundarias {
    display: flex;
    gap: 20px;
    justify-content: center;
    flex-wrap: nowrap; /* Para que estén una al lado de la otra */
    margin-bottom: 40px;
}

.imagenes-secundarias img {
    max-width: 45%;
    border-radius: 10px;
}


        .cta {
            background-color: #f7f7f7;
            padding: 30px 20px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 40px;
        }

        .cta p {
            font-size: 16px;
            margin-bottom: 15px;
        }

        .cta button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #000;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        footer {
            text-align: center;
            padding: 20px;
            font-size: 14px;
            color: #555;
        }

        @media (max-width: 768px) {
    .imagenes-secundarias {
        flex-direction: column;
        align-items: center;
    }
    .imagenes-secundarias img {
        max-width: 100%;
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


<!-- CONTENIDO DE LA PÁGINA -->
<div class="container">
    <h2 class="titulo-principal">ALIMENTACIÓN</h2>
    <p class="subtitulo">Nutre tu cuerpo con planes alimenticios adaptados a tus necesidades y objetivos.</p>

    <img src="../../img\alim1.png" alt="Imagen principal de alimentación" class="imagen-principal">

    <h3>TIPOS DE PLANES ALIMENTICIOS</h3>
<div class="planes">
    <ul>
        <li><strong>1.- Dietas para el control de peso</strong></li>
        <ul>
            <li>Dieta hipocalórica</li>
            <li>Dieta hipercalórica</li>
            <li>Dieta isocalórica</li>
        </ul>
    </ul>
    <ul>
        <li><strong>2.- Dietas para condiciones médicas</strong></li>
        <ul>
            <li>Dieta para diabéticos</li>
            <li>Dieta baja en purinas</li>
            <li>Dieta sin gluten</li>
            <li>Dieta sin lactosa</li>
        </ul>
    </ul>
    <ul>
        <li><strong>3.- Dietas basadas en la alimentación</strong></li>
        <ul>
            <li>Dieta vegetariana/vegana</li>
            <li>Dieta paleo</li>
            <li>Dieta mediterránea</li>
            <li>Dieta DASH</li>
            <li>Dieta cetogénica (keto)</li>
        </ul>
    </ul>
    <ul>
        <li><strong>4.- Otras dietas</strong></li>
        <ul>
            <li>Dieta blanda</li>
            <li>Dieta líquida/semilíquida</li>
            <li>Dieta para alergias alimentarias</li>
        </ul>
    </ul>
</div>


    <div class="imagenes-secundarias">
        <img src="../../img\alim2.png" alt="Alimentos saludables">
        <img src="../../img\alim3.png" alt="Alimentos variados">
    </div>

    <div class="cta">
        <p><strong>¿Quieres saber más sobre tu salud alimenticia y cómo comer más sano?</strong></p>
        <p>Agenda tu cita con un nutriólogo para conocer más sobre cómo mejorar tu alimentación.</p>
        <button onclick="window.location.href='perfil.php'">Agendar una cita</button>
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