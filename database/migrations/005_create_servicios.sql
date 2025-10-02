-- ============================================
-- MIGRACIÓN 005: Crear tabla servicios
-- ============================================

CREATE TABLE servicios (
    id_servicio INT AUTO_INCREMENT PRIMARY KEY,
    nombre_servicio VARCHAR(100) NOT NULL,
    descripcion TEXT,
    tipo_servicio ENUM('asesoramiento', 'catering') NOT NULL,
    duracion_dias INT NOT NULL,
    costo DECIMAL(10, 2) NOT NULL,
    incluye_evaluaciones BOOLEAN DEFAULT TRUE,
    estado ENUM('activo', 'inactivo') DEFAULT 'activo',
    INDEX idx_tipo (tipo_servicio)
) ENGINE=InnoDB;

-- Insertar servicios base
INSERT INTO servicios (nombre_servicio, descripcion, tipo_servicio, duracion_dias, costo, incluye_evaluaciones) VALUES
('Asesoramiento Nutricional Básico', 'Plan alimentario de 15 días con evaluación de control', 'asesoramiento', 15, 150.00, TRUE),
('Catering 15 días', 'Servicio de catering con alimentación diaria por 15 días', 'catering', 15, 450.00, TRUE),
('Catering 30 días', 'Servicio de catering con alimentación diaria por 30 días', 'catering', 30, 850.00, TRUE);