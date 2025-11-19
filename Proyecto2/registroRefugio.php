<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/conexion.php';


$nombre    = $_POST['NombreRefugio'];
$ubicacion = $_POST['UbicacionRefugio'];
$telefono  = $_POST['TelefonoRefugio'];
$username  = $_POST['UsernameRefugio'];
$password  = $_POST['PasswordRefugio'];


$sql = "INSERT INTO refugios 
        (NombreRefugio, UbicacionRefugio, TelefonoRefugio, UsernameRefugio, PasswordRefugio) 
        VALUES ('$nombre', '$ubicacion', '$telefono', '$username', '$password')";

$resultado = $conexion->query($sql);

if ($resultado == true) {
    echo "✅ Registro exitoso del Refugio: " . $nombre;
} else {
    echo "❌ Error al registrar: ";
}
?>
