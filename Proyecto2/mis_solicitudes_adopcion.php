<?php
session_start();
require_once __DIR__ . '/conexion.php';

if (!isset($_SESSION['idUsuario'])) {
    echo "";
    exit;
}

$usuario_id = $_SESSION['idUsuario'];

$sql = "SELECT sa.solicitud_id, sa.estado, sa.fecha_solicitud, sa.fecha_respuesta,
               m.nombre AS nombre_mascota, r.NombreRefugio AS refugio_nombre
        FROM solicitudes_adopcion sa
        INNER JOIN mascotas m ON sa.mascota_id = m.mascota_id
        INNER JOIN refugios r ON m.refugio_id = r.Id
        WHERE sa.usuario_id = ?
        ORDER BY sa.fecha_solicitud DESC";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$res = $stmt->get_result();

while ($fila = $res->fetch_assoc()) {
    $estado = htmlspecialchars($fila['estado']);
    $clase = "solicitud-" . strtolower($estado);
    echo "<div class='solicitud-card $clase'>";
    echo "<span class='estado-badge'>" . strtoupper($estado) . "</span>";
    echo "<div class='mascota-info'>🐾 " . htmlspecialchars($fila['nombre_mascota']) . "</div>";
    echo "<div class='refugio-info'>🏠 " . htmlspecialchars($fila['refugio_nombre']) . "</div>";
    echo "<div class='fechas'>";
    echo "<strong>Fecha solicitud:</strong> " . date('d/m/Y', strtotime($fila['fecha_solicitud'])) . "<br>";
    if ($fila['fecha_respuesta']) {
        echo "<strong>Fecha respuesta:</strong> " . date('d/m/Y', strtotime($fila['fecha_respuesta'])) . "<br>";
    }
    echo "</div>";
    echo "</div>";
}

if ($res->num_rows == 0) {
    echo "<p style='text-align:center; color:#666;'>No tienes solicitudes de adopción</p>";
}

$stmt->close();
?>
