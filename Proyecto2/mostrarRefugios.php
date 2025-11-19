<?php
require_once __DIR__ . '/conexion.php';

$sql = "SELECT Id, NombreRefugio, UbicacionRefugio, TelefonoRefugio, UsernameRefugio, PasswordRefugio FROM refugios";
$resultado = $conexion->query($sql);

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($fila['NombreRefugio']) . "</td>";
        echo "<td>" . htmlspecialchars($fila['UbicacionRefugio']) . "</td>";
        echo "<td>" . htmlspecialchars($fila['TelefonoRefugio']) . "</td>";
        echo "<td>" . htmlspecialchars($fila['UsernameRefugio']) . "</td>";
        echo "<td>" . htmlspecialchars($fila['PasswordRefugio']) . "</td>";
        echo "<td>
          <button class='btn-borrar'
            data-id='" . htmlspecialchars($fila['Id']) . "'>Borrar</button>
        </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6'>No hay refugios registrados</td></tr>";
}
