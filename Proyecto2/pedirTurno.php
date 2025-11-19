<?php
session_start();
require_once __DIR__ . '/conexion.php';

if (!isset($_SESSION['idUsuario'])) {
    echo "<p>No has iniciado sesión. <a href='loginUsuario.html'>Volver al login</a></p>";
    exit();
}

$idUsuario = $_SESSION['idUsuario'];


$sqlPuntos = "SELECT Puntos FROM usuarios WHERE Id = ?";
$stmtPuntos = $conexion->prepare($sqlPuntos);
$stmtPuntos->bind_param("i", $idUsuario);
$stmtPuntos->execute();
$resultadoPuntos = $stmtPuntos->get_result();
$puntosUsuario = $resultadoPuntos->fetch_assoc()['Puntos'];


$sqlMascotas = "SELECT m.mascota_id, m.nombre
                FROM solicitudes_adopcion sa
                INNER JOIN mascotas m ON sa.mascota_id = m.mascota_id
                WHERE sa.usuario_id = ? AND sa.estado = 'aceptada'";
$stmtMascotas = $conexion->prepare($sqlMascotas);
$stmtMascotas->bind_param("i", $idUsuario);
$stmtMascotas->execute();
$resultadoMascotas = $stmtMascotas->get_result();


$sqlVeterinarios = "SELECT ID, NombreVeterinario, ApellidoVeterinario, TelefonoVeterinario FROM veterinarios";
$resultadoVeterinarios = $conexion->query($sqlVeterinarios);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Pedir Turno</title>
  <style>
    body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; }
    .puntos-widget {
      background: #4CAF50;
      color: white;
      padding: 15px 20px;
      border-radius: 5px;
      margin: 20px 0;
      font-size: 18px;
      font-weight: bold;
      text-align: center;
    }
    form { background: #f9f9f9; padding: 20px; border-radius: 5px; }
    label { display: block; margin-top: 15px; font-weight: bold; }
    input, select { width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
    button { background: #2196F3; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; margin-top: 20px; }
    button:hover { background: #0b7dda; }
    .error { color: red; margin-top: 10px; padding: 10px; background: #ffe0e0; border-radius: 4px; display: none; }
    a button { background: #666; }
    a button:hover { background: #444; }
  </style>
</head>
<body>
  <h1>Pedir un Turno</h1>
  
  <div class="puntos-widget">
    💳 Puntos Disponibles: <span id="puntos-display"><?php echo $puntosUsuario; ?></span>
  </div>

  <div class="error" id="error-message"></div>

  <form action="guardarTurno.php" method="POST" id="formTurno">
    <label for="fecha">Fecha del Turno:</label>
    <input type="datetime-local" id="fecha" name="fecha" required><br><br>

    <label for="mascota">Selecciona tu Mascota Adoptada:</label>
    <select name="idMascota" id="mascota" required>
      <option value="">-- Elige una mascota --</option>
      <?php while ($fila = $resultadoMascotas->fetch_assoc()): ?>
        <option value="<?php echo $fila['mascota_id']; ?>">
          <?php echo htmlspecialchars($fila['nombre']); ?>
        </option>
      <?php endwhile; ?>
    </select><br><br>

    <label for="veterinario">Selecciona un Veterinario:</label>
    <select name="idVeterinario" id="veterinario" required>
      <option value="">-- Elige un veterinario --</option>
      <?php while ($vet = $resultadoVeterinarios->fetch_assoc()): ?>
        <option value="<?php echo $vet['ID']; ?>">
          <?php echo htmlspecialchars($vet['NombreVeterinario'] . " " . $vet['ApellidoVeterinario'] . " - Tel: " . $vet['TelefonoVeterinario']); ?>
        </option>
      <?php endwhile; ?>
    </select><br><br>

  
    <label for="metodoPago">Método de Pago:</label>
    <select name="metodoPago" id="metodoPago" required>
      <option value="En_Persona">En persona</option>
      <?php if ($puntosUsuario >= 50): ?>
        <option value="puntos">Pagar con puntos (50 puntos)</option>
      <?php else: ?>
        <option value="puntos" disabled title="No tienes suficientes puntos">Pagar con puntos (50 puntos)</option>
      <?php endif; ?>
    </select><br><br>

    <button type="submit">Guardar Turno</button>
  </form>

  <br>
  <a href="adoptante.php"><button>Atras</button></a>

  <script>
    function establecerFechaMinima() {
      const inputFecha = document.getElementById('fecha');
      const hoy = new Date();
      const mañana = new Date(hoy);
      mañana.setDate(mañana.getDate() + 1);
      
      const año = mañana.getFullYear();
      const mes = String(mañana.getMonth() + 1).padStart(2, '0');
      const día = String(mañana.getDate()).padStart(2, '0');
      const hora = '09';
      const minuto = '00';
      
      inputFecha.min = `${año}-${mes}-${día}T${hora}:${minuto}`;
    }

    function validarFecha() {
      const inputFecha = document.getElementById('fecha');
      const fechaSeleccionada = new Date(inputFecha.value);
      const hoy = new Date();
      const mañana = new Date(hoy);
      mañana.setDate(mañana.getDate() + 1);
      mañana.setHours(0, 0, 0, 0);
      fechaSeleccionada.setHours(0, 0, 0, 0);

      const errorDiv = document.getElementById('error-message');

      if (fechaSeleccionada < mañana) {
        errorDiv.textContent = '❌ Debes seleccionar una fecha a partir de mañana';
        errorDiv.style.display = 'block';
        return false;
      } else {
        errorDiv.style.display = 'none';
        return true;
      }
    }

    document.getElementById('fecha').addEventListener('change', validarFecha);
    document.getElementById('formTurno').addEventListener('submit', function(e) {
      if (!validarFecha()) {
        e.preventDefault();
      }
    });

    window.addEventListener('load', establecerFechaMinima);
  </script>
</body>
</html>
