<?php
require_once __DIR__ . '/conexion.php';
session_start();

$usuario_id = intval($_SESSION['idRefugio'] ?? 0);

if ($usuario_id <= 0) {
    echo "No tienes permiso para cargar mascotas.";
    exit;
}

$nombre = trim($_POST['nombre'] ?? '');
$edad = intval($_POST['edad'] ?? 0);
$descripcion = trim($_POST['descripcion'] ?? '');
$raza = trim($_POST['raza'] ?? '');
$peso = trim($_POST['peso'] ?? '');
$genero = trim($_POST['genero'] ?? '');
$tipo = trim($_POST['tipo'] ?? '');
$titulo = trim($_POST['titulo'] ?? '');

if (empty($nombre) || empty($raza) || empty($tipo) || empty($genero)) {
    echo "Error: Los campos nombre, raza, tipo y género son obligatorios.";
    exit;
}

$foto_url = '';
if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0 && $_FILES['foto']['size'] > 0) {
    $destino = 'fotos/' . uniqid() . '_' . basename($_FILES['foto']['name']);
    if (move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
        $foto_url = $destino;
    }
}

$sql = "INSERT INTO mascotas (URL_de_foto, nombre, edad, descripcion, raza, peso, genero, tipo, titulo, refugio_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ssissssssi", $foto_url, $nombre, $edad, $descripcion, $raza, $peso, $genero, $tipo, $titulo, $usuario_id);

if ($stmt->execute()) {
    echo "Mascota cargada correctamente.";
    echo "<a href='refugio_ABM.html'><button>Confirmar</button></a>";
} else {
    echo "Error al cargar mascota: " . $stmt->error;
}
$stmt->close();
