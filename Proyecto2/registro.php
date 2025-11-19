<?php

ini_set('display_errors',1);
error_reporting(E_ALL);

require_once __DIR__ . '/conexion.php';

if($conexion->connect_errno){
    die("Error de conexion: " . $conexion->connect_errno);
}

echo "Listo, conexion OK y archivo incluido(?)";

$usuario = $_POST['nombre'];
$password = $_POST['contraseña'];
$rol = $_POST["rol"];


$sql = "INSERT INTO `usuarios` (`Nombre`, `Contraseña`, `Rol`) VALUES ('$usuario', '$password', '$rol')";
$resultado = $conexion->query($sql);

if($resultado == true){
    echo "Bienvenido, " . $usuario;
    header("Location: login.html");           
}else{
    echo "Usuario o contraseña incorrectos";
}

?>