-- ============================================
-- MIGRACIÓN 009: Crear tabla analisis_clinicos
-- ============================================

CREATE TABLE analisis_clinicos (
    id_analisis INT AUTO_INCREMENT PRIMARY KEY,
    id_consulta INT NOT NULL,
    tipo_analisis VARCHAR(150) NOT NULL,
    fecha_solicitud DATE NOT NULL,
    fecha_resultado DATE NULL,
    resultado TEXT,
    archivo_adjunto VARCHAR(255) NULL,
    FOREIGN KEY (id_consulta) REFERENCES consultas(id_consulta) ON DELETE CASCADE,
    INDEX idx_consulta (id_consulta)
) ENGINE=InnoDB;