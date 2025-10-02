-- ============================================
-- MIGRACIÓN 002: Crear tabla pacientes
-- ============================================

CREATE TABLE pacientes (
    id_paciente INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    documento_identidad VARCHAR(50) UNIQUE NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    genero ENUM('masculino', 'femenino', 'otro') NOT NULL,
    ocupacion VARCHAR(100),
    contacto_emergencia VARCHAR(200),
    telefono_emergencia VARCHAR(20),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    INDEX idx_documento (documento_identidad)
) ENGINE=InnoDB;