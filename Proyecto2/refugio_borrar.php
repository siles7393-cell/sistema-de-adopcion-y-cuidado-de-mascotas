<?php
require_once __DIR__ . '/conexion.php';
session_start();

$id = intval($_POST['id'] ?? 0);
$usuario_id = intval($_SESSION['idRefugio'] ?? 0);
if ($usuario_id <= 0 || $id <= 0) {
    echo "No tienes permiso para borrar.";
    exit;
}

if ($id > 0) {
    $sql = "DELETE FROM mascotas WHERE mascota_id = ? AND refugio_id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ii", $id, $usuario_id);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        echo "Mascota borrada correctamente.";
        echo "<a href='refugio_ABM.html'><button>Confirmar</button></a>";
    } else {
        echo "Error al borrar mascota o no tienes permiso.";
    }
    $stmt->close();
} else {
    echo "ID inválido.";
}