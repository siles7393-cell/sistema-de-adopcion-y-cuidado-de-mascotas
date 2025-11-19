<?php
session_start();
require_once __DIR__ . '/conexion.php';

if (!isset($_SESSION['idVeterinario'])) {
    exit("No has iniciado sesión.");
}

if (!isset($_POST['idTurno'])) {
    exit("No se especificó el turno.");
}

$idTurno = $_POST['idTurno'];

$sql = "DELETE FROM turnos WHERE idTurno = ? AND idVeterinario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ii", $idTurno, $_SESSION['idVeterinario']);

if ($stmt->execute()) {
    echo "<p>Turno eliminado correctamente.</p>";
    echo "<a href='verTurnosVeterinario.php'><button>Volver a Turnos</button></a>";
} else {
    echo "<p>Error al eliminar el turno: " . $conexion->error . "</p>";
}
