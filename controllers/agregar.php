<?php
session_start();

$conn = new mysqli("localhost", "root", "", "sistema_usuarios");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Recibir datos del formulario
$nombre = $conn->real_escape_string($_POST['nombre']);
$area = $conn->real_escape_string($_POST['area']);
$fecha_nacimiento = $conn->real_escape_string($_POST['fecha']);
$correo = $conn->real_escape_string($_POST['correo']);
$rol = $conn->real_escape_string($_POST['rol']);
$contrasena = $conn->real_escape_string($_POST['contrasena']);  // sin hash

// Procesar imagen - convertir a binario para BLOB
$fotoBinaria = null;
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
    $fotoBinaria = file_get_contents($_FILES['foto']['tmp_name']);
}

// Preparar statement con BLOB
$stmt = $conn->prepare("INSERT INTO usuarios (nombre, area, fecha, correo, contrasena, rol, foto) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssss", $nombre, $area, $fecha_nacimiento, $correo, $contrasena, $rol, $fotoBinaria);

// Para que mysqli funcione con BLOBs se debe usar send_long_data
if ($fotoBinaria !== null) {
    $stmt->send_long_data(6, $fotoBinaria);  // índice 6 porque es el 7° parámetro, base 0
}

if ($stmt->execute()) {
    header("Location: ../view/auth/form_agregar.php?exito=1");
} else {
    echo "Error en la base de datos: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
