-- ============================================
-- MIGRACIÓN 018: Crear tabla paquetes_entrega
-- ============================================

CREATE TABLE paquetes_entrega (
    id_paquete INT AUTO_INCREMENT PRIMARY KEY,
    id_orden INT NOT NULL,
    id_calendario INT NOT NULL,
    numero_paquete VARCHAR(50) UNIQUE NOT NULL,
    fecha_empaquetado DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('empaquetado', 'asignado', 'en_ruta', 'entregado', 'no_entregado') DEFAULT 'empaquetado',
    FOREIGN KEY (id_orden) REFERENCES ordenes_produccion(id_orden) ON DELETE CASCADE,
    FOREIGN KEY (id_calendario) REFERENCES calendario_entregas(id_calendario) ON DELETE CASCADE,
    INDEX idx_orden (id_orden),
    INDEX idx_numero (numero_paquete),
    INDEX idx_estado (estado)
) ENGINE=InnoDB;