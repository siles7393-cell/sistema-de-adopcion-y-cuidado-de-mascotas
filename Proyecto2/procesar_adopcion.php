<?php
session_start();
require_once __DIR__ . '/conexion.php';

if (!isset($_SESSION['idUsuario'])) {
    echo "No autenticado";
    exit;
}

$usuario_id = $_SESSION['idUsuario'];
$mascota_id = $_POST['mascota_id'] ?? null;
$dni = $_POST['dni'] ?? '';
$domicilio = $_POST['domicilio'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$edad = $_POST['edad'] ?? 0;
$convivencia = $_POST['convivencia'] ?? '';
$motivacion = $_POST['motivacion'] ?? '';

if (!$mascota_id) {
    echo "Mascota no especificada";
    exit;
}

// Validar edad >= 18
if ($edad < 18) {
    echo "Debes ser mayor de 18 años";
    exit;
}

// Verificar si ya existe una solicitud pendiente o aceptada
$sqlCheck = "SELECT solicitud_id FROM solicitudes_adopcion 
             WHERE usuario_id = ? AND mascota_id = ? AND estado IN ('pendiente', 'aceptada')";
$stmtCheck = $conexion->prepare($sqlCheck);
$stmtCheck->bind_param("ii", $usuario_id, $mascota_id);
$stmtCheck->execute();
if ($stmtCheck->get_result()->num_rows > 0) {
    echo "Ya tienes una solicitud activa para esta mascota";
    exit;
}

// Verificar estado de la mascota (disponible)
$sqlEstado = "SELECT estado_adopcion FROM mascotas WHERE mascota_id = ? LIMIT 1";
$stmtEstado = $conexion->prepare($sqlEstado);
$stmtEstado->bind_param("i", $mascota_id);
$stmtEstado->execute();
$resEstado = $stmtEstado->get_result();
if ($rowEstado = $resEstado->fetch_assoc()) {
    if ($rowEstado['estado_adopcion'] !== 'disponible') {
        echo "La mascota no está disponible para adopción";
        exit;
    }
} else {
    echo "Mascota no encontrada";
    exit;
}

// Crear solicitud en estado pendiente
$sql = "INSERT INTO solicitudes_adopcion (usuario_id, mascota_id, estado, dni, domicilio, telefono, edad, convivencia, motivacion)
        VALUES (?, ?, 'pendiente', ?, ?, ?, ?, ?, ?)";
$stmt = $conexion->prepare($sql);
$edad = (int)$edad;
$stmt->bind_param("iisssiss", $usuario_id, $mascota_id, $dni, $domicilio, $telefono, $edad, $convivencia, $motivacion);

if ($stmt->execute()) {
    // Marcar mascota como en proceso
    $sqlMascota = "UPDATE mascotas SET estado_adopcion = 'en_proceso' WHERE mascota_id = ?";
    $stmtMascota = $conexion->prepare($sqlMascota);
    $stmtMascota->bind_param("i", $mascota_id);
    $stmtMascota->execute();
    
    echo "OK";
} else {
    echo "Error al crear solicitud: " . $conexion->error;
}
