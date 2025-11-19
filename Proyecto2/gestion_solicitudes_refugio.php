<?php
session_start();
require_once __DIR__ . '/conexion.php';

if (!isset($_SESSION['idRefugio'])) {
    echo "";
    exit;
}

$refugio_id = $_SESSION['idRefugio'];

$sql = "SELECT sa.solicitud_id, sa.estado, sa.fecha_solicitud,
               sa.dni, sa.domicilio, sa.telefono, sa.edad, sa.convivencia, sa.motivacion,
               u.NombreUsuario, u.ApellidoUsuario,
               m.nombre AS nombre_mascota
        FROM solicitudes_adopcion sa
        INNER JOIN usuarios u ON sa.usuario_id = u.Id
        INNER JOIN mascotas m ON sa.mascota_id = m.mascota_id
        WHERE m.refugio_id = ?
        ORDER BY sa.fecha_solicitud DESC";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $refugio_id);
$stmt->execute();
$res = $stmt->get_result();

while ($fila = $res->fetch_assoc()) {
    $estado = htmlspecialchars($fila['estado']);
    $clase = "solicitud-" . strtolower($estado);
    $deshabilitado = $estado !== 'pendiente' ? 'disabled' : '';
    
    echo "<div class='solicitud-card $clase'>";
    echo "<span class='estado-badge'>" . strtoupper($estado) . "</span>";
    echo "<div class='mascota-info'>🐾 " . htmlspecialchars($fila['nombre_mascota']) . "</div>";
    echo "<div class='adopter-info'><strong>👤 Solicitante:</strong> " . htmlspecialchars($fila['NombreUsuario'] . " " . $fila['ApellidoUsuario']) . "</div>";
    echo "<div class='adopter-info'><strong>📝 DNI:</strong> " . htmlspecialchars($fila['dni']) . "</div>";
    echo "<div class='adopter-info'><strong>📍 Domicilio:</strong> " . htmlspecialchars($fila['domicilio']) . "</div>";
    echo "<div class='adopter-info'><strong>📱 Teléfono:</strong> " . htmlspecialchars($fila['telefono']) . "</div>";
    echo "<div class='adopter-info'><strong>🎂 Edad:</strong> " . htmlspecialchars($fila['edad']) . " años</div>";
    echo "<div class='adopter-info'><strong>🏠 Convivencia:</strong> " . htmlspecialchars($fila['convivencia']) . "</div>";
    echo "<div class='adopter-info'><strong>💭 Motivación:</strong></div>";
    echo "<div class='motivacion'>" . htmlspecialchars($fila['motivacion']) . "</div>";
    
    if ($estado === 'pendiente') {
        echo "<div class='action-buttons'>";
        echo "<button class='btn-aceptar' onclick='procesarSolicitud(" . $fila['solicitud_id'] . ", \"aceptar\")'>✅ Aceptar</button>";
        echo "<button class='btn-rechazar' onclick='procesarSolicitud(" . $fila['solicitud_id'] . ", \"rechazar\")'>❌ Rechazar</button>";
        echo "</div>";
    }
    echo "</div>";
}

if ($res->num_rows == 0) {
    echo "<p style='text-align:center; color:#666;'>No hay solicitudes de adopción</p>";
}

$stmt->close();
?>
