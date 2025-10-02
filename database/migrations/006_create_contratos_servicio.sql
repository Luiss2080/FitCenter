-- ============================================
-- MIGRACIÓN 006: Crear tabla contratos_servicio
-- ============================================

CREATE TABLE contratos_servicio (
    id_contrato INT AUTO_INCREMENT PRIMARY KEY,
    id_paciente INT NOT NULL,
    id_servicio INT NOT NULL,
    id_nutricionista INT NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    estado ENUM('activo', 'completado', 'cancelado') DEFAULT 'activo',
    monto_total DECIMAL(10, 2) NOT NULL,
    fecha_contratacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_paciente) REFERENCES pacientes(id_paciente) ON DELETE CASCADE,
    FOREIGN KEY (id_servicio) REFERENCES servicios(id_servicio),
    FOREIGN KEY (id_nutricionista) REFERENCES nutricionistas(id_nutricionista),
    INDEX idx_paciente (id_paciente),
    INDEX idx_estado (estado)
) ENGINE=InnoDB;