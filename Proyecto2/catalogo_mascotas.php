<?php
require_once __DIR__ . '/conexion.php';

// Mostrar mascotas que no estén adoptadas (ocultar definitivamente adoptadas)
$where = "estado_adopcion != 'adoptado'";
$params = [];
if (!empty($_GET['titulo'])) {
    $where .= " AND titulo LIKE ?";
    $params[] = "%" . $_GET['titulo'] . "%";
}
if (!empty($_GET['raza'])) {
    $where .= " AND raza LIKE ?";
    $params[] = "%" . $_GET['raza'] . "%";
}
if (!empty($_GET['edad'])) {
    $where .= " AND edad = ?";
    $params[] = $_GET['edad'];
}
if (!empty($_GET['peso'])) {
    $where .= " AND peso LIKE ?";
    $params[] = "%" . $_GET['peso'] . "%";
}
if (!empty($_GET['genero'])) {
    $where .= " AND genero = ?";
    $params[] = $_GET['genero'];
}
if (!empty($_GET['tipo'])) {
    $where .= " AND tipo = ?";
    $params[] = $_GET['tipo'];
}

// Seleccionar también el estado de adopción para mostrar disponibilidad
$sql = "SELECT mascota_id, URL_de_foto, titulo, nombre, estado_adopcion FROM mascotas WHERE $where";
$stmt = $conexion->prepare($sql);
if ($params) {
    $types = str_repeat('s', count($params));
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$res = $stmt->get_result();

while ($fila = $res->fetch_assoc()) {
    $estadoAdop = $fila['estado_adopcion'];
    $badge = '';
    $disabled = '';
    $adoptLabel = 'Adoptar';
    if ($estadoAdop === 'disponible') {
        $badge = "<span style='color: #fff; background:#2ecc71; padding:4px 8px; border-radius:4px; font-size:12px;'>Disponible</span>";
    } elseif ($estadoAdop === 'en_proceso') {
        $badge = "<span style='color:#fff; background:#f39c12; padding:4px 8px; border-radius:4px; font-size:12px;'>En proceso</span>";
        $disabled = 'disabled';
        $adoptLabel = 'En proceso';
    } else {
        $badge = "<span style='color:#fff; background:#e74c3c; padding:4px 8px; border-radius:4px; font-size:12px;'>Adoptado</span>";
        $disabled = 'disabled';
        $adoptLabel = 'Adoptado';
    }

    echo "<div class='card'>";
    echo "<img src='" . htmlspecialchars($fila['URL_de_foto']) . "' alt='foto'>";
    echo "<h4>" . htmlspecialchars($fila['titulo']) . " " . $badge . "</h4>";
    echo "<p><b>Nombre:</b> " . htmlspecialchars($fila['nombre']) . "</p>";
    echo "<button class='btn-detalles' data-id='" . $fila['mascota_id'] . "'>Ver detalles</button> ";
    echo "<button class='btn-adoptar' data-id='" . $fila['mascota_id'] . "' " . $disabled . ">" . $adoptLabel . "</button>";
    echo "</div>";
}
if ($res->num_rows == 0) {
    echo "<p>No hay mascotas disponibles con esos filtros.</p>";
}
$stmt->close();