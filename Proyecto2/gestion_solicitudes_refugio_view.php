<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['idRefugio'])) {
    header("Location: loginRefugio.html"); 
    exit();
}

$refugio_id = $_SESSION['idRefugio'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Solicitudes - Refugio</title>
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
            max-width: 1200px;
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
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
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
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }
        
        .adopter-info {
            margin: 10px 0;
            font-size: 14px;
            color: #555;
        }
        
        .adopter-info strong {
            color: #333;
        }
        
        .motivacion {
            background: #f9f9f9;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
            font-size: 13px;
            color: #666;
            max-height: 100px;
            overflow-y: auto;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 15px;
            margin-bottom: 0;
        }
        
        .btn-aceptar {
            flex: 1;
            padding: 10px;
            background: #2ecc71;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s;
        }
        
        .btn-aceptar:hover {
            background: #27ae60;
        }
        
        .btn-rechazar {
            flex: 1;
            padding: 10px;
            background: #e74c3c;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s;
        }
        
        .btn-rechazar:hover {
            background: #c0392b;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>📋 Gestión de Solicitudes de Adopción</h1>
            <div class="nav-buttons">
                <a href="refugio_ABM.html">← Volver</a>
            </div>
        </header>
        
        <div class="solicitudes-container" id="solicitudes">
            <?php
            $sql = "SELECT sa.solicitud_id, sa.estado, sa.fecha_solicitud,
                           sa.dni, sa.domicilio, sa.telefono, sa.edad, sa.convivencia, sa.motivacion,
                           u.NombreUsuario, u.ApellidoUsuario,
                           m.nombre AS nombre_mascota
                    FROM solicitudes_adopcion sa
                    INNER JOIN usuarios u ON sa.usuario_id = u.Id
                    INNER JOIN mascotas m ON sa.mascota_id = m.mascota_id
                    WHERE m.refugio_id = ?
                    ORDER BY sa.fecha_solicitud DESC";

            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("i", $refugio_id);
            $stmt->execute();
            $res = $stmt->get_result();

            if ($res->num_rows == 0) {
                echo "<p style='text-align:center; color:#666;'>No hay solicitudes de adopción</p>";
            } else {
                while ($fila = $res->fetch_assoc()) {
                    $estado = htmlspecialchars($fila['estado']);
                    $clase = "solicitud-" . strtolower($estado);
                    echo "<div class='solicitud-card $clase'>";
                    echo "<span class='estado-badge'>" . strtoupper($estado) . "</span>";
                    echo "<div class='mascota-info'>🐾 " . htmlspecialchars($fila['nombre_mascota']) . "</div>";
                    echo "<div class='adopter-info'><strong>👤 Solicitante:</strong> " . htmlspecialchars($fila['NombreUsuario'] . " " . $fila['ApellidoUsuario']) . "</div>";
                    echo "<div class='adopter-info'><strong>📝 DNI:</strong> " . htmlspecialchars($fila['dni']) . "</div>";
                    echo "<div class='adopter-info'><strong>📍 Domicilio:</strong> " . htmlspecialchars($fila['domicilio']) . "</div>";
                    echo "<div class='adopter-info'><strong>📱 Teléfono:</strong> " . htmlspecialchars($fila['telefono']) . "</div>";
                    echo "<div class='adopter-info'><strong>🎂 Edad:</strong> " . htmlspecialchars($fila['edad']) . " años</div>";
                    echo "<div class='adopter-info'><strong>🏠 Convivencia:</strong> " . htmlspecialchars($fila['convivencia']) . "</div>";
                    echo "<div class='adopter-info'><strong>💭 Motivación:</strong></div>";
                    echo "<div class='motivacion'>" . htmlspecialchars($fila['motivacion']) . "</div>";
                    
                    if ($estado === 'pendiente') {
                        echo "<div class='action-buttons'>";
                        echo "<button class='btn-aceptar' onclick='procesarSolicitud(" . $fila['solicitud_id'] . ", \"aceptar\")'>✅ Aceptar</button>";
                        echo "<button class='btn-rechazar' onclick='procesarSolicitud(" . $fila['solicitud_id'] . ", \"rechazar\")'>❌ Rechazar</button>";
                        echo "</div>";
                    }
                    echo "</div>";
                }
            }

            $stmt->close();
            ?>
        </div>
    </div>
    
    <script>
        function procesarSolicitud(solicitud_id, accion) {
            if (!confirm('¿Estás seguro?')) {
                return;
            }
            
            const formData = new FormData();
            formData.append('solicitud_id', solicitud_id);
            formData.append('accion', accion);
            
            fetch('procesar_solicitud_refugio.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if (data === 'OK') {
                    alert('Solicitud procesada correctamente');
                    location.reload();
                } else {
                    alert('Error: ' + data);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al procesar la solicitud');
            });
        }
    </script>
</body>
</html>
