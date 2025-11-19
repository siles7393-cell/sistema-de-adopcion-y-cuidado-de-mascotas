<?php
require_once __DIR__ . '/conexion.php';

echo "<h1>Instalación del Sistema de Solicitudes de Adopción</h1>";
echo "<hr>";

// Leer el archivo SQL
$sql_file = __DIR__ . '/SETUP_solicitudes_adopcion.sql';
if (!file_exists($sql_file)) {
    echo "<p style='color: red;'>❌ Error: No se encontró el archivo SETUP_solicitudes_adopcion.sql</p>";
    exit;
}

$sql_content = file_get_contents($sql_file);

// Dividir por punto y coma para ejecutar múltiples comandos
$statements = array_filter(array_map('trim', explode(';', $sql_content)));

$success_count = 0;
$error_count = 0;

foreach ($statements as $sql) {
    if (empty($sql)) continue;
    
    echo "<p>Ejecutando: " . substr($sql, 0, 50) . "...</p>";
    
    if ($conexion->query($sql) === true) {
        echo "<p style='color: green;'>✅ OK</p>";
        $success_count++;
    } else {
        echo "<p style='color: orange;'>⚠️ " . $conexion->error . "</p>";
        $error_count++;
    }
}

echo "<hr>";
echo "<h2>Resultado Final</h2>";
echo "<p>✅ Comandos ejecutados correctamente: " . $success_count . "</p>";
if ($error_count > 0) {
    echo "<p>⚠️ Comandos con advertencia (posiblemente ya existían): " . $error_count . "</p>";
}

// Verificar que las tablas existan
echo "<hr>";
echo "<h2>Verificación</h2>";

$result = $conexion->query("DESCRIBE solicitudes_adopcion");
if ($result) {
    echo "<p style='color: green;'>✅ Tabla 'solicitudes_adopcion' existe</p>";
} else {
    echo "<p style='color: red;'>❌ Tabla 'solicitudes_adopcion' NO existe</p>";
}

$result = $conexion->query("DESCRIBE mascotas LIKE 'estado_adopcion'");
if ($result && $result->num_rows > 0) {
    echo "<p style='color: green;'>✅ Columna 'estado_adopcion' en mascotas existe</p>";
} else {
    echo "<p style='color: orange;'>⚠️ Columna 'estado_adopcion' podría no estar en mascotas (verificar manualmente)</p>";
}

echo "<hr>";
echo "<p><a href='index.html'><button>Volver al inicio</button></a></p>";
?>
