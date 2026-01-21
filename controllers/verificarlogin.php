<?php
session_start();
$conn = new mysqli("localhost", "root", "", "sistema_usuarios");

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$email = $_POST['email'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT * FROM usuarios WHERE correo = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Comparación directa de contraseñas (sin hash)
    if ($password === $user['contrasena']) {
        $_SESSION['nombre'] = $user['nombre'];
        $_SESSION['rol'] = $user['rol'];
        $_SESSION['correo'] = $user['correo'];

        $stmt->close();
        $conn->close();

        // Redirección según rol
        if ($user['rol'] === 'Administrador') {
            header("Location: ../view/usuario/landingpage.php");
        } else {
            header("Location: ../view/usuario/perfil.php");
        }
        exit();
    } else {
        $stmt->close();
        $conn->close();

        header("Location: ../view/auth/login.php?error=Correo o Contraseña incorrecta");
        exit();
    }
} else {
    $stmt->close();
    $conn->close();

    header("Location: ../view/auth/login.php?error=Correo no registrado");
    exit();
}
?>
