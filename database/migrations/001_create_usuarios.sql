-- ============================================
-- MIGRACIÓN 001: Crear tabla usuarios - FitCenter
-- ============================================

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    telefono VARCHAR(20),
    rol ENUM('admin', 'vendedor', 'cliente') NOT NULL DEFAULT 'cliente',
    estado ENUM('activo', 'inactivo', 'suspendido') DEFAULT 'activo',
    email_verificado BOOLEAN DEFAULT FALSE,
    fecha_ultimo_login TIMESTAMP NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_rol (rol),
    INDEX idx_estado (estado)
) ENGINE=InnoDB;

