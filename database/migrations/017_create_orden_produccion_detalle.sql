-- ============================================
-- MIGRACIÓN 017: Crear tabla orden_produccion_detalle
-- ============================================

CREATE TABLE orden_produccion_detalle (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_orden INT NOT NULL,
    id_receta INT NOT NULL,
    cantidad_porciones INT NOT NULL,
    estado ENUM('pendiente', 'preparando', 'completado') DEFAULT 'pendiente',
    FOREIGN KEY (id_orden) REFERENCES ordenes_produccion(id_orden) ON DELETE CASCADE,
    FOREIGN KEY (id_receta) REFERENCES recetas(id_receta),
    INDEX idx_orden (id_orden)
) ENGINE=InnoDB;