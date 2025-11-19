<?php
require_once __DIR__ . '/conexion.php';

if (!isset($_POST['Id'])) {
    echo "No se recibió el ID.";
    exit;
}

$id = intval($_POST['Id']);

$stmt = $conexion->prepare('DELETE FROM usuarios WHERE Id = ?');
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    echo "OK";
} else {
    echo "Error al eliminar: " . $conexion->error;
}
$stmt->close();
?>