-- ============================================
-- MIGRACIÓN 003: Crear tabla direcciones
-- ============================================

CREATE TABLE direcciones (
    id_direccion INT AUTO_INCREMENT PRIMARY KEY,
    id_paciente INT NOT NULL,
    nombre_direccion VARCHAR(100) NOT NULL,
    direccion_completa TEXT NOT NULL,
    referencia VARCHAR(255),
    latitud DECIMAL(10, 8) NULL,
    longitud DECIMAL(11, 8) NULL,
    es_principal BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_paciente) REFERENCES pacientes(id_paciente) ON DELETE CASCADE,
    INDEX idx_paciente (id_paciente)
) ENGINE=InnoDB;