-- ============================================
-- MIGRACIÓN 011: Crear tabla planes_alimentarios
-- ============================================

CREATE TABLE planes_alimentarios (
    id_plan INT AUTO_INCREMENT PRIMARY KEY,
    nombre_plan VARCHAR(150) NOT NULL,
    descripcion TEXT,
    objetivo VARCHAR(255),
    tiempos_comida_dia INT NOT NULL,
    estado ENUM('activo', 'inactivo') DEFAULT 'activo',
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_nombre (nombre_plan)
) ENGINE=InnoDB;