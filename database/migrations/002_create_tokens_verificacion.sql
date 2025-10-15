-- ============================================
-- MIGRACIÓN 002: Crear tabla tokens_verificacion - FitCenter
-- ============================================

CREATE TABLE tokens_verificacion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    email VARCHAR(150) NOT NULL,
    token VARCHAR(255) NOT NULL UNIQUE,
    tipo ENUM('verificacion_email', 'reset_password') NOT NULL,
    usado BOOLEAN DEFAULT FALSE,
    expira_en TIMESTAMP NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_token (token),
    INDEX idx_email (email),
    INDEX idx_expira (expira_en),
    INDEX idx_tipo (tipo),
    INDEX idx_usuario_id (usuario_id),
    
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Crear índice compuesto para búsquedas eficientes
CREATE INDEX idx_token_activo ON tokens_verificacion (token, usado, expira_en);