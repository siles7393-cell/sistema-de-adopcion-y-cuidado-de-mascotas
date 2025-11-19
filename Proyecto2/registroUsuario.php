<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/conexion.php';


$nombre     = $_POST['NombreUsuario'];
$apellido   = $_POST['ApellidoUsuario'];
$fechaNac   = $_POST['FechaNacUsuario'];
$telefono   = $_POST['TelefonoUsuario'];
$username   = $_POST['Username'];
$password   = $_POST['Password'];


$sql = "INSERT INTO usuarios 
        (NombreUsuario, ApellidoUsuario, FechaNacUsuario, TelefonoUsuario, Username, Password) 
        VALUES ('$nombre', '$apellido', '$fechaNac', '$telefono', '$username', '$password')";

$resultado = $conexion->query($sql);

if ($resultado == true) {
    echo "✅ Registro exitoso del veterinario: " . $nombre . " " . $apellido;
} else {
    echo "❌ Error al registrar: ";
}
?>
