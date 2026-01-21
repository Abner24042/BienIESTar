<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Agregar Usuario</title>
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
    button {
      background: #f45b0f; color: white; padding: 10px;
      border: none; width: 100%; margin-top: 20px;
      font-weight: bold; border-radius: 4px;
      cursor: pointer;
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
  padding: 6px 10px;        /* Menos padding para que sea más pequeño */
  font-weight: bold;
  font-size: 14px;          /* Tamaño de fuente menor */
  border-radius: 6px;
  cursor: pointer;
  box-shadow: 0 2px 6px rgba(0,0,0,0.2);
  transition: background-color 0.3s;
  z-index: 1000;
  width: auto;              /* Para que no sea ancho completo */
}
#btn-regresar:hover {
  background-color: #e55a00;
}

  </style>
</head>
<body>

<button id="btn-regresar" onclick="history.back()">← Regresar</button>

<div class="contenedor">
  <div class="titulo">AGREGAR USUARIO</div>

  <?php if (isset($_GET['exito']) && $_GET['exito'] == 1): ?>
    <div class="alerta">✅ Usuario agregado correctamente.</div>
  <?php endif; ?>

  <form action="../../controllers/agregar.php" method="POST" enctype="multipart/form-data">
    <label>Foto de perfil:</label>
    <input type="file" name="foto">

    <label>Nombre:</label>
    <input type="text" name="nombre" required>

    <label>Área:</label>
    <input type="text" name="area" required>

    <label>Fecha de Nacimiento:</label>
    <input type="date" name="fecha_nacimiento" required>

    <label>Correo:</label>
    <input type="email" name="correo" required>

    <label>Contraseña:</label>
    <input type="password" name="contrasena" required>

    <label>Rol:</label>
    <select name="rol" required>
      <option value="">Selección</option>
      <option>Usuario Base</option>
      <option>Nutriólogo</option>
      <option>Psicólogo</option>
      <option>Coach</option>
      <option>Admin</option>
    </select>

    <button type="submit">Agregar Usuario</button>
  </form>
</div>

</body>
</html>
