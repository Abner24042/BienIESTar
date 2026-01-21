<?php
session_start();
$conexion = new mysqli("localhost", "root", "", "sistema_usuarios");

$data = json_decode(file_get_contents("php://input"), true);
$fecha = $conexion->real_escape_string($data['fecha']);
$titulo = $conexion->real_escape_string($data['titulo']);
$correo = $conexion->real_escape_string($_SESSION['correo']);

$sql = "INSERT INTO citas_bieniestar (correo, fecha, titulo) VALUES (?, ?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("sss", $correo, $fecha, $titulo);
$stmt->execute();
$stmt->close();
?>