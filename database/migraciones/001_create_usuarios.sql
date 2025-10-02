-- Migración: Crear tabla de usuarios
-- Fecha: 2025-10-01
-- Descripción: Tabla base para la gestión de usuarios del sistema

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    telefono VARCHAR(20) NULL,
    avatar VARCHAR(255) NULL,
    rol_id INT NOT NULL,
    activo BOOLEAN DEFAULT 1,
    eliminado BOOLEAN DEFAULT 0,
    ultimo_acceso TIMESTAMP NULL,
    token_recuperacion VARCHAR(255) NULL,
    token_expiracion TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    
    INDEX idx_usuarios_email (email),
    INDEX idx_usuarios_rol (rol_id),
    INDEX idx_usuarios_activo (activo),
    INDEX idx_usuarios_eliminado (eliminado)
);

-- Crear tabla de roles
CREATE TABLE IF NOT EXISTS roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion TEXT NULL,
    activo BOOLEAN DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insertar roles básicos
INSERT INTO roles (id, nombre, descripcion) VALUES
(1, 'Administrador', 'Acceso completo al sistema'),
(2, 'Nutriólogo', 'Gestión de pacientes y planes nutricionales'),
(3, 'Cocina', 'Gestión de producción y recetas'),
(4, 'Repartidor', 'Gestión de entregas'),
(5, 'Cliente', 'Acceso a información personal y planes');

-- Agregar clave foránea
ALTER TABLE usuarios 
ADD CONSTRAINT fk_usuarios_rol 
FOREIGN KEY (rol_id) REFERENCES roles(id) 
ON DELETE RESTRICT ON UPDATE CASCADE;

-- Crear usuario administrador por defecto
INSERT INTO usuarios (nombre, email, password, rol_id) VALUES 
('Administrador', 'admin@carecenter.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1);
-- Password: password123