<?php
session_start();
$usuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Usuario';
$paginaActual = basename($_SERVER['PHP_SELF']);
include __DIR__ . '/../partials/header.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Salud Mental | Bienestar</title>
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
        .ejercicios {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            margin-bottom: 40px;
        }
        .ejercicios ul {
            flex: 1;
            min-width: 260px;
            padding-left: 20px;
        }
        .ejercicios li {
            margin-bottom: 8px;
        }
        .imagenes-secundarias {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.imagenes-secundarias a {
    display: block;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transition: transform 0.3s;
}

.imagenes-secundarias a:hover {
    transform: scale(1.03);
}

.imagenes-secundarias img {
    width: 100%;
    height: 300px;
    object-fit: cover;
    border-radius: 10px;
}

        
        .importancia {
            background-color: #f7f7f7;
            padding: 20px;
            border-left: 6px solid #ff6b00;
            margin-bottom: 40px;
        }
        .cta {
            background-color: #f2f2f2;
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
            .ejercicios {
                flex-direction: column;
            }
            .imagenes-secundarias {
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
<div class="container">
    <h2 class="titulo-principal">SALUD MENTAL</h2>
    <p class="subtitulo">Fortalece tu mente con herramientas y apoyo para una vida equilibrada y plena.</p>

    <img src="../../img/salud1.png" alt="Salud mental" class="imagen-principal">

    <h3>TIPOS DE EJERCICIOS PARA MEJORAR TU SALUD MENTAL</h3>
    <div class="ejercicios">
        <ul>
            <li><strong>Mindfulness:</strong> Reduce ansiedad y mejora concentración.</li>
            <li><strong>Ejercicio físico:</strong> Mejora el estado de ánimo.</li>
            <li><strong>Diario de gratitud:</strong> Reprograma tu mente en positivo.</li>
            <li><strong>Respiración consciente:</strong> Útil para crisis de ansiedad.</li>
            <li><strong>Visualización:</strong> Refuerza la autoestima.</li>
        </ul>
        <ul>
            <li><strong>Contacto con la naturaleza:</strong> Estimula emociones positivas.</li>
            <li><strong>Arte y música:</strong> Expresa y regula tus emociones.</li>
            <li><strong>Rutinas:</strong> Disminuyen el estrés.</li>
            <li><strong>Relaciones sanas:</strong> Refuerzan tu seguridad.</li>
            <li><strong>Reducción de redes:</strong> Menos comparación, más tranquilidad.</li>
        </ul>
    </div>

    <div class="imagenes-secundarias">
        <img src="../../img/salud2.png" alt="Psicólogo">
        <img src="../../img/salud3.png" alt="Terapia mental">
    </div>

    <div class="importancia">
        <h4>Importancia de contar con un psicólogo</h4>
        <ul>
            <li><strong>Espacio seguro:</strong> Hablar con libertad.</li>
            <li><strong>Acompañamiento profesional:</strong> Guía experta.</li>
            <li><strong>Prevención:</strong> Detectar problemas a tiempo.</li>
        </ul>
    </div>

    <div class="cta">
        <p><strong>¿Te gustaría mejorar tu bienestar emocional y mental?</strong></p>
        <p>Agenda tu cita con un psicólogo para trabajar en tu equilibrio emocional.</p>
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
