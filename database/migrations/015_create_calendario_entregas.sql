-- ============================================
-- MIGRACIÓN 015: Crear tabla calendario_entregas
-- ============================================

CREATE TABLE calendario_entregas (
    id_calendario INT AUTO_INCREMENT PRIMARY KEY,
    id_contrato INT NOT NULL,
    fecha_entrega DATE NOT NULL,
    id_direccion INT NOT NULL,
    requiere_entrega BOOLEAN DEFAULT TRUE,
    observaciones VARCHAR(255),
    fecha_modificacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_contrato) REFERENCES contratos_servicio(id_contrato) ON DELETE CASCADE,
    FOREIGN KEY (id_direccion) REFERENCES direcciones(id_direccion),
    INDEX idx_fecha (fecha_entrega),
    INDEX idx_contrato (id_contrato),
    UNIQUE KEY unique_contrato_fecha (id_contrato, fecha_entrega)
) ENGINE=InnoDB;