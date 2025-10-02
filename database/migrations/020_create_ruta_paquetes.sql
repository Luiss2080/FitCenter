-- ============================================
-- MIGRACIÓN 020: Crear tabla ruta_paquetes
-- ============================================

CREATE TABLE ruta_paquetes (
    id_ruta_paquete INT AUTO_INCREMENT PRIMARY KEY,
    id_ruta INT NOT NULL,
    id_paquete INT NOT NULL,
    orden_entrega INT NOT NULL,
    FOREIGN KEY (id_ruta) REFERENCES rutas_entrega(id_ruta) ON DELETE CASCADE,
    FOREIGN KEY (id_paquete) REFERENCES paquetes_entrega(id_paquete) ON DELETE CASCADE,
    INDEX idx_ruta (id_ruta),
    UNIQUE KEY unique_ruta_paquete (id_ruta, id_paquete)
) ENGINE=InnoDB;