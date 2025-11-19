<?php
session_start();
require_once __DIR__ . '/conexion.php';

if (!isset($_SESSION['idUsuario'])) {
    echo "<p>No has iniciado sesión. <a href='loginUsuario.html'>Volver al login</a></p>";
    exit();
}

$idUsuario = $_SESSION['idUsuario'];
$idMascota = $_POST['idMascota'];
$fecha = $_POST['fecha'];
$idVeterinario = $_POST['idVeterinario'];
$metodoPago = $_POST['metodoPago'];

// Validar que la fecha no sea hoy ni en el pasado
try {
    $fechaSeleccionada = new DateTime($fecha);
    $mañana = new DateTime();
    $mañana->modify('+1 day');
    $mañana->setTime(0, 0, 0);
    $fechaSeleccionada->setTime(0, 0, 0);

    if ($fechaSeleccionada < $mañana) {
        echo "<p>❌ Error: Debes seleccionar una fecha a partir de mañana.</p>";
        echo "<a href='pedirTurno.php'><button>Volver</button></a>";
        exit();
    }
} catch (Exception $e) {
    echo "<p>❌ Error: Formato de fecha inválido.</p>";
    echo "<a href='pedirTurno.php'><button>Volver</button></a>";
    exit();
}

if ($metodoPago === 'puntos') {
    $sqlPuntos = "SELECT puntos FROM usuarios WHERE Id = ?";
    $stmtPuntos = $conexion->prepare($sqlPuntos);
    $stmtPuntos->bind_param("i", $idUsuario);
    $stmtPuntos->execute();
    $resultadoPuntos = $stmtPuntos->get_result();
    $puntosUsuario = $resultadoPuntos->fetch_assoc()['puntos'];

    if ($puntosUsuario < 50) {
        echo "<p>No tienes suficientes puntos para pagar con puntos.</p>";
        echo "<a href='pedirTurno.php'><button>Volver</button></a>";
        exit();
    }
}

$sql = "INSERT INTO turnos (fecha, idUsuario, idMascota, idVeterinario, estado, metodoPago)
        VALUES (?, ?, ?, ?, 'pendiente', ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("siiis", $fecha, $idUsuario, $idMascota, $idVeterinario, $metodoPago);

if ($stmt->execute()) {

    if ($metodoPago === 'puntos') {
        $sqlDescuento = "UPDATE usuarios SET puntos = puntos - 50 WHERE Id = ?";
        $stmtDescuento = $conexion->prepare($sqlDescuento);
        $stmtDescuento->bind_param("i", $idUsuario);
        $stmtDescuento->execute();
    }

    echo "<p>Turno guardado con éxito.</p>";
    echo "<a href='adoptante.php'><button>Volver al Panel</button></a>";
} else {
    echo "<p>Error al guardar el turno: " . $conexion->error . "</p>";
}
