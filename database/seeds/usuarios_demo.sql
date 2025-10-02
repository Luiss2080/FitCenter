-- Script para crear usuarios de demostración en CareCenter
-- Ejecutar este script después de crear las tablas
-- Estructura acorde a 001_create_usuarios.sql

-- Insertar usuarios de demostración
INSERT IGNORE INTO usuarios (nombre, apellido, email, password, telefono, rol, estado) VALUES
('Administrador', 'Principal', 'admin@carecenter.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+52 55 1234 5678', 'administrador', 'activo'),
('Dr. María', 'González', 'nutriologo@carecenter.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+52 55 2345 6789', 'nutricionista', 'activo'),
('Chef Carlos', 'Mendoza', 'cocina@carecenter.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+52 55 3456 7890', 'cocina', 'activo'),
('Roberto', 'Delivery', 'repartidor@carecenter.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+52 55 4567 8901', 'entrega', 'activo'),
('Ana', 'Paciente', 'cliente@carecenter.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+52 55 5678 9012', 'paciente', 'activo');

-- Verificar que los usuarios fueron insertados
SELECT id_usuario, nombre, apellido, email, rol, estado FROM usuarios;

-- Información de login para las cuentas de demo:
-- admin@carecenter.com / password123
-- nutriologo@carecenter.com / password123  
-- cocina@carecenter.com / password123
-- repartidor@carecenter.com / password123
-- cliente@carecenter.com / password123

-- Nota: Todos usan la misma contraseña hasheada para facilitar las pruebas
-- El hash corresponde a: password123