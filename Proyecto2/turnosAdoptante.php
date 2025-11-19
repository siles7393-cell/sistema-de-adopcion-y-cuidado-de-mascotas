<?php
session_start();
require_once "conexion.php";

if (!isset($_SESSION['idUsuario'])) {
    header("Location: loginUsuario.html");
    exit();
}

$idUsuario = $_SESSION['idUsuario'];


$sqlTurnos = "SELECT t.idTurno, t.fecha, t.metodoPago, 
                     m.nombre AS nombreMascota,
                     v.NombreVeterinario, v.ApellidoVeterinario, v.TelefonoVeterinario
              FROM turnos t
              INNER JOIN mascotas m ON t.idMascota = m.mascota_id
              INNER JOIN veterinarios v ON t.idVeterinario = v.ID
              WHERE t.idUsuario = ?";
$stmt = $conexion->prepare($sqlTurnos);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$resultado = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mis Turnos</title>
  <style>
    table {
      width: 90%;
      border-collapse: collapse;
      margin: 20px auto;
    }
    th, td {
      border: 1px solid #333;
      padding: 10px;
      text-align: center;
    }
    th {
      background: #f4f4f4;
    }
    button {
      padding: 5px 8px;
      margin: 2px;
      border-radius: 4px;
      border: none;
      cursor: pointer;
    }
    .editar { background-color: #4CAF50; color: white; }
    .eliminar { background-color: #f44336; color: white; }
  </style>
</head>
<body>
  <h1 style="text-align:center;">Mis Turnos</h1>
  <table>
    <tr>
      <th>Fecha</th>
      <th>Mascota</th>
      <th>Veterinario</th>
      <th>Teléfono</th>
      <th>Método de Pago</th>
      <th>Acciones</th>
    </tr>
    <?php while($fila = $resultado->fetch_assoc()): ?>
      <tr>
        <td><?php echo htmlspecialchars($fila['fecha']); ?></td>
        <td><?php echo htmlspecialchars($fila['nombreMascota']); ?></td>
        <td><?php echo htmlspecialchars($fila['NombreVeterinario'] . " " . $fila['ApellidoVeterinario']); ?></td>
        <td><?php echo htmlspecialchars($fila['TelefonoVeterinario']); ?></td>
        <td><?php echo htmlspecialchars($fila['metodoPago']); ?></td>
        <td>
          <form action="editarTurnosUsuario.php" method="GET" style="display:inline;">
            <input type="hidden" name="idTurno" value="<?php echo $fila['idTurno']; ?>">
            <button type="submit" class="editar">Editar</button>
          </form>
          <form action="eliminarTurnoUsuario.php" method="POST" style="display:inline;">
            <input type="hidden" name="idTurno" value="<?php echo $fila['idTurno']; ?>">
            <button type="submit" class="eliminar" onclick="return confirm('¿Deseas eliminar este turno?')">Eliminar</button>
          </form>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>
  <div style="text-align:center; margin-top:20px;">
    <a href="adoptante.php"><button>atras</button></a>
  </div>
</body>
</html>
