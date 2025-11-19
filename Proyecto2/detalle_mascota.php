<?php
require_once __DIR__ . '/conexion.php';

$id = intval($_GET['id'] ?? 0);
$sql = "SELECT * FROM mascotas WHERE mascota_id=? LIMIT 1";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
if ($fila = $res->fetch_assoc()) {
    echo "<img src='" . htmlspecialchars($fila['URL_de_foto']) . "' style='max-width:200px;'><br>";
    echo "<b>Título:</b> " . htmlspecialchars($fila['titulo']) . "<br>";
    echo "<b>Nombre:</b> " . htmlspecialchars($fila['nombre']) . "<br>";
    echo "<b>Edad:</b> " . htmlspecialchars($fila['edad']) . " meses<br>";
    echo "<b>Descripción:</b> " . htmlspecialchars($fila['descripcion']) . "<br>";
    echo "<b>Raza:</b> " . htmlspecialchars($fila['raza']) . "<br>";
    echo "<b>Peso:</b> " . htmlspecialchars($fila['peso']) . "<br>";
    echo "<b>Género:</b> " . htmlspecialchars($fila['genero']) . "<br>";
    echo "<b>Tipo:</b> " . htmlspecialchars($fila['tipo']) . "<br>";
} else {
    echo "Mascota no encontrada.";
}
$stmt->close();