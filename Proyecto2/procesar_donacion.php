<?php
require_once __DIR__ . '/conexion.php';
session_start();

$usuario_id = $_SESSION['idUsuario'] ?? null;
$refugio_id = $_POST['refugio_id'] ?? '';
$importe = $_POST['importe'] ?? '';

if (!$usuario_id) {
    echo "Debes iniciar sesión para donar.";
    exit;
}
if (!$refugio_id || !$importe || $importe <= 0) {
    echo "Datos inválidos.";
    exit;
}

$sql = "INSERT INTO donaciones (usuario_id, refugio_id, importe) VALUES (?, ?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("iid", $usuario_id, $refugio_id, $importe);
if ($stmt->execute()) {
    echo "¡Gracias por tu donación!";
} else {
    echo "Error al procesar la donación.";
}
$stmt->close();