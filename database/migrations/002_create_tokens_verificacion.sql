-- ============================================
-- MIGRACIÓN 023: Crear tabla tokens_verificacion
-- ============================================

CREATE TABLE tokens_verificacion (
    id_token INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    token VARCHAR(64) NOT NULL UNIQUE,
    tipo ENUM('verificacion_email', 'reset_password') NOT NULL,
    email VARCHAR(150) NOT NULL,
    expira_en DATETIME NOT NULL,
    usado TINYINT(1) DEFAULT 0,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_uso DATETIME NULL,
    
    INDEX idx_token (token),
    INDEX idx_usuario (id_usuario),
    INDEX idx_expira (expira_en),
    INDEX idx_tipo (tipo),
    
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Crear índice compuesto para búsquedas eficientes
CREATE INDEX idx_token_activo ON tokens_verificacion (token, usado, expira_en);