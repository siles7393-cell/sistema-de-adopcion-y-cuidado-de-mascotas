<?php
require_once __DIR__ . '/conexion.php';
session_start();

$usuario_id = $_SESSION['idUsuario'] ?? null;
if (!$usuario_id) {
    echo "<p>No has iniciado sesión.</p>";
    exit;
}


$sql = "SELECT m.mascota_id, m.URL_de_foto, m.titulo, m.nombre, m.raza
        FROM mascotas m
        INNER JOIN solicitudes_adopcion sa ON m.mascota_id = sa.mascota_id
        WHERE sa.usuario_id = ? AND sa.estado = 'aceptada'
        GROUP BY m.mascota_id";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
    while ($fila = $res->fetch_assoc()) {
        echo "<div class='card'>";
        echo "<img src='" . htmlspecialchars($fila['URL_de_foto']) . "' alt='foto'>";
        echo "<h4>" . htmlspecialchars($fila['titulo']) . "</h4>";
        echo "<p><b>Nombre:</b> " . htmlspecialchars($fila['nombre']) . "</p>";
        echo "<p><b>Raza:</b> " . htmlspecialchars($fila['raza']) . "</p>";
        echo "<button class='btn-detalles' data-id='" . $fila['mascota_id'] . "'>Ver detalles</button>";
        echo "</div>";
    }
} else {
    echo "<p>No has adoptado mascotas aún.</p>";
}
$stmt->close();