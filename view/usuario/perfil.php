<?php
session_start();
require_once '../../controllers/google_config.php';

if (!isset($_SESSION['correo'])) {
    header("Location: ../auth/login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "sistema_usuarios");
if ($conn->connect_error) {
    die("Error: " . $conn->connect_error);
}

$correo = $_SESSION['correo'];
// Usar prepared statement para evitar SQL injection
$stmt = $conn->prepare("SELECT nombre, foto, rol FROM usuarios WHERE correo = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Foto desde sesión, base de datos o placeholder (robusta para Google)
function limpiaRutaFoto($valor) {
    return is_string($valor) ? trim($valor) : '';
}

$foto = 'https://via.placeholder.com/120'; // fallback por defecto

// 1) Si la sesión trae foto (p. ej. de Google OAuth), úsala
if (!empty($_SESSION['foto'])) {
    $tmp = limpiaRutaFoto($_SESSION['foto']);
    if ($tmp !== '') {
        $foto = $tmp;
    }
}
// 2) Si no hay en sesión pero sí en BD, respeta si es URL o archivo local
elseif (!empty($user['foto'])) {
    $tmp = limpiaRutaFoto($user['foto']);
    if ($tmp !== '') {
        // Si ya es una URL http/https, úsala tal cual; si no, asume archivo local en uploads/
        if (preg_match('~^https?://~i', $tmp)) {
            $foto = $tmp;
        } else {
            $foto = 'uploads/' . ltrim($tmp, '/');
        }
    }
}
// 3) Si nada de lo anterior, queda el placeholder



// Guardar cita y sincronizar con Google Calendar (igual que antes)
// --- GUARDAR / ELIMINAR cita y (opcional) sincronización con Google ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $accion = isset($data['accion']) ? $data['accion'] : 'crear';

    if ($accion === 'eliminar') {
        // Eliminación por ID (más seguro)
        $id = (int)$data['id'];
        $stmt = $conn->prepare("DELETE FROM citas_bieniestar WHERE id = ? AND correo = ?");
        $stmt->bind_param("is", $id, $correo);
        $ok = $stmt->execute();
        $stmt->close();

        // Nota: si guardas eventId de Google Calendar, aquí lo borrarías también.
        exit(json_encode(['status' => $ok ? 'ok' : 'error']));
    }

    // Crear
    $fecha  = $conn->real_escape_string($data['fecha']);
    $titulo = $conn->real_escape_string($data['titulo']);
    $hora   = $conn->real_escape_string($data['hora']);

    $stmt = $conn->prepare("INSERT INTO citas_bieniestar (fecha, hora, titulo, correo) VALUES (?,?,?,?)");
    $stmt->bind_param("ssss", $fecha, $hora, $titulo, $correo);
    $ok = $stmt->execute();
    $newId = $stmt->insert_id;
    $stmt->close();

    if ($ok && isset($_SESSION['token'])) {
        $client->setAccessToken($_SESSION['token']);
        if (!$client->isAccessTokenExpired()) {
            $calendarService = new Google_Service_Calendar($client);
            $fechaHoraInicio = $fecha . 'T' . $hora . ':00';
            $horaFin = date('H:i:s', strtotime($hora . ' +30 minutes'));
            $fechaHoraFin = $fecha . 'T' . $horaFin;
            $evento = new Google_Service_Calendar_Event([
                'summary' => $titulo,
                'description' => 'Cita agendada desde el sistema Bienestar.',
                'start' => ['dateTime' => $fechaHoraInicio, 'timeZone' => 'America/Mexico_City'],
                'end'   => ['dateTime' => $fechaHoraFin,   'timeZone' => 'America/Mexico_City'],
            ]);
            try { $calendarService->events->insert('primary', $evento); } catch (Exception $e) {}
        }
    }

    exit(json_encode(['status' => $ok ? 'ok' : 'error', 'id' => $newId]));
}

// --- Cargar citas (incluye id) ---
$citas = [];
$stmt = $conn->prepare("SELECT id, fecha, hora, titulo FROM citas_bieniestar WHERE correo = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $citas[] = $row;
}
$stmt->close();
$conn->close();

$paginaActual = basename($_SERVER['PHP_SELF']);
include __DIR__ . '/../partials/header.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario - Bienestar</title>
    <style>
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
* { 
    box-sizing: border-box; 
}

body {
    font-family: 'Segoe UI', sans-serif;
    margin: 0;
    background-color: #fff;
    color: #111;
}

/* Header */
header {
    background-color: #ff6b00;
    color: white;
    padding: 10px 20px;
    text-align: center;
}

header span {
    color: #000000;
}

/* INFO PERSONAL */
.info-personal {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 24px;
    margin: 24px;
    padding-bottom: 24px;
    border-bottom: 2px solid #eee;
    justify-content: center;
}

.info-personal img {
    width: 280px;          /* antes 250px */
    height: 280px;         /* antes 250px */
    border-radius: 12px;
    object-fit: cover;
    margin-right: 0;       /* gap ya maneja el espacio */
    /* nitidez */
    image-rendering: -webkit-optimize-contrast;
    image-rendering: crisp-edges;
    image-rendering: high-quality;
    transform: translateZ(0);
    backface-visibility: hidden;
}



h3 {
    font-size: 2.1rem;     /* antes 1.9rem */
    margin: 0 0 10px;
}

.info-personal p {
    font-size: 1.3rem;     /* antes 1.2rem */
    line-height: 1.5;
    margin: 6px 0;
}

/* RESPONSIVE */
@media (max-width: 900px) {
  .info-personal img { width: 260px; height: 260px; }
  h3 { font-size: 1.9rem; }
  .info-personal p { font-size: 1.15rem; }
}

@media (max-width: 600px) {
  .info-personal { margin: 16px; gap: 16px; text-align: center; }
  .info-personal img { width: 200px; height: 200px; }
  .info-personal div { text-align: center; }
  h3 { font-size: 1.7rem; }
  .info-personal p { font-size: 1.05rem; }
}


/* Botones admin en columna */
.admin-buttons {
  display: flex;
  flex-direction: column;   /* ← apila vertical */
  align-items: flex-start;  /* izquierda; usa center si los quieres centrados */
  gap: 10px;                /* separación vertical */
  margin-top: 10px;
}

.admin-buttons button {
  width: 100%;          /* ocupa el ancho disponible del contenedor */
  max-width: 260px;     /* límite para que no queden muy anchos en desktop */
  padding: 12px 16px;
  font-size: 15px;
  border: none;
  border-radius: 8px;
  background-color: #ff6b00;
  color: #fff;
  cursor: pointer;
  transition: background-color 0.3s;
}
.admin-buttons button:hover { background-color: #e55a00; }

/* Opcional: en móviles, centrarlos */
@media (max-width: 600px) {
  .admin-buttons { align-items: center; }
}

/* ====== Calendario (adaptable) ====== */
.calendar-section{
  /* espacio arriba y extra abajo para que no quede pegado al footer */
  margin: 40px auto clamp(60px, 8vh, 100px);
  padding-inline: min(20px, 4vw);
  max-width: 1200px;
}

.calendar{
  display: grid;
  /* columnas automáticas, se acomodan solas según ancho */
  grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
  gap: clamp(8px, 1.8vw, 14px);
  margin-top: 20px;
}

.day{
  background-color: #fff;
  border: 2px solid #ff6b00;
  border-radius: 12px;
  padding: clamp(12px, 2vw, 20px) 10px;
  min-height: clamp(90px, 14vw, 160px);
  text-align: center;
  font-weight: 600;
  color: #333;
  cursor: pointer;
  box-shadow: 0 4px 6px rgba(255, 107, 0, 0.2);
  transition: transform .15s ease, box-shadow .2s ease, background-color .2s ease, color .2s ease;
  position: relative;
  display: flex;
  flex-direction: column;
  justify-content: center;
}
.day:hover{
  transform: translateY(-2px);
  background-color: #ff6b00;
  color: white;
  box-shadow: 0 8px 16px rgba(255, 107, 0, 0.25);
}

.day small{
  margin-top: 8px;
  font-weight: normal;
  font-size: 0.85rem;
  color: #555;
  display: block;
  overflow-wrap: break-word;
}

/* Marquita cuando hay cita (opcional) */
.day[data-has-appointment="true"]::after{
  content:'';
  width:10px; height:10px; background:#ff6b00; border-radius:50%;
  position:absolute; top:8px; right:8px;
}


/* Barra de navegación */
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
footer {
    text-align: center;
    font-size: 14px;
    padding: 20px;
    color: #555;
}
/* ========== Modal ========== */
:root{
  --modal-bg: #ffffff;
  --modal-text: #111;
  --modal-accent: #ff6b00;
  --modal-border: #eee;
  --modal-shadow: 0 20px 50px rgba(0,0,0,.2);
  --radius-lg: 14px;
  --radius-md: 10px;
}

.modal{
  position: fixed; inset: 0;
  display: none; /* se muestra con .is-open */
  align-items: center; justify-content: center;
  background: rgba(0,0,0,.45);
  backdrop-filter: blur(2px);
  z-index: 2000;
  opacity: 0;
  transition: opacity .25s ease;
}
.modal.is-open{ display:flex; opacity:1; }

.modal__dialog{
  width: min(560px, 92vw);
  background: var(--modal-bg);
  color: var(--modal-text);
  border-radius: var(--radius-lg);
  box-shadow: var(--modal-shadow);
  border: 1px solid var(--modal-border);
  transform: translateY(10px) scale(.98);
  transition: transform .25s ease;
  overflow: hidden;
}
.modal.is-open .modal__dialog{ transform: translateY(0) scale(1); }

/* Close button (X) */
.modal__close{
  position:absolute; right:12px; top:10px;
  width:36px; height:36px; border-radius: 50%;
  border: 1px solid var(--modal-border);
  background:#fff; color:#333; cursor:pointer;
  font-size: 22px; line-height: 1; display:grid; place-items:center;
  transition: background .2s, transform .1s;
}
.modal__close:hover{ background:#f6f6f6; }
.modal__close:active{ transform: scale(.96); }

.modal__header{
  padding: 20px 22px 10px;
  border-bottom: 1px solid var(--modal-border);
}
.modal__header h3{
  margin:0; font-size:1.25rem;
}
.modal__subtitle{
  margin:6px 0 0; color:#666; font-size:.95rem;
}

.modal__body{
  display: grid; gap: 16px;
  padding: 18px 22px;
}

/* Campos */
.field{ display:grid; gap:8px; }
.field__label{ font-weight: 600; color:#444; }
.field__input{
  width:100%; padding:12px 14px; border-radius: var(--radius-md);
  border:1.5px solid #ddd; outline: none; font-size:1rem;
  transition: border-color .2s, box-shadow .2s;
}
.field__input:focus{
  border-color: var(--modal-accent);
  box-shadow: 0 0 0 4px rgba(255,107,0,.15);
}

/* Footer */
.modal__footer{
  display:flex; gap:12px; justify-content: flex-end;
  padding: 16px 22px 20px;
  border-top:1px solid var(--modal-border);
}

/* Botones */
.btn{
  background: var(--modal-accent);
  color:#fff; border:0; border-radius:10px;
  padding:10px 16px; font-weight:600; cursor:pointer;
  transition: transform .05s ease, background .2s ease;
}
.btn:hover{ background:#e55a00; }
.btn:active{ transform: translateY(1px); }

.btn--ghost{
  background:#fff; color:#333; border:1.5px solid #ddd;
}
.btn--ghost:hover{ background:#f7f7f7; }

/* Responsive ajustes finos */
@media (max-width: 480px){
  .modal__header{ padding:16px 16px 8px; }
  .modal__body{ padding:14px 16px; }
  .modal__footer{ padding: 12px 16px 16px; }
}

/* ===== Selects Año / Mes ===== */
.select-container{
  display:flex; align-items:center; justify-content:center;
  gap:16px; margin: 10px auto 22px;
  flex-wrap:wrap;
}

/* estilo base compartido */
#selectYear, #selectMonth{
  appearance:none; -webkit-appearance:none; -moz-appearance:none;
  font-family:inherit; font-size:1rem; font-weight:600;
  padding:12px 44px 12px 14px;
  border-radius:12px;
  border:2px solid #ff6b00;
  background:
    /* caret SVG */
    url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='black' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><polyline points='6 9 12 15 18 9'/></svg>")
    no-repeat right 12px center / 18px 18px,
    #fff;
  color:#333;
  transition: border-color .2s, box-shadow .2s, background-color .2s;
  min-width: 150px;
}

/* enfoque y hover */
#selectYear:hover, #selectMonth:hover{
  border-color:#e55a00;
}
#selectYear:focus, #selectMonth:focus{
  outline:none;
  border-color:#e55a00;
  box-shadow:0 0 0 5px rgba(229,90,0,.15);
}

/* tamaños en móvil */
@media (max-width:600px){
  #selectYear, #selectMonth{
    width: calc(50% - 10px);
    min-width: 0;
  }
}

/* label más vistoso */
.select-container label{
  font-weight:700;
  color:#ff6b00;
  margin-right:4px;
}

/* Lista de citas */
.lista-citas{ display:grid; gap:12px; }
.item-cita{
  display:flex; align-items:center; justify-content:space-between;
  border:1px solid #eee; border-radius:10px; padding:12px 14px;
}
.item-cita strong{ display:block; }
.item-cita small{ color:#666; }
.btn--danger{
  background:#ff3b30 !important;
  box-shadow:0 8px 18px rgba(255,59,48,.22);
}
.btn--danger:hover{ filter:brightness(1.03); }
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
  <a href="../../controllers/logout.php">Cerrar Sesión</a>
</nav>

<div id="overlay" class="overlay"></div>

<div class="container">
    <div class="info-personal">
        <img
  src="<?= htmlspecialchars($foto) ?>"
  alt="Foto de perfil"
  referrerpolicy="no-referrer"
  onerror="this.onerror=null;this.src='https://via.placeholder.com/120';"
/>
        <div>
            <h3>INFORMACIÓN PERSONAL</h3>
            <p><strong>NOMBRE:</strong> <?= htmlspecialchars($user['nombre']) ?></p>
            <p><strong>CORREO:</strong> <?= htmlspecialchars($correo) ?></p>
            <p><strong>ROL:</strong> <?= htmlspecialchars($user['rol']) ?></p>
        </div>

        <?php 
        $rol = isset($user['rol']) ? trim(strtolower($user['rol'])) : '';
        if ($rol === 'administrador'): 
        ?>
            <div class="admin-buttons">
                <button onclick="window.location.href='../auth/form_agregar.php';">Agregar Usuarios</button>
                <button onclick="window.location.href='../auth/form_modificar.php';">Modificar Usuarios</button>
            </div>
        <?php endif; ?>
    </div>

    <h2 style="text-align:center; margin-top: 40px;">CALENDARIO DE CITAS</h2>

    <div class="select-container">
        <label for="selectYear">Año:</label>
        <select id="selectYear"></select>

        <label for="selectMonth">Mes:</label>
        <select id="selectMonth">
            <option value="0">Enero</option>
            <option value="1">Febrero</option>
            <option value="2">Marzo</option>
            <option value="3">Abril</option>
            <option value="4">Mayo</option>
            <option value="5">Junio</option>
            <option value="6">Julio</option>
            <option value="7">Agosto</option>
            <option value="8">Septiembre</option>
            <option value="9">Octubre</option>
            <option value="10">Noviembre</option>
            <option value="11">Diciembre</option>
        </select>
    </div>

    <div class="calendar-section">
        <div id="calendar" class="calendar"></div>
    </div>
</div>

<!-- MODAL: Agregar Cita -->
<div id="modal" class="modal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
  <div class="modal__dialog" role="document">
    <button class="modal__close" type="button" aria-label="Cerrar" onclick="cerrarModal()">×</button>

    <header class="modal__header">
      <h3 id="modalTitle">Agregar Cita</h3>
      <p class="modal__subtitle">Para <span id="modalFecha"></span></p>
    </header>

    <div class="modal__body">
      <label class="field">
        <span class="field__label">Título</span>
        <input id="titulo" type="text" class="field__input" placeholder="Ej. Revisión médica" />
      </label>

      <label class="field">
        <span class="field__label">Hora</span>
        <input id="hora" type="time" class="field__input" />
      </label>
    </div>

    <footer class="modal__footer">
      <button class="btn btn--ghost" type="button" onclick="cerrarModal()">Cancelar</button>
      <button class="btn" type="button" onclick="guardarCita()">Guardar</button>
    </footer>
  </div>
</div>

<!-- ===== MODAL: Ver/Eliminar citas del día ===== -->
<div id="modalVer" class="modal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="modalVerTitle">
  <div class="modal__dialog" role="document">
    <button class="modal__close" type="button" aria-label="Cerrar" onclick="cerrarModalVer()">×</button>

    <header class="modal__header">
      <h3 id="modalVerTitle">Citas del día</h3>
      <p class="modal__subtitle"><span id="modalVerFecha"></span></p>
    </header>

    <div class="modal__body">
      <div id="listaCitasDia" class="lista-citas"></div>
    </div>

    <footer class="modal__footer">
      <button class="btn btn--ghost" type="button" onclick="cerrarModalVer()">Cerrar</button>
    </footer>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const citas = <?= json_encode($citas) ?>;
  let fechaSeleccionada = null;

  const calendar     = document.getElementById("calendar");
  const selectYear   = document.getElementById("selectYear");
  const selectMonth  = document.getElementById("selectMonth");

  // Modales (agregar)
  const modal        = document.getElementById("modal");
  const modalFecha   = document.getElementById("modalFecha");
  const tituloInput  = document.getElementById("titulo");
  const horaInput    = document.getElementById("hora");

  // Modales (ver)
  const modalVer       = document.getElementById("modalVer");
  const modalVerFecha  = document.getElementById("modalVerFecha");
  const listaCitasDia  = document.getElementById("listaCitasDia");

  // Función para obtener los días en un mes
  function diasEnMes(year, month) {
    return new Date(year, month + 1, 0).getDate();
  }

  // Función optimizada para comparar fechas sin la hora
  function sameDay(a, b) {
    const dateA = new Date(a);
    const dateB = new Date(b);
    return dateA.getFullYear() === dateB.getFullYear() &&
           dateA.getMonth() === dateB.getMonth() &&
           dateA.getDate() === dateB.getDate();
  }

  // Manejo de eventos al hacer clic en un día
  function clickDia(fecha) {
    fechaSeleccionada = fecha;
    const delDia = citas.filter(c => sameDay(c.fecha, fecha));
    if (delDia.length > 0) {
      abrirModalVer(fecha, delDia);
    } else {
      abrirModalAgregar(fecha);
    }
  }

  // ----- Agregar cita -----
  window.abrirModal = abrirModalAgregar;
  function abrirModalAgregar(fecha) {
    modalFecha.textContent = fecha;
    modal.classList.add("is-open");
    modal.setAttribute("aria-hidden", "false");
  }
  
  window.cerrarModal = function() {
    modal.classList.remove("is-open");
    modal.setAttribute("aria-hidden", "true");
    tituloInput.value = "";
    horaInput.value   = "";
  }

  window.guardarCita = function() {
    const titulo = tituloInput.value.trim();
    const hora   = horaInput.value.trim();
    if (!titulo || !hora) {
      alert("Completa todos los campos.");
      return;
    }

    fetch("perfil.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ accion: "crear", fecha: fechaSeleccionada, titulo, hora })
    })
    .then(r => r.json())
    .then(d => {
      if (d.status === "ok") {
        citas.push({ id: d.id, fecha: fechaSeleccionada, titulo, hora });
        cargarCalendario();
        alert("✅ Cita guardada");
      } else {
        alert("⚠️ No se pudo guardar");
      }
    })
    .catch(() => alert("❌ Error de red"));
    window.cerrarModal();
  }

  // ----- Ver / Eliminar cita -----
  function abrirModalVer(fecha, arr) {
    modalVerFecha.textContent = fecha;
    listaCitasDia.innerHTML = "";
    arr.forEach(c => {
      const row = document.createElement("div");
      row.className = "item-cita";
      row.innerHTML = `
        <div>
          <strong>${c.titulo}</strong>
          <small>${c.hora}</small>
        </div>
        <button class="btn btn--danger" type="button" data-id="${c.id}">Eliminar</button>
      `;
      listaCitasDia.appendChild(row);
    });
    modalVer.classList.add("is-open");
    modalVer.setAttribute("aria-hidden", "false");
  }

  window.cerrarModalVer = function() {
    modalVer.classList.remove("is-open");
    modalVer.setAttribute("aria-hidden", "true");
  }

  // Delegación para eliminar cita
  listaCitasDia.addEventListener('click', (e) => {
    const btn = e.target.closest('button.btn--danger');
    if (!btn) return;
    const id = +btn.dataset.id;
    if (!confirm("¿Eliminar esta cita?")) return;

    fetch("perfil.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ accion: "eliminar", id })
    })
    .then(r => r.json())
    .then(d => {
      if (d.status === "ok") {
        const i = citas.findIndex(x => +x.id === id);
        if (i > -1) citas.splice(i, 1);
        cargarCalendario();
        const delDia = citas.filter(c => sameDay(c.fecha, fechaSeleccionada));
        if (delDia.length > 0) abrirModalVer(fechaSeleccionada, delDia);
        else cerrarModalVer();
        alert("🗑️ Cita eliminada");
      } else {
        alert("⚠️ No se pudo eliminar");
      }
    })
    .catch(() => alert("❌ Error de red"));
  });

  function cargarCalendario() {
    const year = parseInt(selectYear.value);
    const month = parseInt(selectMonth.value);
    const days = diasEnMes(year, month);

    // Crear fragmento para mejor rendimiento
    const fragment = document.createDocumentFragment();

    // Genera los días del calendario
    for (let day = 1; day <= days; day++) {
      const fecha = `${year}-${String(month + 1).padStart(2, "0")}-${String(day).padStart(2, "0")}`;
      const div = document.createElement("div");
      div.className = "day";
      div.textContent = day;

      // Buscar las citas de este día
      const citasDia = citas.filter(c => sameDay(c.fecha, fecha));

      if (citasDia.length) {
        div.dataset.hasAppointment = "true";
        citasDia.forEach(c => {
          const s = document.createElement("small");
          s.textContent = `${c.titulo} (${c.hora})`;
          div.appendChild(s);
        });
      }

      div.addEventListener("click", () => clickDia(fecha));
      fragment.appendChild(div);
    }

    // Actualizar el DOM una sola vez
    calendar.innerHTML = "";
    calendar.appendChild(fragment);
  }



  // ----- Llenar años en select -----
  function llenarSelectYears() {
    const y = new Date().getFullYear();
    for (let i = y - 10; i <= y + 10; i++) {
      const op = document.createElement("option");
      op.value = i;
      op.textContent = i;
      if (i === y) op.selected = true;
      selectYear.appendChild(op);
    }
  }

  selectYear.addEventListener("change", cargarCalendario);
  selectMonth.addEventListener("change", cargarCalendario);

  // Init
  llenarSelectYears();
  selectMonth.value = new Date().getMonth();
  cargarCalendario();

  // ESC cierra modales
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      cerrarModal();
      cerrarModalVer();
    }
  });
});

// Menú hamburguesa
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
