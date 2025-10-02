-- ============================================
-- MIGRACIÓN 019: Crear tabla rutas_entrega
-- ============================================

CREATE TABLE rutas_entrega (
    id_ruta INT AUTO_INCREMENT PRIMARY KEY,
    fecha_ruta DATE NOT NULL,
    id_repartidor INT NOT NULL,
    estado ENUM('pendiente', 'en_curso', 'completada') DEFAULT 'pendiente',
    fecha_inicio DATETIME NULL,
    fecha_fin DATETIME NULL,
    FOREIGN KEY (id_repartidor) REFERENCES usuarios(id_usuario),
    INDEX idx_fecha (fecha_ruta),
    INDEX idx_repartidor (id_repartidor)
) ENGINE=InnoDB;