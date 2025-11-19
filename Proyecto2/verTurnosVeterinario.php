<?php
session_start();
require_once __DIR__ . '/conexion.php';

if (!isset($_SESSION['idVeterinario'])) {
    echo "<p>No has iniciado sesión. <a href='loginVeterinario.html'>Volver al login</a></p>";
    exit();
}

$idVeterinario = $_SESSION['idVeterinario'];

$sql = "SELECT 
            t.idTurno,
            t.fecha, 
            t.metodoPago,
            u.NombreUsuario, 
            u.ApellidoUsuario, 
            m.nombre AS nombreMascota, 
            m.URL_de_foto
        FROM turnos t
        INNER JOIN usuarios u ON t.idUsuario = u.Id
        INNER JOIN mascotas m ON t.idMascota = m.mascota_id
        WHERE t.idVeterinario = ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $idVeterinario);
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
    img {
      max-width: 100px;
      height: auto;
      border-radius: 8px;
    }
    button {
      padding: 5px 10px;
      margin: 2px;
      border-radius: 5px;
      cursor: pointer;
    }
    .editar { background-color: #4CAF50; color: white; }
    .eliminar { background-color: #f44336; color: white; }
  </style>
</head>
<body>
  <h1 style="text-align:center;">Turnos Asignados</h1>
  <table>
    <tr>
      <th>Fecha</th>
      <th>Nombre Usuario</th>
      <th>Apellido Usuario</th>
      <th>Nombre Mascota</th>
      <th>Foto Mascota</th>
      <th>Método de Pago</th>
      <th>Acciones</th>
    </tr>
    <?php while ($fila = $resultado->fetch_assoc()): ?>
      <tr>
        <td><?php echo htmlspecialchars($fila['fecha']); ?></td>
        <td><?php echo htmlspecialchars($fila['NombreUsuario']); ?></td>
        <td><?php echo htmlspecialchars($fila['ApellidoUsuario']); ?></td>
        <td><?php echo htmlspecialchars($fila['nombreMascota']); ?></td>
        <td>
          <?php if (!empty($fila['URL_de_foto'])): ?>
            <img src="<?php echo htmlspecialchars($fila['URL_de_foto']); ?>" alt="Foto de <?php echo htmlspecialchars($fila['nombreMascota']); ?>">
          <?php else: ?>
            Sin foto
          <?php endif; ?>
        </td>
        <td><?php echo htmlspecialchars($fila['metodoPago']); ?></td>
        <td>
          <form action="editarTurno.php" method="GET" style="display:inline;">
            <input type="hidden" name="idTurno" value="<?php echo $fila['idTurno']; ?>">
            <button type="submit" class="editar">Editar</button>
          </form>
          <form action="eliminarTurno.php" method="POST" style="display:inline;">
            <input type="hidden" name="idTurno" value="<?php echo $fila['idTurno']; ?>">
            <button type="submit" class="eliminar" onclick="return confirm('¿Estás seguro de eliminar este turno?')">Eliminar</button>
          </form>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>

  <div style="text-align:center; margin-top:20px;">
    <a href="pantallaPrincipalVeterinario.php"><button>atras</button></a>
  </div>
</body>
</html>
