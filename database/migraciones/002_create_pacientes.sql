-- Migración: Crear tabla de pacientes
-- Fecha: 2025-10-01
-- Descripción: Tabla para la gestión de pacientes/clientes

CREATE TABLE IF NOT EXISTS pacientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NULL, -- Referencia al usuario si tiene cuenta
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    email VARCHAR(150) NULL,
    telefono VARCHAR(20) NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    genero ENUM('masculino', 'femenino', 'otro') NOT NULL,
    estatura DECIMAL(5,2) NULL COMMENT 'Estatura en centímetros',
    peso_inicial DECIMAL(5,2) NULL COMMENT 'Peso inicial en kilogramos',
    peso_objetivo DECIMAL(5,2) NULL COMMENT 'Peso objetivo en kilogramos',
    actividad_fisica ENUM('sedentario', 'ligero', 'moderado', 'intenso', 'muy_intenso') DEFAULT 'sedentario',
    condiciones_medicas TEXT NULL,
    alergias_alimentarias TEXT NULL,
    preferencias_alimentarias TEXT NULL,
    observaciones TEXT NULL,
    nutriologo_id INT NULL COMMENT 'Nutriólogo asignado',
    activo BOOLEAN DEFAULT 1,
    eliminado BOOLEAN DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    
    INDEX idx_pacientes_usuario (usuario_id),
    INDEX idx_pacientes_nutriologo (nutriologo_id),
    INDEX idx_pacientes_email (email),
    INDEX idx_pacientes_telefono (telefono),
    INDEX idx_pacientes_activo (activo),
    INDEX idx_pacientes_eliminado (eliminado),
    
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (nutriologo_id) REFERENCES usuarios(id) ON DELETE SET NULL ON UPDATE CASCADE
);

-- Tabla de mediciones corporales
CREATE TABLE IF NOT EXISTS mediciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente_id INT NOT NULL,
    peso DECIMAL(5,2) NOT NULL COMMENT 'Peso en kilogramos',
    estatura DECIMAL(5,2) NULL COMMENT 'Estatura en centímetros',
    imc DECIMAL(4,2) NULL COMMENT 'Índice de masa corporal calculado',
    grasa_corporal DECIMAL(4,1) NULL COMMENT 'Porcentaje de grasa corporal',
    masa_muscular DECIMAL(4,1) NULL COMMENT 'Porcentaje de masa muscular',
    circunferencia_cintura DECIMAL(5,2) NULL COMMENT 'Circunferencia de cintura en cm',
    circunferencia_cadera DECIMAL(5,2) NULL COMMENT 'Circunferencia de cadera en cm',
    circunferencia_brazo DECIMAL(5,2) NULL COMMENT 'Circunferencia de brazo en cm',
    presion_arterial VARCHAR(10) NULL COMMENT 'Presión arterial (ej: 120/80)',
    observaciones TEXT NULL,
    fecha_medicion DATE NOT NULL,
    usuario_registro_id INT NOT NULL COMMENT 'Usuario que registró la medición',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_mediciones_paciente (paciente_id),
    INDEX idx_mediciones_fecha (fecha_medicion),
    INDEX idx_mediciones_usuario (usuario_registro_id),
    
    FOREIGN KEY (paciente_id) REFERENCES pacientes(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (usuario_registro_id) REFERENCES usuarios(id) ON DELETE RESTRICT ON UPDATE CASCADE
);