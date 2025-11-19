<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['idUsuario'])) {
    header("Location: loginUsuario.html"); 
    exit();
}

$usuario_id = $_SESSION['idUsuario'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Solicitudes de Adopción</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
        }
        
        header {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        h1 {
            color: #333;
            margin-bottom: 10px;
        }
        
        .nav-buttons {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        
        a, button {
            padding: 10px 20px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            transition: background 0.3s;
        }
        
        a:hover, button:hover {
            background: #764ba2;
        }
        
        .solicitudes-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .solicitud-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-left: 5px solid #667eea;
        }
        
        .solicitud-card.solicitud-aceptada {
            border-left-color: #2ecc71;
        }
        
        .solicitud-card.solicitud-rechazada {
            border-left-color: #e74c3c;
        }
        
        .solicitud-card.solicitud-pendiente {
            border-left-color: #f39c12;
        }
        
        .estado-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
            background: #f0f0f0;
            color: #333;
        }
        
        .mascota-info {
            margin: 15px 0;
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        
        .refugio-info {
            color: #666;
            font-size: 14px;
            margin: 10px 0;
        }
        
        .fechas {
            font-size: 12px;
            color: #999;
            margin-top: 10px;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>📋 Mis Solicitudes de Adopción</h1>
            <div class="nav-buttons">
                <a href="adoptante_index.html">← Volver</a>
            </div>
        </header>
        
        <div class="solicitudes-container" id="solicitudes">
            <?php
            $sql = "SELECT sa.solicitud_id, sa.estado, sa.fecha_solicitud, sa.fecha_respuesta,
                           m.nombre AS nombre_mascota, r.NombreRefugio AS refugio_nombre
                    FROM solicitudes_adopcion sa
                    INNER JOIN mascotas m ON sa.mascota_id = m.mascota_id
                    INNER JOIN refugios r ON m.refugio_id = r.Id
                    WHERE sa.usuario_id = ?
                    ORDER BY sa.fecha_solicitud DESC";

            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("i", $usuario_id);
            $stmt->execute();
            $res = $stmt->get_result();

            if ($res->num_rows == 0) {
                echo "<p style='text-align:center; color:#666;'>No tienes solicitudes de adopción</p>";
            } else {
                while ($fila = $res->fetch_assoc()) {
                    $estado = htmlspecialchars($fila['estado']);
                    $clase = "solicitud-" . strtolower($estado);
                    echo "<div class='solicitud-card $clase'>";
                    echo "<span class='estado-badge'>" . strtoupper($estado) . "</span>";
                    echo "<div class='mascota-info'>🐾 " . htmlspecialchars($fila['nombre_mascota']) . "</div>";
                    echo "<div class='refugio-info'>🏠 " . htmlspecialchars($fila['refugio_nombre']) . "</div>";
                    echo "<div class='fechas'>";
                    echo "<strong>Fecha solicitud:</strong> " . date('d/m/Y', strtotime($fila['fecha_solicitud'])) . "<br>";
                    if ($fila['fecha_respuesta']) {
                        echo "<strong>Fecha respuesta:</strong> " . date('d/m/Y', strtotime($fila['fecha_respuesta'])) . "<br>";
                    }
                    echo "</div>";
                    echo "</div>";
                }
            }

            $stmt->close();
            ?>
        </div>
    </div>
</body>
</html>
