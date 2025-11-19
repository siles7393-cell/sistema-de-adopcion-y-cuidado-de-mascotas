<?php
require_once __DIR__ . '/conexion.php';

$sql = "SELECT Id, NombreUsuario, ApellidoUsuario, FechaNacUsuario, TelefonoUsuario, Username, Password, Puntos FROM usuarios";
$resultado = $conexion->query($sql);

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($fila['NombreUsuario']) . "</td>";
        echo "<td>" . htmlspecialchars($fila['ApellidoUsuario']) . "</td>";
        echo "<td>" . htmlspecialchars($fila['FechaNacUsuario']) . "</td>";
        echo "<td>" . htmlspecialchars($fila['TelefonoUsuario']) . "</td>";
        echo "<td>" . htmlspecialchars($fila['Username']) . "</td>";
        echo "<td>" . htmlspecialchars($fila['Password']) . "</td>";
        echo "<td>" . htmlspecialchars($fila['Puntos']) . "</td>";
        echo "<td>
          <button class='btn-borrar'
            data-id='" . htmlspecialchars($fila['Id']) . "'>Borrar</button>
        </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='8'>No hay registros</td></tr>";
}
