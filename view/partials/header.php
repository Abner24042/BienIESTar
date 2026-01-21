<?php
// header.php
// Recibe $paginaActual para marcar activo el menú
if (!isset($paginaActual)) {
    $paginaActual = basename($_SERVER['PHP_SELF']);
}
?>
<header>
    <h1><b>BIEN<span>IEST</span>AR</b></h1>
    <div class="navbar-links">
        <a href="landingpage.php" class="<?= $paginaActual == 'landingpage.php' ? 'activo' : '' ?>">Home</a>
        <a href="alimentacion.php" class="<?= $paginaActual == 'alimentacion.php' ? 'activo' : '' ?>">Alimentación</a>
        <a href="saludmental.php" class="<?= $paginaActual == 'saludmental.php' ? 'activo' : '' ?>">Salud Mental</a>
        <a href="ejercicio.php" class="<?= $paginaActual == 'ejercicio.php' ? 'activo' : '' ?>">Ejercicio</a>
        <a href="noticias.php" class="<?= $paginaActual == 'noticias.php' ? 'activo' : '' ?>">Noticias</a>
        <a href="perfil.php" class="<?= $paginaActual == 'perfil.php' ? 'activo' : '' ?>">Perfil</a>
        <a href="../../controllers/logout.php">Cerrar Sesión</a>
    </div>
</header>
