<?php
require_once __DIR__ . '/conexion.php';
session_start();

$id = intval($_POST['id'] ?? 0);
$usuario_id = intval($_SESSION['idRefugio'] ?? 0);

if ($usuario_id <= 0 || $id <= 0) {
    echo "Error: No tienes permiso para modificar.";
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

if ($foto_url) {
    $sql = "UPDATE mascotas SET URL_de_foto=?, titulo=?, nombre=?, edad=?, descripcion=?, raza=?, peso=?, genero=?, tipo=? WHERE mascota_id=? AND refugio_id=?";
    $stmt = $conexion->prepare($sql);
    if (!$stmt) {
        echo "Error en la preparación: " . $conexion->error;
        exit;
    }
    $stmt->bind_param("sssisssssii", $foto_url, $titulo, $nombre, $edad, $descripcion, $raza, $peso, $genero, $tipo, $id, $usuario_id);
} else {
    $sql = "UPDATE mascotas SET titulo=?, nombre=?, edad=?, descripcion=?, raza=?, peso=?, genero=?, tipo=? WHERE mascota_id=? AND refugio_id=?";
    $stmt = $conexion->prepare($sql);
    if (!$stmt) {
        echo "Error en la preparación: " . $conexion->error;
        exit;
    }
    $stmt->bind_param("ssisssssii", $titulo, $nombre, $edad, $descripcion, $raza, $peso, $genero, $tipo, $id, $usuario_id);
}

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo "Mascota modificada correctamente.";
    } else {
        echo "No se realizaron cambios. Verifica que los datos sean diferentes o que la mascota exista.";
    }
} else {
    echo "Error al modificar: " . $stmt->error;
}
$stmt->close();
