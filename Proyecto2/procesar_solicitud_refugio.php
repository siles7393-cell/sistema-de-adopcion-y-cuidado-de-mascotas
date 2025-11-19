<?php
session_start();
require_once __DIR__ . '/conexion.php';

if (!isset($_SESSION['idRefugio'])) {
    echo "No autenticado";
    exit;
}

$solicitud_id = $_POST['solicitud_id'] ?? null;
$accion = $_POST['accion'] ?? null; // 'aceptar' o 'rechazar'

if (!$solicitud_id || !$accion) {
    echo "Datos incompletos";
    exit;
}

// Obtener datos de la solicitud
$sqlSolicitud = "SELECT usuario_id, mascota_id FROM solicitudes_adopcion WHERE solicitud_id = ?";
$stmtSolicitud = $conexion->prepare($sqlSolicitud);
$stmtSolicitud->bind_param("i", $solicitud_id);
$stmtSolicitud->execute();
$resSolicitud = $stmtSolicitud->get_result();
if ($resSolicitud->num_rows == 0) {
    echo "Solicitud no encontrada";
    exit;
}
$solicitud = $resSolicitud->fetch_assoc();
$usuario_id = $solicitud['usuario_id'];
$mascota_id = $solicitud['mascota_id'];

try {
    if (!$conexion->begin_transaction()) {
        throw new Exception("No se pudo iniciar la transacción");
    }
    if ($accion === 'aceptar') {
        // Actualizar solicitud a aceptada
        $sqlUpdate = "UPDATE solicitudes_adopcion SET estado = 'aceptada', fecha_respuesta = NOW() WHERE solicitud_id = ?";
        $stmt = $conexion->prepare($sqlUpdate);
        $stmt->bind_param("i", $solicitud_id);
        $stmt->execute();

        // Marcar mascota como adoptada
        $sqlMascota = "UPDATE mascotas SET estado_adopcion = 'adoptado' WHERE mascota_id = ?";
        $stmt = $conexion->prepare($sqlMascota);
        $stmt->bind_param("i", $mascota_id);
        $stmt->execute();

        // Dar 50 puntos al usuario
        $sqlPuntos = "UPDATE usuarios SET Puntos = Puntos + 50 WHERE Id = ?";
        $stmt = $conexion->prepare($sqlPuntos);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();

        // Rechazar todas las otras solicitudes de esta mascota
        $sqlReject = "UPDATE solicitudes_adopcion SET estado = 'rechazada', fecha_respuesta = NOW() 
                      WHERE mascota_id = ? AND solicitud_id != ?";
        $stmt = $conexion->prepare($sqlReject);
        $stmt->bind_param("ii", $mascota_id, $solicitud_id);
        $stmt->execute();

        $conexion->commit();
        echo "OK";
    } elseif ($accion === 'rechazar') {
        // Actualizar solicitud a rechazada
        $sqlUpdate = "UPDATE solicitudes_adopcion SET estado = 'rechazada', fecha_respuesta = NOW() WHERE solicitud_id = ?";
        $stmt = $conexion->prepare($sqlUpdate);
        $stmt->bind_param("i", $solicitud_id);
        $stmt->execute();

        // Verificar si hay más solicitudes pendientes para esta mascota
        $sqlCheck = "SELECT COUNT(*) as count FROM solicitudes_adopcion 
                     WHERE mascota_id = ? AND estado = 'pendiente'";
        $stmt = $conexion->prepare($sqlCheck);
        $stmt->bind_param("i", $mascota_id);
        $stmt->execute();
        $resCheck = $stmt->get_result();
        $row = $resCheck->fetch_assoc();

        // Si no hay más pendientes, marcar mascota como disponible
        if ($row['count'] == 0) {
            $sqlMascota = "UPDATE mascotas SET estado_adopcion = 'disponible' WHERE mascota_id = ?";
            $stmt = $conexion->prepare($sqlMascota);
            $stmt->bind_param("i", $mascota_id);
            $stmt->execute();
        }

        $conexion->commit();
        echo "OK";
    }
} catch (Exception $e) {
    $conexion->rollback();
    echo "Error: " . $e->getMessage();
}
?>
