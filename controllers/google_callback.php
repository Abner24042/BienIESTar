<?php
session_start();
require_once 'google_config.php';

use Google\Service\Oauth2;

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "sistema_usuarios");

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    if (!isset($token['error'])) {
        $client->setAccessToken($token);

        // Guarda token para Calendar y (si existe) el refresh token
        $_SESSION['access_token'] = $token;
        if (!empty($token['refresh_token'])) {
            $_SESSION['refresh_token'] = $token['refresh_token'];
        }

        // Datos básicos del usuario (para nombre/correo)
        $oauth = new Oauth2($client);
        $userInfo = $oauth->userinfo->get();

        $nombre = $conexion->real_escape_string($userInfo->name ?? '');
        $correo = $conexion->real_escape_string($userInfo->email ?? '');

        // FOTO EN ALTA RESOLUCIÓN
        // 1) Intenta People API (helper getHighResGooglePhoto)
        // 2) Si no, usa userinfo->picture "upsized" a 512px
        $fotoHD = getHighResGooglePhoto($client, 512);
        if (!$fotoHD && !empty($userInfo->picture)) {
            $fotoHD = upsizeGooglePhotoUrl($userInfo->picture, 512);
        }
        $foto = $conexion->real_escape_string($fotoHD ?? ($userInfo->picture ?? ''));

        // Contraseña “base” (tal como la tenías)
        $contrasena = '123';

        // Guardar en sesión
        $_SESSION['nombre'] = $nombre;
        $_SESSION['correo'] = $correo;
        $_SESSION['foto']   = $foto;

        // Registrar si no existe
        $existe = $conexion->query("SELECT id FROM usuarios WHERE correo = '$correo'");
        if ($existe && $existe->num_rows === 0) {
            $conexion->query("
                INSERT INTO usuarios (nombre, correo, foto, rol, contrasena)
                VALUES ('$nombre', '$correo', '$foto', 'Usuario Base', '$contrasena')
            ");
        }

        header('Location: ../view/usuario/landingpage.php');
        exit();
    } else {
        echo "Error al obtener el token: " . htmlspecialchars($token['error']);
    }
} else {
    header('Location: ../view/usuario/error_codigo.php');
    exit();
}
