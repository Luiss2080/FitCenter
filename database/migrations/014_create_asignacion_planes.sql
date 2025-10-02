-- ============================================
-- MIGRACIÓN 014: Crear tabla asignacion_planes
-- ============================================

CREATE TABLE asignacion_planes (
    id_asignacion INT AUTO_INCREMENT PRIMARY KEY,
    id_contrato INT NOT NULL,
    id_plan INT NOT NULL,
    fecha_asignacion DATE NOT NULL,
    observaciones TEXT,
    FOREIGN KEY (id_contrato) REFERENCES contratos_servicio(id_contrato) ON DELETE CASCADE,
    FOREIGN KEY (id_plan) REFERENCES planes_alimentarios(id_plan),
    INDEX idx_contrato (id_contrato)
) ENGINE=InnoDB;