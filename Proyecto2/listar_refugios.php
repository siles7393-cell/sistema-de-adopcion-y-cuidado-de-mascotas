<?php
require_once __DIR__ . '/conexion.php';
header('Content-Type: application/json');
$res = $conexion->query("SELECT Id, NombreRefugio FROM refugios");
$refugios = [];
while ($r = $res->fetch_assoc()) $refugios[] = $r;
echo json_encode($refugios);