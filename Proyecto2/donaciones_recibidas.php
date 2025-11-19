<?php
require_once __DIR__ . '/conexion.php';
session_start();

$refugio_id = $_SESSION['idRefugio'] ?? null;
if (!$refugio_id) {
    echo "<p>No has iniciado sesión.</p>";
    exit;
}

$sql = "SELECT d.donacion_id, 
               u.NombreUsuario, u.ApellidoUsuario, 
               r.NombreRefugio, 
               d.importe, d.fecha
        FROM donaciones d
        JOIN usuarios u ON d.usuario_id = u.Id
        JOIN refugios r ON d.refugio_id = r.Id
        WHERE d.refugio_id = ?
        ORDER BY d.fecha DESC";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $refugio_id);
$stmt->execute();
$res = $stmt->get_result();


echo "<table border='1' cellpadding='6'>";
echo "<tr>
        <th>ID Donación</th>
        <th>Donante</th>
        <th>Refugio</th>
        <th>Importe</th>
        <th>Fecha</th>
      </tr>";

if ($res->num_rows > 0) {
    while ($fila = $res->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $fila['donacion_id'] . "</td>";
        echo "<td>" . htmlspecialchars($fila['NombreUsuario'] . " " . $fila['ApellidoUsuario']) . "</td>";
        echo "<td>" . htmlspecialchars($fila['NombreRefugio']) . "</td>";
        echo "<td>$" . number_format($fila['importe'], 2) . "</td>";
        echo "<td>" . $fila['fecha'] . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>No se registran donaciones.</td></tr>";
}

echo "</table>";

$stmt->close();
?>
