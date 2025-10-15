-- ============================================
-- SEED 002: Datos iniciales para tabla tokens_verificacion - FitCenter
-- ============================================

-- Esta tabla normalmente está vacía ya que los tokens se generan dinámicamente
-- Se incluyen algunos tokens de ejemplo para testing (OPCIONAL)

INSERT IGNORE INTO tokens_verificacion (usuario_id, email, token, tipo, usado, expira_en) VALUES

-- Tokens de verificación de email ya usados (usuarios demo)
(1, 'admin@fitcenter.com', 'token_admin_demo_001', 'verificacion_email', 1, DATE_ADD(NOW(), INTERVAL 1 HOUR)),
(2, 'vendedor@fitcenter.com', 'token_vendedor_demo_001', 'verificacion_email', 1, DATE_ADD(NOW(), INTERVAL 1 HOUR)),
(4, 'cliente@fitcenter.com', 'token_cliente_demo_001', 'verificacion_email', 1, DATE_ADD(NOW(), INTERVAL 1 HOUR)),

-- Token de prueba para reset de contraseña (no usado) - Cliente María López
(5, 'maria@fitcenter.com', 'reset_token_demo_001', 'reset_password', 0, DATE_ADD(NOW(), INTERVAL 2 HOUR));

-- Verificar inserción
SELECT t.id, u.nombre, u.email, t.tipo, t.usado, t.expira_en 
FROM tokens_verificacion t 
JOIN usuarios u ON t.usuario_id = u.id 
ORDER BY t.creado_en DESC;