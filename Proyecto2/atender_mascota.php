<?php

session_start();
require 'conexion.php';

if (!isset($_SESSION['idVeterinario'])) {
    echo "<p>No has iniciado sesión. <a href='loginVeterinario.html'>Volver al login</a></p>";
    exit();
}

$idVeterinario = $_SESSION['idVeterinario'];


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mascota_id'], $_POST['accion'])) {
    $mascota_id = $_POST['mascota_id'];
    $accion = $_POST['accion'];
    // Registrar la entrada con la fecha/hora actual en el servidor (NOW())
    $stmt = $conexion->prepare("INSERT INTO historial_clinico (mascota_id, accion, ID_veterinario, fecha) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("isi", $mascota_id, $accion, $idVeterinario);
    $stmt->execute();
    $stmt->close();
    $msg = "Acción registrada en el historial clínico.";
}

$sql = "SELECT 
    t.idTurno, 
    t.fecha, 
    m.mascota_id, 
    m.nombre AS nombre_mascota, 
    m.edad, 
    m.peso, 
    m.raza, 
    m.descripcion, 
    m.tipo, 
    m.genero, 
    m.titulo, 
    m.URL_de_foto, 
    m.estado AS estado_mascota, 
    m.refugio_id
FROM 
    turnos t
JOIN 
    mascotas m ON t.idMascota = m.mascota_id
WHERE 
    t.idVeterinario = ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $idVeterinario);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Atender Mascotas</title>
    <style>
        table { border-collapse: collapse; width: 80%; margin: 20px auto; }
        th, td { border: 1px solid #333; padding: 8px; text-align: center; }
        th { background: #f4f4f4; }
        form { display: inline; }
        .msg { color: green; text-align: center; }
    </style>
</head>
<body>
    <h2 style="text-align:center;">Mascotas con Turno Asignado</h2>
    <?php if (isset($msg)) echo "<p class='msg'>$msg</p>"; ?>
    <table>
        <tr>
            <th>Fecha Turno</th>
            <th>Nombre Mascota</th>
            <th>Registrar Acción Clínica</th>

        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['fecha']); ?></td>
            <td><?php echo htmlspecialchars($row['nombre_mascota']); ?></td>
            <td>
                <form method="post">
                    <input type="hidden" name="mascota_id" value="<?php echo $row['mascota_id']; ?>">
                    <input type="text" name="accion" required placeholder="Acción realizada">
                    <button type="submit">Registrar</button>
                </form>
            </td>
            <td>
                <a href="historial_mascota.php?mascota_id=<?php echo $row['mascota_id']; ?>">Ver historial</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <div style="text-align:center;">
        <a href="pantallaPrincipalVeterinario.php">Volver al panel</a>
    </div>
</body>
</html>
<?php
$stmt->close();
$conexion->close();
?>