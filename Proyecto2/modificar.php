<?php
require_once __DIR__ . '/conexion.php';

$id = intval($_POST['usuario_id'] ?? 0);
$rol = $_POST['Rol'] ?? '';
$nombre = $_POST['Nombre'] ?? '';
$contra = $_POST['Contraseña'] ?? '';

if ($id > 0 && $rol && $nombre && $contra) {
    $sql = "UPDATE usuarios SET Rol=?, Nombre=?, Contraseña=? WHERE usuario_id=?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssi", $rol, $nombre, $contra, $id);
    if ($stmt->execute()) {
        echo "Usuario modificado correctamente.";
    } else {
        echo "Error al modificar usuario.";
    }
    $stmt->close();
} else {
    echo "Datos incompletos.";
}