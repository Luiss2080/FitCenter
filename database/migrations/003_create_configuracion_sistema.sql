-- ============================================
-- MIGRACIÓN 003: Crear tabla configuracion_sistema - FitCenter
-- ============================================

CREATE TABLE configuracion_sistema (
    id INT AUTO_INCREMENT PRIMARY KEY,
    clave VARCHAR(100) NOT NULL UNIQUE,
    valor TEXT NOT NULL,
    descripcion VARCHAR(255),
    categoria ENUM('email', 'general', 'seguridad', 'notificaciones') DEFAULT 'general',
    activo BOOLEAN DEFAULT TRUE,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_clave (clave),
    INDEX idx_categoria (categoria),
    INDEX idx_activo (activo)
) ENGINE=InnoDB;

-- Insertar configuraciones iniciales para el sistema de email
INSERT INTO configuracion_sistema (clave, valor, descripcion, categoria) VALUES
('email_smtp_host', 'smtp.gmail.com', 'Servidor SMTP para envío de correos', 'email'),
('email_smtp_port', '587', 'Puerto SMTP para envío de correos', 'email'),
('email_smtp_secure', 'tls', 'Tipo de seguridad SMTP (tls/ssl)', 'email'),
('email_from_address', 'noreply@fitcenter.com', 'Dirección de email remitente', 'email'),
('email_from_name', 'FitCenter - Sistema de Gestión', 'Nombre del remitente de emails', 'email'),
('reset_password_expiry_hours', '2', 'Horas de expiración para tokens de reset de contraseña', 'seguridad'),
('email_verification_expiry_hours', '24', 'Horas de expiración para tokens de verificación de email', 'seguridad'),
('sistema_nombre', 'FitCenter', 'Nombre del sistema', 'general'),
('sistema_url_base', 'http://localhost/FitCenter', 'URL base del sistema', 'general');

-- Verificar inserción
SELECT clave, valor, categoria, descripcion FROM configuracion_sistema ORDER BY categoria, clave;