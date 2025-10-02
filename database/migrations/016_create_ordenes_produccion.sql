-- ============================================
-- MIGRACIÓN 016: Crear tabla ordenes_produccion
-- ============================================

CREATE TABLE ordenes_produccion (
    id_orden INT AUTO_INCREMENT PRIMARY KEY,
    fecha_produccion DATE NOT NULL,
    fecha_entrega DATE NOT NULL,
    estado ENUM('pendiente', 'en_proceso', 'completada') DEFAULT 'pendiente',
    id_usuario_asignado INT NULL,
    fecha_inicio DATETIME NULL,
    fecha_completado DATETIME NULL,
    FOREIGN KEY (id_usuario_asignado) REFERENCES usuarios(id_usuario),
    INDEX idx_fecha_entrega (fecha_entrega),
    INDEX idx_estado (estado),
    UNIQUE KEY unique_fecha_entrega (fecha_entrega)
) ENGINE=InnoDB;