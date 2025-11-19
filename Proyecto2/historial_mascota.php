<?php
session_start();
require_once __DIR__ . '/conexion.php';

if (!isset($_SESSION['idVeterinario'])) {
    echo "<p>No has iniciado sesión. <a href='loginVeterinario.html'>Volver al login</a></p>";
    exit();
}

$mascota_id = intval($_GET['mascota_id'] ?? 0);
if ($mascota_id <= 0) {
    echo "<p>Mascota inválida.</p>";
    exit();
}

$sql = "SELECT h.accion, h.fecha, h.ID_veterinario, v.NombreVeterinario, v.ApellidoVeterinario
        FROM historial_clinico h
        LEFT JOIN veterinarios v ON h.ID_veterinario = v.ID
        WHERE h.mascota_id = ?
        ORDER BY h.fecha DESC";

$stmt = $conexion->prepare($sql);
$stmt->bind_param('i', $mascota_id);
$stmt->execute();
$res = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Historial Clínico - Mascota</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; max-width: 900px; margin: 30px auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #f4f4f4; }
        .empty { color: #666; text-align: center; padding: 20px; }
        .back { margin-top: 20px; }
    </style>
</head>
<body>
    <h2>Historial Clínico de la mascota (ID: <?php echo $mascota_id; ?>)</h2>

    <?php if ($res->num_rows === 0): ?>
        <div class="empty">No hay entradas en el historial clínico para esta mascota.</div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Acción</th>
                    <th>Veterinario</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $res->fetch_assoc()): ?>
                <tr>
                    <td><?php echo date('d/m/Y', strtotime($row['fecha'])); ?></td>
                    <td><?php echo nl2br(htmlspecialchars($row['accion'])); ?></td>
                    <td><?php echo htmlspecialchars(($row['NombreVeterinario'] ?? '') . ' ' . ($row['ApellidoVeterinario'] ?? '')); ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div class="back">
        <a href="atender_mascota.php">← Volver a Turnos</a>
    </div>
</body>
</html>

<?php
$stmt->close();
$conexion->close();
?>
