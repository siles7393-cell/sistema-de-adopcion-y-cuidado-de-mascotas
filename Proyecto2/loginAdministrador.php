<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/conexion.php';

if ($conexion->connect_errno) {
  die("Error de conexión: " . $conexion->connect_error);
}

echo "Listo, conexión OK y archivo incluido.";

$usuario = $_POST['usuario'];
$password = $_POST['password'];

$sql = "SELECT * FROM administradores WHERE AdminUsername='$usuario' AND AdminPassword='$password'";
$resultado = $conexion->query($sql);

if ($resultado->num_rows > 0) {
    echo "✅ Bienvenido, " . $usuario;
    echo "<a href='administrador.html'>Siguiente</a>";
} else {
    echo "❌ Usuario o contraseña incorrectos";
      echo "<a href='loginAdministrador.html'>Atras</a>";
}

?>
