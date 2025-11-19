<?php
session_start();
require_once "conexion.php";

if (!isset($_SESSION['idUsuario'])) exit("No has iniciado sesión.");
if (!isset($_GET['idTurno'])) exit("No se especificó el turno.");

$idTurno = $_GET['idTurno'];
$idUsuario = $_SESSION['idUsuario'];

$sql = "SELECT fecha FROM turnos WHERE idTurno = ? AND idUsuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ii", $idTurno, $idUsuario);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) exit("Turno no encontrado.");
$turno = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Turno</title>
</head>
<body>
  <h1>Editar Turno</h1>
  <form action="actualizarTurnosUsuario.php" method="POST">
    <input type="hidden" name="idTurno" value="<?php echo $idTurno; ?>">
    <label>Nueva Fecha y Hora:</label>
    <input type="datetime-local" name="fecha" value="<?php echo date('Y-m-d\TH:i', strtotime($turno['fecha'])); ?>" required>
    <button type="submit">Actualizar</button>
  </form>
  <br>
  <a href="turnosAdoptante.php"><button>Volver</button></a>
</body>
</html>
