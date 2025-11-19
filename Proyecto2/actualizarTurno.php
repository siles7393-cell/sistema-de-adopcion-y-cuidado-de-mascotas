<?php
session_start();
require_once __DIR__ . '/conexion.php';

if (!isset($_SESSION['idVeterinario'])) {
    exit("No has iniciado sesión.");
}

$idTurno = intval($_POST['idTurno'] ?? 0);
$nuevaFecha = $_POST['fecha'] ?? '';

// Convertir el formato datetime-local a datetime SQL
$fechaObj = DateTime::createFromFormat('Y-m-d\TH:i', $nuevaFecha);
if (!$fechaObj) {
    echo "<p>Formato de fecha inválido.</p>";
    echo "<a href='verTurnosVeterinario.php'><button>Volver a Turnos</button></a>";
    exit;
}

$nuevaFechaFormato = $fechaObj->format('Y-m-d H:i:s');

// Validar que la fecha sea al menos un día en el futuro (mañana)
$hoy = new DateTime();
$hoy->setTime(0, 0, 0);
$mañana = (new DateTime())->add(new DateInterval('P1D'));
$mañana->setTime(0, 0, 0);

// Comparar la fecha ingresada con mañana
if ($fechaObj < $mañana) {
    echo "<p style='color:red; text-align:center;'>Error: La fecha del turno debe ser al menos un día después de hoy.</p>";
    echo "<a href='editarTurno.php?idTurno=$idTurno'><button>Volver a Editar</button></a>";
    exit;
}

$sql = "UPDATE turnos SET fecha = ? WHERE idTurno = ? AND idVeterinario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("sii", $nuevaFechaFormato, $idTurno, $_SESSION['idVeterinario']);

if ($stmt->execute()) {
    echo "<p style='color:green; text-align:center;'>Turno actualizado correctamente.</p>";
    echo "<div style='text-align:center;'><a href='verTurnosVeterinario.php'><button>Volver a Turnos</button></a></div>";
} else {
    echo "<p>Error al actualizar el turno: " . $conexion->error . "</p>";
}
$stmt->close();
$conexion->close();
?>
