-- ============================================
-- MIGRACIÓN 021: Crear tabla entregas_realizadas
-- ============================================

CREATE TABLE entregas_realizadas (
    id_entrega INT AUTO_INCREMENT PRIMARY KEY,
    id_paquete INT NOT NULL,
    fecha_hora_entrega DATETIME NOT NULL,
    latitud_entrega DECIMAL(10, 8) NULL,
    longitud_entrega DECIMAL(11, 8) NULL,
    estado_entrega ENUM('entregado', 'no_recibido', 'reprogramado') NOT NULL,
    receptor VARCHAR(100),
    observaciones TEXT,
    firma_digital VARCHAR(255) NULL,
    foto_evidencia VARCHAR(255) NULL,
    codigo_confirmacion VARCHAR(50) NULL,
    FOREIGN KEY (id_paquete) REFERENCES paquetes_entrega(id_paquete) ON DELETE CASCADE,
    INDEX idx_paquete (id_paquete),
    INDEX idx_fecha (fecha_hora_entrega)
) ENGINE=InnoDB;