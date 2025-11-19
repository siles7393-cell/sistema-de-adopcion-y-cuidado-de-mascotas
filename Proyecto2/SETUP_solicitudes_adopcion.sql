
CREATE TABLE IF NOT EXISTS solicitudes_adopcion (
    solicitud_id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    mascota_id INT NOT NULL,
    estado ENUM('pendiente', 'aceptada', 'rechazada') DEFAULT 'pendiente',
    fecha_solicitud TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_respuesta TIMESTAMP NULL,
    dni VARCHAR(20),
    domicilio VARCHAR(255),
    telefono VARCHAR(20),
    edad INT,
    convivencia TEXT,
    motivacion TEXT,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(Id) ON DELETE CASCADE,
    FOREIGN KEY (mascota_id) REFERENCES mascotas(mascota_id) ON DELETE CASCADE,
    INDEX idx_usuario (usuario_id),
    INDEX idx_mascota (mascota_id),
    INDEX idx_estado (estado)
);

ALTER TABLE mascotas ADD COLUMN IF NOT EXISTS estado_adopcion ENUM('disponible', 'en_proceso', 'adoptado') DEFAULT 'disponible';
