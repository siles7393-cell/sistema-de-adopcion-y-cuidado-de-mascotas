<?php

session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/conexion.php';

if ($conexion->connect_errno) {
  die("Error de conexión: " . $conexion->connect_error);
}

echo "Listo, conexión OK y archivo incluido.";

$usuario = $_POST['usuario'];
$password = $_POST['password'];

$sql = "SELECT * FROM veterinarios WHERE Usuario='$usuario' AND Contrasenia='$password'";
$resultado = $conexion->query($sql);

if ($resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc(); 
  
    $_SESSION['idVeterinario'] = $fila['ID']; 
    $_SESSION['nombreVeterinario'] = $fila['NombreVeterinario'];
    $_SESSION['usuario'] = $fila['Usuario'];

    echo "✅ Bienvenido $usuario <br>";
    echo "<a href='pantallaPrincipalVeterinario.php'>Ir al Panel</a>";
} else {
    echo "❌ Usuario o contraseña incorrectos <br>";
    echo "<a href='loginVeterinario.html'>Volver al login</a>";
}
?>
