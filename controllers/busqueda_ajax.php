<?php
$conn = new mysqli("localhost", "root", "", "sistema_usuarios");

$term = $_GET['term'];

$sql = "SELECT nombre FROM usuarios WHERE nombre LIKE '%$term%' LIMIT 5";
$result = $conn->query($sql);

$sugerencias = [];

while ($row = $result->fetch_assoc()) {
    $sugerencias[] = $row['nombre'];
}

echo json_encode($sugerencias);
?>
