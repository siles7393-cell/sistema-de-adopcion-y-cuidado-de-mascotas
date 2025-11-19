<?php
$server = "localhost";
$user = "root";
$password = "";
$BaseDeDatos = "Proyecto";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conexion = new mysqli($server, $user, $password, $BaseDeDatos);
$conexion->set_charset('utf8mb4');

// Configurar zona horaria
date_default_timezone_set('America/Argentina/Buenos_Aires');

