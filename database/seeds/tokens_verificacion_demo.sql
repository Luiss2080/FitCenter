-- ============================================
-- SEED DE TOKENS DE VERIFICACIÓN - CARECENTER
-- ============================================
-- Este seed normalmente está vacío porque los tokens se generan dinámicamente
-- Solo se incluye para completar la estructura del proyecto

-- Insertar algunos tokens de ejemplo para testing (OPCIONAL)
-- INSERT INTO tokens_verificacion (id_usuario, token, tipo, email, expira_en) VALUES
-- (1, 'ejemplo_token_123456789abcdef', 'verificacion_email', 'admin@carecenter.com', DATE_ADD(NOW(), INTERVAL 24 HOUR));

-- Verificar estructura de la tabla
DESCRIBE tokens_verificacion;

-- ============================================
-- INFORMACIÓN SOBRE TOKENS
-- ============================================
-- Los tokens se generan automáticamente cuando:
-- 1. Un usuario se registra (verificacion_email)
-- 2. Un usuario solicita reset de contraseña (reset_password)
--
-- Duración de tokens:
-- - Verificación de email: 24 horas
-- - Reset de contraseña: 1 hora
--
-- Los tokens expiran automáticamente y se pueden limpiar con:
-- DELETE FROM tokens_verificacion WHERE expira_en < NOW();