-- ============================================
-- MIGRACIÓN 004: Crear tabla log_actividades - FitCenter
-- ============================================

CREATE TABLE log_actividades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NULL,
    email VARCHAR(150) NULL,
    accion VARCHAR(100) NOT NULL,
    descripcion TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    datos_adicionales JSON,
    tipo_evento ENUM('registro', 'login', 'logout', 'password_reset', 'email_verification', 'admin_action') NOT NULL,
    resultado ENUM('exitoso', 'fallido', 'pendiente') DEFAULT 'exitoso',
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_usuario_id (usuario_id),
    INDEX idx_email (email),
    INDEX idx_tipo_evento (tipo_evento),
    INDEX idx_resultado (resultado),
    INDEX idx_creado_en (creado_en),
    INDEX idx_accion (accion),
    
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Crear índice compuesto para consultas frecuentes
CREATE INDEX idx_evento_fecha ON log_actividades (tipo_evento, creado_en);
CREATE INDEX idx_usuario_evento ON log_actividades (usuario_id, tipo_evento, creado_en);