<?php
$conn = new mysqli("localhost", "root", "", "sistema_usuarios");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $area = $conn->real_escape_string($_POST['area']);
    $fecha = $conn->real_escape_string($_POST['fecha_nacimiento']);
    $correo = $conn->real_escape_string($_POST['correo']);
    $rol = $conn->real_escape_string($_POST['rol']);
    $foto_google = isset($_POST['foto_google']) ? $conn->real_escape_string($_POST['foto_google']) : null;

    $foto = null;

    // Procesar imagen subida por formulario (archivo local)
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
        $nombreFoto = uniqid() . '_' . basename($_FILES['foto']['name']);
        $ruta = 'uploads/' . $nombreFoto;

        if (!is_dir('uploads')) {
            mkdir('uploads', 0755, true);
        }

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $ruta)) {
            $foto = $nombreFoto;  // Solo nombre archivo
        }
    }

    // Construcción de la query
    $set = [
        "nombre='$nombre'",
        "area='$area'",
        "fecha='$fecha'",
        "correo='$correo'",
        "rol='$rol'"
    ];

    if (!empty($_POST['contrasena'])) {
        $contrasena = $conn->real_escape_string($_POST['contrasena']);
        $set[] = "contrasena='$contrasena'";
    }

    // Si se subió una foto local, se guarda esa
    // Si no y hay foto de Google, se guarda la URL
    if ($foto) {
        $set[] = "foto='$foto'";
    } elseif ($foto_google) {
        $set[] = "foto='$foto_google'";
    }

    $sql = "UPDATE usuarios SET " . implode(', ', $set) . " WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../view/auth/form_modificar.php?exito=1");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
    
} else {
    echo "Acceso no permitido.";
}

$conn->close();
?>
