<?php
session_start();
require_once "conexion.php";

if (!isset($_SESSION['idUsuario'])) exit("No has iniciado sesión.");
if (!isset($_POST['idTurno'])) exit("No se especificó el turno.");

$idTurno = $_POST['idTurno'];
$idUsuario = $_SESSION['idUsuario'];

$sql = "DELETE FROM turnos WHERE idTurno = ? AND idUsuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ii", $idTurno, $idUsuario);

if ($stmt->execute()) {
    echo "<p>Turno eliminado correctamente.</p>";
    echo "<a href='turnosAdoptante.php'><button>Volver a Mis Turnos</button></a>";
} else {
    echo "<p>Error al eliminar el turno: " . $conexion->error . "</p>";
}
