<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/conexion.php';


$usuario            = $_POST['Usuario'];
$password           = $_POST['Password'];
$nombre             = $_POST['NombreVeterinario'];
$apellido           = $_POST['ApellidoVeterinario'];
$fechaNac           = $_POST['FechaNacVeterinario'];
$numeroMatricula    = $_POST['NumeroMatricula'];
$telefono           = $_POST['TelefonoVeterinario'];


$sql = "INSERT INTO veterinarios 
        (NombreVeterinario, ApellidoVeterinario, FechaNacVeterinario, NumeroMatricula, TelefonoVeterinario, Usuario, contrasenia) 
        VALUES ('$nombre', '$apellido', '$fechaNac', '$numeroMatricula', '$telefono', '$usuario', '$password')";

$resultado = $conexion->query($sql);

if ($resultado == true) {
    echo "✅ Registro exitoso del veterinario: " . $nombre . " " . $apellido;
} else {
    echo "❌ Error al registrar: ";
}

?>
