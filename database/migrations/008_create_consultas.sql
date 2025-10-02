-- ============================================
-- MIGRACIÓN 008: Crear tabla consultas
-- ============================================

CREATE TABLE consultas (
    id_consulta INT AUTO_INCREMENT PRIMARY KEY,
    id_contrato INT NOT NULL,
    tipo_consulta ENUM('inicial', 'evaluacion') NOT NULL,
    fecha_consulta DATE NOT NULL,
    peso DECIMAL(5, 2) NOT NULL,
    altura DECIMAL(5, 2) NOT NULL,
    imc DECIMAL(5, 2) GENERATED ALWAYS AS (peso / ((altura / 100) * (altura / 100))) STORED,
    grasa_corporal DECIMAL(5, 2),
    masa_muscular DECIMAL(5, 2),
    observaciones TEXT,
    diagnostico TEXT,
    habitos_alimenticios TEXT,
    antecedentes_clinicos TEXT,
    FOREIGN KEY (id_contrato) REFERENCES contratos_servicio(id_contrato) ON DELETE CASCADE,
    INDEX idx_contrato (id_contrato),
    INDEX idx_fecha (fecha_consulta)
) ENGINE=InnoDB;