-- ============================================
-- SEED 001: Datos iniciales para tabla usuarios - FitCenter
-- ============================================

INSERT IGNORE INTO usuarios (nombre, apellido, email, password, telefono, rol, estado, email_verificado) VALUES

-- Administrador principal del gimnasio
('Admin', 'FitCenter', 'admin@fitcenter.com', '$2y$10$rFl4O91fqUG/zAnBfSJpWOsnAcHkDYH4Kg.dGeSmLK2xN0I54hYOS', '+52 55 1234 5678', 'admin', 'activo', TRUE),

-- Staff de ventas/recepción
('Juan', 'Pérez', 'vendedor@fitcenter.com', '$2y$10$rFl4O91fqUG/zAnBfSJpWOsnAcHkDYH4Kg.dGeSmLK2xN0I54hYOS', '+52 55 2345 6789', 'vendedor', 'activo', TRUE),
('Carlos', 'Mendoza', 'carlos@fitcenter.com', '$2y$10$rFl4O91fqUG/zAnBfSJpWOsnAcHkDYH4Kg.dGeSmLK2xN0I54hYOS', '+52 55 3456 7890', 'vendedor', 'activo', TRUE),

-- Clientes/Miembros del gimnasio
('Ana', 'García', 'cliente@fitcenter.com', '$2y$10$rFl4O91fqUG/zAnBfSJpWOsnAcHkDYH4Kg.dGeSmLK2xN0I54hYOS', '+52 55 5678 9012', 'cliente', 'activo', TRUE),
('María', 'López', 'maria@fitcenter.com', '$2y$10$rFl4O91fqUG/zAnBfSJpWOsnAcHkDYH4Kg.dGeSmLK2xN0I54hYOS', '+52 55 4567 8901', 'cliente', 'activo', TRUE);

-- Verificar inserción
SELECT id, nombre, apellido, email, rol, estado, email_verificado FROM usuarios ORDER BY rol, nombre;
