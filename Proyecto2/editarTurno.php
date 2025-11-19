<?php
session_start();
require_once __DIR__ . '/conexion.php';

if (!isset($_SESSION['idVeterinario'])) {
    exit("No has iniciado sesión.");
}

if (!isset($_GET['idTurno'])) {
    exit("No se especificó el turno.");
}

$idTurno = $_GET['idTurno'];


$sql = "SELECT fecha FROM turnos WHERE idTurno = ? AND idVeterinario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ii", $idTurno, $_SESSION['idVeterinario']);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    exit("Turno no encontrado.");
}

$turno = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Turno</title>
  <style>
    body { font-family: Arial; max-width: 600px; margin: 50px auto; }
    form { border: 1px solid #ccc; padding: 20px; border-radius: 8px; }
    input, button { padding: 8px; margin: 10px 0; width: 100%; box-sizing: border-box; }
    button { background-color: #4CAF50; color: white; cursor: pointer; border: none; border-radius: 4px; }
    button:hover { background-color: #45a049; }
    a button { background-color: #008CBA; text-align: center; }
    a button:hover { background-color: #007399; }
  </style>
</head>
<body>
  <h1>Editar Turno</h1>
  <form action="actualizarTurno.php" method="POST" onsubmit="return validarFecha()">
    <input type="hidden" name="idTurno" value="<?php echo $idTurno; ?>">
    <label for="fecha">Nueva Fecha y Hora:</label>
    <input type="datetime-local" name="fecha" id="fecha" value="<?php echo date('Y-m-d\TH:i', strtotime($turno['fecha'])); ?>" required>
    <small style="color: #666;">*Debe ser al menos un día después de hoy</small>
    <button type="submit">Actualizar</button>
  </form>
  <br>
  <a href="verTurnosVeterinario.php"><button>Volver</button></a>
  
  <script>
    function validarFecha() {
      const inputFecha = document.getElementById('fecha').value;
      if (!inputFecha) {
        alert('Por favor selecciona una fecha');
        return false;
      }
      
      const fecha = new Date(inputFecha);
      const hoy = new Date();
      const mañana = new Date();
      mañana.setDate(mañana.getDate() + 1);
      mañana.setHours(0, 0, 0, 0);
      
      if (fecha < mañana) {
        alert('La fecha debe ser al menos un día después de hoy');
        return false;
      }
      return true;
    }
    
    // Establecer el atributo min para que no permita seleccionar fechas pasadas
    const inputFecha = document.getElementById('fecha');
    const mañana = new Date();
    mañana.setDate(mañana.getDate() + 1);
    const año = mañana.getFullYear();
    const mes = String(mañana.getMonth() + 1).padStart(2, '0');
    const día = String(mañana.getDate()).padStart(2, '0');
    inputFecha.min = año + '-' + mes + '-' + día + 'T00:00';
  </script>
</body>
</html>
