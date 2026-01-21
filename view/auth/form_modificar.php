<?php
$conn = new mysqli("localhost", "root", "", "sistema_usuarios");

// Autocompletar nombres
$nombres = [];
$sql_nombres = "SELECT nombre FROM usuarios";
$result_nombres = $conn->query($sql_nombres);
if ($result_nombres && $result_nombres->num_rows > 0) {
    while ($row = $result_nombres->fetch_assoc()) {
        $nombres[] = $row['nombre'];
    }
}

// Buscar usuario por nombre
$usuario = null;
if (isset($_POST['buscar_nombre'])) {
    $nombre_buscar = $conn->real_escape_string($_POST['buscar_nombre']);
    $sql = "SELECT * FROM usuarios WHERE nombre = '$nombre_buscar' LIMIT 1";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
    } else {
        echo "<p style='color:red; text-align:center;'>Usuario no encontrado</p>";
    }
}

// Función para detectar si es URL
function es_url($string) {
    return filter_var($string, FILTER_VALIDATE_URL) !== false;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Modificar Usuario</title>
  <style>
    body { font-family: Arial; background: #f0f0f0; }
    .contenedor {
      width: 400px; background: #fff; margin: 20px auto;
      padding: 20px; border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      position: relative;
    }
    .titulo {
      background: #f45b0f; padding: 10px;
      color: #fff; font-weight: bold; text-align: center;
      margin-bottom: 15px;
    }
    label { display: block; margin-top: 10px; font-weight: bold; }
    input, select {
      width: 100%; padding: 8px; margin-top: 4px;
      border: 1px solid #ccc; border-radius: 4px;
      box-sizing: border-box;
    }
    .buscador {
      position: relative;
    }
    #sugerencias {
      position: absolute;
      top: 100%;
      left: 0;
      width: 100%;
      background-color: white;
      border: 1px solid #ccc;
      border-top: none;
      z-index: 99;
      max-height: 150px;
      overflow-y: auto;
      border-radius: 0 0 6px 6px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    #sugerencias div {
      padding: 8px 10px;
      cursor: pointer;
    }
    #sugerencias div:hover {
      background-color: #f0f0f0;
    }
    button {
      background: #f45b0f; color: white; padding: 10px;
      border: none; width: 100%; margin-top: 20px;
      font-weight: bold; border-radius: 4px;
      cursor: pointer;
    }
    img {
      display: block;
      margin: 10px auto;
      max-width: 100px;
      border-radius: 50%;
    }
    .alerta {
      background: #d4edda;
      color: #155724;
      padding: 10px;
      margin-bottom: 15px;
      border-radius: 4px;
      text-align: center;
    }
    /* Botón regresar fijo en esquina */
    #btn-regresar {
      position: fixed;
      top: 15px;
      left: 15px;
      background-color: #f45b0f;
      color: white;
      border: none;
      padding: 6px 10px;
      font-weight: bold;
      font-size: 14px;
      border-radius: 6px;
      cursor: pointer;
      box-shadow: 0 2px 6px rgba(0,0,0,0.2);
      transition: background-color 0.3s;
      z-index: 1000;
      width: auto;
    }
    #btn-regresar:hover {
      background-color: #e55a00;
    }
  </style>
</head>
<body>

<!-- Botón regresar -->
<button id="btn-regresar" onclick="history.back()">← Regresar</button>

<div class="contenedor">
  <div class="titulo">MODIFICAR USUARIO</div>

  <?php if (isset($_GET['exito']) && $_GET['exito'] == 1): ?>
    <div class="alerta">✅ Usuario modificado correctamente.</div>
  <?php endif; ?>

  <!-- Formulario de búsqueda -->
  <form method="POST" autocomplete="off">
    <label>Buscar por nombre:</label>
    <div class="buscador">
      <input type="text" id="buscar_nombre" name="buscar_nombre" required>
      <div id="sugerencias"></div>
    </div>
    <button type="submit">Buscar</button>
  </form>

  <?php if ($usuario): ?>
  <!-- Formulario de modificación -->
  <form action="../../controllers/modificar.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= htmlspecialchars($usuario['id']) ?>">

    <!-- Foto actual -->
    <label>Foto actual:</label>
    <?php
      if (es_url($usuario['foto'])) {
          $src = $usuario['foto'];
      } else {
          $src = "uploads/" . $usuario['foto'];
      }
    ?>
    <img src="<?= htmlspecialchars($src) ?>" alt="Foto de perfil">

    <!-- Campo oculto para enviar foto de Google si no se cambia -->
    <input type="hidden" name="foto_google" value="<?= htmlspecialchars($usuario['foto']) ?>">

    <!-- Nueva imagen -->
    <label>Subir nueva foto:</label>
    <input type="file" name="foto">

    <label>Nombre:</label>
    <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>

    <label>Área:</label>
    <input type="text" name="area" value="<?= htmlspecialchars($usuario['area']) ?>">

    <label>Fecha de Nacimiento:</label>
    <input type="date" name="fecha_nacimiento" value="<?= htmlspecialchars($usuario['fecha']) ?>">

    <label>Correo:</label>
    <input type="email" name="correo" value="<?= htmlspecialchars($usuario['correo']) ?>">

    <label>Contraseña:</label>
    <input type="password" name="contrasena" placeholder="Nueva contraseña (opcional)">

    <label>Rol:</label>
    <select name="rol">
      <option <?= $usuario['rol'] == '' ? 'selected' : '' ?>>Selección</option>
      <option <?= $usuario['rol'] == 'Usuario Base' ? 'selected' : '' ?>>Usuario Base</option>
      <option <?= $usuario['rol'] == 'Nutriólogo' ? 'selected' : '' ?>>Nutriólogo</option>
      <option <?= $usuario['rol'] == 'Psicólogo' ? 'selected' : '' ?>>Psicólogo</option>
      <option <?= $usuario['rol'] == 'Coach' ? 'selected' : '' ?>>Coach</option>
      <option <?= $usuario['rol'] == 'Admin' ? 'selected' : '' ?>>Administrador</option>
    </select>

    <button type="submit">Aplicar Cambios</button>
  </form>
  <?php endif; ?>
</div>

<script>
document.getElementById('buscar_nombre').addEventListener('keyup', function () {
    const valor = this.value;

    if (valor.length === 0) {
        document.getElementById('sugerencias').innerHTML = '';
        return;
    }

    fetch('../../controllers/busqueda_ajax.php?term=' + encodeURIComponent(valor))
        .then(res => res.json())
        .then(data => {
            let html = '';
            data.forEach(nombre => {
                html += '<div onclick="seleccionarNombre(\'' + nombre + '\')">' + nombre + '</div>';
            });
            document.getElementById('sugerencias').innerHTML = html;
        });
});

function seleccionarNombre(nombre) {
    document.getElementById('buscar_nombre').value = nombre;
    document.getElementById('sugerencias').innerHTML = '';
}
</script>

</body>
</html>
