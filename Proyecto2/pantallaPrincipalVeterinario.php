<?php
session_start();

// Verificar si hay sesión activa
if (!isset($_SESSION['idVeterinario'])) {
    echo "<p>No has iniciado sesión. <a href='loginVeterinario.html'>Volver al login</a></p>";
    exit();
}

// Guardar variable
$nombreVeterinario = $_SESSION['nombreVeterinario'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel Veterinario</title>
</head>
<body>
  <h1>Bienvenido, <?php echo htmlspecialchars($nombreVeterinario); ?></h1>

 
  <a href="cerrarSesion.php"><button>Cerrar sesión</button></a>
  <a href="atender_mascota.php"><button>atender mascotas</button></a>


  <a href="verTurnosVeterinario.php"><button>Ver mis turnos</button></a>
</body>
</html>
