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

$sql = "SELECT * FROM usuarios WHERE Username='$usuario' AND Password='$password'";
$resultado = $conexion->query($sql);

if ($resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc(); 
    
  
    $_SESSION['idUsuario'] = $fila['Id']; 
    echo "✅ Bienvenido $usuario <br>";
    echo "<a href='adoptante_index.html'>Ir al Panel</a>";
} else {
    echo "❌ Usuario o contraseña incorrectos <br>";
    echo "<a href='loginUsuario.html'>Volver al login</a>";
}
?>
