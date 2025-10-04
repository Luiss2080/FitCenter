-- ============================================
-- MIGRACIÓN 024: Agregar verificación de email a usuarios
-- ============================================

-- Agregar columna para verificación de email
ALTER TABLE usuarios ADD COLUMN email_verificado TINYINT(1) DEFAULT 0 AFTER email;

-- Crear índice para búsquedas de usuarios verificados
CREATE INDEX idx_email_verificado ON usuarios (email_verificado);

-- Actualizar usuarios existentes como verificados (para demo)
UPDATE usuarios SET email_verificado = 1 WHERE id_usuario > 0;