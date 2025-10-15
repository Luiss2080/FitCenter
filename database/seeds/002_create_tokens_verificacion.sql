-- ============================================
-- SEED 002: Datos iniciales para tabla tokens_verificacion - FitCenter
-- ============================================

-- Esta tabla normalmente está vacía ya que los tokens se generan dinámicamente
-- Se incluyen algunos tokens de ejemplo para testing (OPCIONAL)

INSERT IGNORE INTO tokens_verificacion (email, token, tipo, usado, expira_en) VALUES

-- Tokens de verificación de email ya usados (usuarios demo)
('admin@fitcenter.com', 'token_admin_demo_001', 'verificacion_email', TRUE, DATE_ADD(NOW(), INTERVAL 1 HOUR)),
('vendedor@fitcenter.com', 'token_vendedor_demo_001', 'verificacion_email', TRUE, DATE_ADD(NOW(), INTERVAL 1 HOUR)),
('cliente@fitcenter.com', 'token_cliente_demo_001', 'verificacion_email', TRUE, DATE_ADD(NOW(), INTERVAL 1 HOUR)),

-- Token de prueba para reset de contraseña (no usado)
('test@fitcenter.com', 'reset_token_demo_001', 'reset_password', FALSE, DATE_ADD(NOW(), INTERVAL 2 HOURS));

-- Verificar inserción
SELECT id, email, tipo, usado, expira_en FROM tokens_verificacion ORDER BY creado_en DESC;