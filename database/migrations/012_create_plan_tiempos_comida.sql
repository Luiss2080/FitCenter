-- ============================================
-- MIGRACIÓN 012: Crear tabla plan_tiempos_comida
-- ============================================

CREATE TABLE plan_tiempos_comida (
    id_tiempo_comida INT AUTO_INCREMENT PRIMARY KEY,
    id_plan INT NOT NULL,
    nombre_tiempo VARCHAR(50) NOT NULL,
    hora_sugerida TIME,
    orden INT NOT NULL,
    FOREIGN KEY (id_plan) REFERENCES planes_alimentarios(id_plan) ON DELETE CASCADE,
    INDEX idx_plan (id_plan)
) ENGINE=InnoDB;