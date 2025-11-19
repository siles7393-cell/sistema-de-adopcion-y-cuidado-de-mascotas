<?php
require_once __DIR__ . '/conexion.php';

$sql = "SELECT ID, NombreVeterinario, ApellidoVeterinario, FechaNacVeterinario, NumeroMatricula, TelefonoVeterinario, Usuario, Contrasenia FROM veterinarios";
$resultado = $conexion->query($sql);

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($fila['NombreVeterinario']) . "</td>";
        echo "<td>" . htmlspecialchars($fila['ApellidoVeterinario']) . "</td>";
        echo "<td>" . htmlspecialchars($fila['FechaNacVeterinario']) . "</td>";
        echo "<td>" . htmlspecialchars($fila['NumeroMatricula']) . "</td>";
        echo "<td>" . htmlspecialchars($fila['TelefonoVeterinario']) . "</td>";
        echo "<td>" . htmlspecialchars($fila['Usuario']) . "</td>";
        echo "<td>" . htmlspecialchars($fila['Contrasenia']) . "</td>";
        echo "<td><button class='btn-borrar' data-id='" . htmlspecialchars($fila['ID'], ENT_QUOTES, 'UTF-8') . "'>Borrar</button></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='8'>No hay veterinarios registrados</td></tr>";
}