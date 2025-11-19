<?php
require_once __DIR__ . '/conexion.php';
session_start();

echo "<h2>Información de Diagnóstico</h2>";
echo "<pre>";
echo "===== SESIÓN =====\n";
echo "idRefugio en sesión: " . (isset($_SESSION['idRefugio']) ? $_SESSION['idRefugio'] : "NO DEFINIDO") . "\n";
echo "Tipo: " . gettype($_SESSION['idRefugio'] ?? null) . "\n";
echo "Como int: " . intval($_SESSION['idRefugio'] ?? 0) . "\n";

echo "\n===== MASCOTAS DEL REFUGIO =====\n";
$usuario_id = intval($_SESSION['idRefugio'] ?? 0);
if ($usuario_id > 0) {
    $sql = "SELECT mascota_id, nombre, refugio_id FROM mascotas WHERE refugio_id=?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows > 0) {
        echo "Mascotas encontradas: " . $resultado->num_rows . "\n";
        while ($fila = $resultado->fetch_assoc()) {
            echo "ID: " . $fila['mascota_id'] . " | Nombre: " . $fila['nombre'] . " | Refugio ID: " . $fila['refugio_id'] . "\n";
        }
    } else {
        echo "No hay mascotas para este refugio\n";
    }
    $stmt->close();
} else {
    echo "ERROR: Usuario ID no válido (0 o negativo)\n";
}

echo "\n===== REFUGIOS EN BD =====\n";
$sql = "SELECT Id, NombreRefugio FROM refugios";
$resultado = $conexion->query($sql);
if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        echo "ID: " . $fila['Id'] . " | Nombre: " . $fila['NombreRefugio'] . "\n";
    }
} else {
    echo "No hay refugios en la BD\n";
}

echo "</pre>";
echo "<a href='refugio_ABM.html'><button>Volver</button></a>";
?>
