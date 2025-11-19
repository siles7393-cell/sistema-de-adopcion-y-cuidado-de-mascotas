<?php
require_once __DIR__ . '/conexion.php';

$id = intval($_POST['usuario_id'] ?? 0);

if ($id > 0) {
    $sql = "DELETE FROM usuarios WHERE usuario_id = $id";
    if ($conexion->query($sql)) {
        echo "Usuario borrado correctamente.";
    } else {
        echo "Error al borrar usuario.";
    }
} else {
    echo "ID inválido.";
}