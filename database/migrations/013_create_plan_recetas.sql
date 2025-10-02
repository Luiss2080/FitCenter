-- ============================================
-- MIGRACIÓN 013: Crear tabla plan_recetas
-- ============================================

CREATE TABLE plan_recetas (
    id_plan_receta INT AUTO_INCREMENT PRIMARY KEY,
    id_tiempo_comida INT NOT NULL,
    id_receta INT NOT NULL,
    porcion DECIMAL(5, 2) DEFAULT 1,
    FOREIGN KEY (id_tiempo_comida) REFERENCES plan_tiempos_comida(id_tiempo_comida) ON DELETE CASCADE,
    FOREIGN KEY (id_receta) REFERENCES recetas(id_receta) ON DELETE CASCADE,
    INDEX idx_tiempo (id_tiempo_comida),
    INDEX idx_receta (id_receta)
) ENGINE=InnoDB;