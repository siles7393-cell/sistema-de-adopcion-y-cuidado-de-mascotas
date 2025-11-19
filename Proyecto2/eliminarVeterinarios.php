<?php
require_once __DIR__ . '/conexion.php';

if (!isset($_POST['ID'])) {
    echo "No se recibió el ID.";
    exit;
}

$id = intval($_POST['ID']);

$stmt = $conexion->prepare('DELETE FROM veterinarios WHERE ID = ?');
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    echo "OK";
} else {
    echo "Error al eliminar: " . $conexion->error;
}
$stmt->close();
?>