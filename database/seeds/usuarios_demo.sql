-- ============================================
-- SEED DE USUARIOS DEMO - CARECENTER
-- ============================================
-- Script simple para insertar usuarios de demostración
-- Solo inserta usuarios, no elimina datos existentes

-- Insertar usuarios de demostración
INSERT IGNORE INTO usuarios (nombre, apellido, email, password, telefono, rol, estado) VALUES
-- Usuario Administrador Principal
('Administrador', 'Principal', 'admin@carecenter.com', '$2y$10$rFl4O91fqUG/zAnBfSJpWOsnAcHkDYH4Kg.dGeSmLK2xN0I54hYOS', '+52 55 1234 5678', 'administrador', 'activo'),

-- Personal del Centro
('Dr. María', 'González', 'nutriologo@carecenter.com', '$2y$10$rFl4O91fqUG/zAnBfSJpWOsnAcHkDYH4Kg.dGeSmLK2xN0I54hYOS', '+52 55 2345 6789', 'nutricionista', 'activo'),
('Chef Carlos', 'Mendoza', 'cocina@carecenter.com', '$2y$10$rFl4O91fqUG/zAnBfSJpWOsnAcHkDYH4Kg.dGeSmLK2xN0I54hYOS', '+52 55 3456 7890', 'cocina', 'activo'),
('Roberto', 'Delivery', 'repartidor@carecenter.com', '$2y$10$rFl4O91fqUG/zAnBfSJpWOsnAcHkDYH4Kg.dGeSmLK2xN0I54hYOS', '+52 55 4567 8901', 'entrega', 'activo'),

-- Pacientes de Demo
('Ana', 'Paciente', 'cliente@carecenter.com', '$2y$10$rFl4O91fqUG/zAnBfSJpWOsnAcHkDYH4Kg.dGeSmLK2xN0I54hYOS', '+52 55 5678 9012', 'paciente', 'activo'),
('Luis', 'Martínez', 'luis.martinez@email.com', '$2y$10$rFl4O91fqUG/zAnBfSJpWOsnAcHkDYH4Kg.dGeSmLK2xN0I54hYOS', '+52 55 6789 0123', 'paciente', 'activo'),
('Carmen', 'López', 'carmen.lopez@email.com', '$2y$10$rFl4O91fqUG/zAnBfSJpWOsnAcHkDYH4Kg.dGeSmLK2xN0I54hYOS', '+52 55 7890 1234', 'paciente', 'activo');

-- Verificar usuarios insertados
SELECT id_usuario, nombre, apellido, email, rol, estado FROM usuarios;

-- ============================================
-- INFORMACIÓN DE LOGIN
-- ============================================
-- Todos los usuarios usan la contraseña: password123

-- CREDENCIALES DISPONIBLES:
-- admin@carecenter.com / password123 (Administrador)
-- nutriologo@carecenter.com / password123 (Nutricionista)  
-- cocina@carecenter.com / password123 (Cocina)
-- repartidor@carecenter.com / password123 (Entrega)
-- cliente@carecenter.com / password123 (Paciente)
-- luis.martinez@email.com / password123 (Paciente)
-- carmen.lopez@email.com / password123 (Paciente)

-- Hash: $2y$10$rFl4O91fqUG/zAnBfSJpWOsnAcHkDYH4Kg.dGeSmLK2xN0I54hYOS
-- Contraseña: password123