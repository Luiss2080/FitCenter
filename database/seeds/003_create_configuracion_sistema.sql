-- ============================================
-- SEED 003: Datos adicionales para configuracion_sistema - FitCenter
-- ============================================

-- Configuraciones adicionales del sistema
INSERT IGNORE INTO configuracion_sistema (clave, valor, descripcion, categoria) VALUES

-- Configuraciones de email adicionales
('email_templates_path', 'view/emails/', 'Ruta de las plantillas de email', 'email'),
('email_enable_debug', 'false', 'Habilitar debug para emails', 'email'),
('email_queue_enabled', 'false', 'Habilitar cola de emails', 'email'),

-- Configuraciones de seguridad
('max_login_attempts', '5', 'Máximo número de intentos de login', 'seguridad'),
('account_lockout_duration_minutes', '30', 'Duración del bloqueo de cuenta en minutos', 'seguridad'),
('password_min_length', '8', 'Longitud mínima de contraseña', 'seguridad'),
('session_timeout_minutes', '120', 'Tiempo de expiración de sesión en minutos', 'seguridad'),

-- Configuraciones de notificaciones
('notif_welcome_email', 'true', 'Enviar email de bienvenida al registrarse', 'notificaciones'),
('notif_password_reset', 'true', 'Enviar notificación de cambio de contraseña', 'notificaciones'),
('notif_login_alert', 'false', 'Enviar alerta de nuevo login', 'notificaciones'),

-- Configuraciones generales del gimnasio
('gimnasio_nombre', 'FitCenter Gym', 'Nombre completo del gimnasio', 'general'),
('gimnasio_direccion', 'Av. Fitness #123, Colonia Salud', 'Dirección del gimnasio', 'general'),
('gimnasio_telefono', '+52 55 1234 5678', 'Teléfono de contacto', 'general'),
('gimnasio_horarios', 'Lunes a Viernes: 6:00 AM - 11:00 PM, Sábados y Domingos: 7:00 AM - 9:00 PM', 'Horarios de atención', 'general');

-- Verificar inserción
SELECT COUNT(*) as total_configuraciones FROM configuracion_sistema;
SELECT categoria, COUNT(*) as cantidad FROM configuracion_sistema GROUP BY categoria ORDER BY categoria;