-- ============================================
-- SEED 004: Datos iniciales para log_actividades - FitCenter
-- ============================================

-- Log de actividades de ejemplo (normalmente se generan automáticamente)
INSERT IGNORE INTO log_actividades 
(usuario_id, email, accion, descripcion, ip_address, tipo_evento, resultado, creado_en) VALUES

-- Registros de los usuarios demo
(1, 'admin@fitcenter.com', 'Registro de cuenta', 'Cuenta de administrador creada', '127.0.0.1', 'registro', 'exitoso', DATE_SUB(NOW(), INTERVAL 30 DAY)),
(1, 'admin@fitcenter.com', 'Verificación de email', 'Email verificado exitosamente', '127.0.0.1', 'email_verification', 'exitoso', DATE_SUB(NOW(), INTERVAL 30 DAY)),

(2, 'vendedor@fitcenter.com', 'Registro de cuenta', 'Cuenta de vendedor creada', '127.0.0.1', 'registro', 'exitoso', DATE_SUB(NOW(), INTERVAL 25 DAY)),
(2, 'vendedor@fitcenter.com', 'Verificación de email', 'Email verificado exitosamente', '127.0.0.1', 'email_verification', 'exitoso', DATE_SUB(NOW(), INTERVAL 25 DAY)),

(4, 'cliente@fitcenter.com', 'Registro de cuenta', 'Cliente registrado desde formulario web', '127.0.0.1', 'registro', 'exitoso', DATE_SUB(NOW(), INTERVAL 15 DAY)),
(4, 'cliente@fitcenter.com', 'Verificación de email', 'Email verificado exitosamente', '127.0.0.1', 'email_verification', 'exitoso', DATE_SUB(NOW(), INTERVAL 15 DAY)),

(5, 'maria@fitcenter.com', 'Registro de cuenta', 'Cliente registrado desde formulario web', '127.0.0.1', 'registro', 'exitoso', DATE_SUB(NOW(), INTERVAL 10 DAY)),
(5, 'maria@fitcenter.com', 'Verificación de email', 'Email verificado exitosamente', '127.0.0.1', 'email_verification', 'exitoso', DATE_SUB(NOW(), INTERVAL 10 DAY)),

-- Algunas actividades de login recientes
(1, 'admin@fitcenter.com', 'Inicio de sesión', 'Login exitoso desde panel admin', '127.0.0.1', 'login', 'exitoso', DATE_SUB(NOW(), INTERVAL 2 DAY)),
(4, 'cliente@fitcenter.com', 'Inicio de sesión', 'Login exitoso desde web', '127.0.0.1', 'login', 'exitoso', DATE_SUB(NOW(), INTERVAL 1 DAY)),

-- Ejemplo de intento de reset de contraseña
(5, 'maria@fitcenter.com', 'Solicitud reset contraseña', 'Token de reset generado y enviado', '127.0.0.1', 'password_reset', 'pendiente', DATE_SUB(NOW(), INTERVAL 5 HOUR));

-- Verificar inserción
SELECT 
    u.nombre,
    u.email,
    l.accion,
    l.tipo_evento,
    l.resultado,
    l.creado_en
FROM log_actividades l
LEFT JOIN usuarios u ON l.usuario_id = u.id
ORDER BY l.creado_en DESC
LIMIT 10;