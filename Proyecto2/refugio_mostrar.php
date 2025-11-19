<?php
require_once __DIR__ . '/conexion.php';
session_start();

$usuario_id = intval($_SESSION['idRefugio'] ?? 0);
if ($usuario_id <= 0) {
    echo "<tr><td colspan='10'>No tienes permiso para ver mascotas.</td></tr>";
    exit;
}

$sql = "SELECT mascota_id, URL_de_foto, nombre, edad, descripcion, raza, peso, genero, tipo, titulo FROM mascotas WHERE refugio_id=?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        echo "<tr>";
        echo "<td><img src='" . htmlspecialchars($fila['URL_de_foto']) . "' style='max-width:80px;'></td>";
        echo "<td>" . htmlspecialchars($fila['titulo']) . "</td>";
        echo "<td>" . htmlspecialchars($fila['nombre']) . "</td>";
        echo "<td>" . htmlspecialchars($fila['edad']) . "</td>";
        echo "<td>" . htmlspecialchars($fila['descripcion']) . "</td>";
        echo "<td>" . htmlspecialchars($fila['raza']) . "</td>";
        echo "<td>" . htmlspecialchars($fila['peso']) . "</td>";
        echo "<td>" . htmlspecialchars($fila['genero']) . "</td>";
        echo "<td>" . htmlspecialchars($fila['tipo']) . "</td>";
        echo "<td>
          <button class='btn-editar'
            data-id='" . htmlspecialchars($fila['mascota_id']) . "'
            data-titulo='" . htmlspecialchars($fila['titulo']) . "'
            data-foto='" . htmlspecialchars($fila['URL_de_foto']) . "'
            data-nombre='" . htmlspecialchars($fila['nombre']) . "'
            data-edad='" . htmlspecialchars($fila['edad']) . "'
            data-descripcion='" . htmlspecialchars($fila['descripcion']) . "'
            data-raza='" . htmlspecialchars($fila['raza']) . "'
            data-peso='" . htmlspecialchars($fila['peso']) . "'
            data-genero='" . htmlspecialchars($fila['genero']) . "'
            data-tipo='" . htmlspecialchars($fila['tipo']) . "'>Modificar</button>
          <button class='btn-borrar'
            data-id='" . htmlspecialchars($fila['mascota_id']) . "'>Borrar</button>
        </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='10'>No hay registros</td></tr>";
}
$stmt->close();