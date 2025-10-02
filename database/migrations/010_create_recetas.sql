-- ============================================
-- MIGRACIÓN 010: Crear tabla recetas
-- ============================================

CREATE TABLE recetas (
    id_receta INT AUTO_INCREMENT PRIMARY KEY,
    nombre_receta VARCHAR(150) NOT NULL,
    descripcion TEXT,
    ingredientes TEXT NOT NULL,
    instrucciones TEXT NOT NULL,
    tiempo_preparacion INT,
    calorias DECIMAL(7, 2),
    proteinas DECIMAL(6, 2),
    carbohidratos DECIMAL(6, 2),
    grasas DECIMAL(6, 2),
    porciones INT DEFAULT 1,
    estado ENUM('activo', 'inactivo') DEFAULT 'activo',
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_nombre (nombre_receta)
) ENGINE=InnoDB;