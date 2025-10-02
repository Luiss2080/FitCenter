-- Migración: Crear tablas de direcciones
-- Fecha: 2025-10-01
-- Descripción: Gestión de direcciones para pacientes y entregas

CREATE TABLE IF NOT EXISTS direcciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente_id INT NOT NULL,
    tipo ENUM('principal', 'trabajo', 'familiar', 'temporal') DEFAULT 'principal',
    nombre_referencia VARCHAR(100) NULL COMMENT 'Nombre para identificar la dirección (Casa, Oficina, etc.)',
    calle VARCHAR(200) NOT NULL,
    numero_exterior VARCHAR(20) NOT NULL,
    numero_interior VARCHAR(20) NULL,
    colonia VARCHAR(100) NOT NULL,
    codigo_postal VARCHAR(10) NOT NULL,
    ciudad VARCHAR(100) NOT NULL,
    estado VARCHAR(100) NOT NULL,
    pais VARCHAR(100) DEFAULT 'México',
    referencias TEXT NULL COMMENT 'Referencias adicionales para ubicar la dirección',
    latitud DECIMAL(10, 8) NULL COMMENT 'Coordenada de latitud para geolocalización',
    longitud DECIMAL(11, 8) NULL COMMENT 'Coordenada de longitud para geolocalización',
    es_principal BOOLEAN DEFAULT 0 COMMENT 'Indica si es la dirección principal del paciente',
    activa BOOLEAN DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_direcciones_paciente (paciente_id),
    INDEX idx_direcciones_cp (codigo_postal),
    INDEX idx_direcciones_ciudad (ciudad),
    INDEX idx_direcciones_coordenadas (latitud, longitud),
    INDEX idx_direcciones_principal (es_principal),
    INDEX idx_direcciones_activa (activa),
    
    FOREIGN KEY (paciente_id) REFERENCES pacientes(id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Tabla de zonas de entrega
CREATE TABLE IF NOT EXISTS zonas_entrega (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT NULL,
    codigos_postales TEXT NULL COMMENT 'Lista de códigos postales separados por comas',
    ciudades TEXT NULL COMMENT 'Lista de ciudades cubiertas separadas por comas',
    costo_entrega DECIMAL(8,2) DEFAULT 0.00 COMMENT 'Costo adicional de entrega para esta zona',
    tiempo_entrega_min INT DEFAULT 60 COMMENT 'Tiempo mínimo de entrega en minutos',
    tiempo_entrega_max INT DEFAULT 120 COMMENT 'Tiempo máximo de entrega en minutos',
    activa BOOLEAN DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_zonas_activa (activa)
);

-- Insertar zonas de entrega básicas
INSERT INTO zonas_entrega (nombre, descripcion, costo_entrega, tiempo_entrega_min, tiempo_entrega_max) VALUES
('Zona Centro', 'Centro de la ciudad y colonias aledañas', 0.00, 30, 60),
('Zona Norte', 'Zona norte de la ciudad', 25.00, 45, 90),
('Zona Sur', 'Zona sur de la ciudad', 25.00, 45, 90),
('Zona Este', 'Zona este de la ciudad', 30.00, 60, 120),
('Zona Oeste', 'Zona oeste de la ciudad', 30.00, 60, 120),
('Zona Metropolitana', 'Municipios metropolitanos', 50.00, 90, 180);

-- Tabla de horarios de entrega por zona
CREATE TABLE IF NOT EXISTS horarios_entrega (
    id INT AUTO_INCREMENT PRIMARY KEY,
    zona_id INT NOT NULL,
    dia_semana TINYINT NOT NULL COMMENT '1=Lunes, 2=Martes, ..., 7=Domingo',
    hora_inicio TIME NOT NULL,
    hora_fin TIME NOT NULL,
    activo BOOLEAN DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_horarios_zona (zona_id),
    INDEX idx_horarios_dia (dia_semana),
    INDEX idx_horarios_activo (activo),
    
    FOREIGN KEY (zona_id) REFERENCES zonas_entrega(id) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE KEY unique_zona_dia_horario (zona_id, dia_semana, hora_inicio, hora_fin)
);

-- Insertar horarios básicos (Lunes a Viernes de 8:00 a 20:00, Sábados de 9:00 a 18:00)
INSERT INTO horarios_entrega (zona_id, dia_semana, hora_inicio, hora_fin) 
SELECT 
    z.id, 
    d.dia, 
    CASE WHEN d.dia = 7 THEN '09:00:00' ELSE '08:00:00' END as hora_inicio,
    CASE WHEN d.dia = 7 THEN '18:00:00' ELSE '20:00:00' END as hora_fin
FROM zonas_entrega z
CROSS JOIN (
    SELECT 1 as dia UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6
) d
WHERE z.activa = 1;