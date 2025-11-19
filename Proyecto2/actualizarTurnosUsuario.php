<?php
session_start();
require_once "conexion.php";

if (!isset($_SESSION['idUsuario'])) exit("No has iniciado sesión.");

$idTurno = $_POST['idTurno'];
$nuevaFecha = $_POST['fecha'];
$idUsuario = $_SESSION['idUsuario'];

$sql = "UPDATE turnos SET fecha = ? WHERE idTurno = ? AND idUsuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("sii", $nuevaFecha, $idTurno, $idUsuario);

if ($stmt->execute()) {
    echo "<p>Turno actualizado correctamente.</p>";
    echo "<a href='turnosAdoptante.php'><button>Volver</button></a>";
} else {
    echo "<p>Error al actualizar el turno: " . $conexion->error . "</p>";
}
