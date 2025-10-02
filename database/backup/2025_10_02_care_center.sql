-- ============================================
-- MIGRACIÓN 001: Crear tabla usuarios
-- ============================================

CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    telefono VARCHAR(20),
    rol ENUM('administrador', 'nutricionista', 'cocina', 'entrega', 'paciente') NOT NULL,
    estado ENUM('activo', 'inactivo') DEFAULT 'activo',
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    ultimo_acceso DATETIME NULL,
    INDEX idx_email (email),
    INDEX idx_rol (rol)
) ENGINE=InnoDB;

-- Insertar usuario administrador por defecto
INSERT INTO usuarios (nombre, apellido, email, password, telefono, rol) VALUES
('Admin', 'Sistema', 'admin@carecenter.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '555-0000', 'administrador');

-- ============================================
-- MIGRACIÓN 002: Crear tabla pacientes
-- ============================================

CREATE TABLE pacientes (
    id_paciente INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    documento_identidad VARCHAR(50) UNIQUE NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    genero ENUM('masculino', 'femenino', 'otro') NOT NULL,
    ocupacion VARCHAR(100),
    contacto_emergencia VARCHAR(200),
    telefono_emergencia VARCHAR(20),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    INDEX idx_documento (documento_identidad)
) ENGINE=InnoDB;

-- ============================================
-- MIGRACIÓN 003: Crear tabla direcciones
-- ============================================

CREATE TABLE direcciones (
    id_direccion INT AUTO_INCREMENT PRIMARY KEY,
    id_paciente INT NOT NULL,
    nombre_direccion VARCHAR(100) NOT NULL,
    direccion_completa TEXT NOT NULL,
    referencia VARCHAR(255),
    latitud DECIMAL(10, 8) NULL,
    longitud DECIMAL(11, 8) NULL,
    es_principal BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_paciente) REFERENCES pacientes(id_paciente) ON DELETE CASCADE,
    INDEX idx_paciente (id_paciente)
) ENGINE=InnoDB;

-- ============================================
-- MIGRACIÓN 004: Crear tabla nutricionistas
-- ============================================

CREATE TABLE nutricionistas (
    id_nutricionista INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    numero_colegiatura VARCHAR(50) UNIQUE NOT NULL,
    especialidad VARCHAR(150),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================
-- MIGRACIÓN 005: Crear tabla servicios
-- ============================================

CREATE TABLE servicios (
    id_servicio INT AUTO_INCREMENT PRIMARY KEY,
    nombre_servicio VARCHAR(100) NOT NULL,
    descripcion TEXT,
    tipo_servicio ENUM('asesoramiento', 'catering') NOT NULL,
    duracion_dias INT NOT NULL,
    costo DECIMAL(10, 2) NOT NULL,
    incluye_evaluaciones BOOLEAN DEFAULT TRUE,
    estado ENUM('activo', 'inactivo') DEFAULT 'activo',
    INDEX idx_tipo (tipo_servicio)
) ENGINE=InnoDB;

-- Insertar servicios base
INSERT INTO servicios (nombre_servicio, descripcion, tipo_servicio, duracion_dias, costo, incluye_evaluaciones) VALUES
('Asesoramiento Nutricional Básico', 'Plan alimentario de 15 días con evaluación de control', 'asesoramiento', 15, 150.00, TRUE),
('Catering 15 días', 'Servicio de catering con alimentación diaria por 15 días', 'catering', 15, 450.00, TRUE),
('Catering 30 días', 'Servicio de catering con alimentación diaria por 30 días', 'catering', 30, 850.00, TRUE);

-- ============================================
-- MIGRACIÓN 006: Crear tabla contratos_servicio
-- ============================================

CREATE TABLE contratos_servicio (
    id_contrato INT AUTO_INCREMENT PRIMARY KEY,
    id_paciente INT NOT NULL,
    id_servicio INT NOT NULL,
    id_nutricionista INT NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    estado ENUM('activo', 'completado', 'cancelado') DEFAULT 'activo',
    monto_total DECIMAL(10, 2) NOT NULL,
    fecha_contratacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_paciente) REFERENCES pacientes(id_paciente) ON DELETE CASCADE,
    FOREIGN KEY (id_servicio) REFERENCES servicios(id_servicio),
    FOREIGN KEY (id_nutricionista) REFERENCES nutricionistas(id_nutricionista),
    INDEX idx_paciente (id_paciente),
    INDEX idx_estado (estado)
) ENGINE=InnoDB;

-- ============================================
-- MIGRACIÓN 007: Crear tabla facturas
-- ============================================

CREATE TABLE facturas (
    id_factura INT AUTO_INCREMENT PRIMARY KEY,
    id_contrato INT NOT NULL,
    numero_factura VARCHAR(50) UNIQUE NOT NULL,
    fecha_emision DATETIME DEFAULT CURRENT_TIMESTAMP,
    subtotal DECIMAL(10, 2) NOT NULL,
    impuesto DECIMAL(10, 2) DEFAULT 0,
    total DECIMAL(10, 2) NOT NULL,
    estado_pago ENUM('pagado', 'pendiente', 'anulado') DEFAULT 'pendiente',
    FOREIGN KEY (id_contrato) REFERENCES contratos_servicio(id_contrato) ON DELETE CASCADE,
    INDEX idx_numero (numero_factura)
) ENGINE=InnoDB;

-- ============================================
-- MIGRACIÓN 008: Crear tabla consultas
-- ============================================

CREATE TABLE consultas (
    id_consulta INT AUTO_INCREMENT PRIMARY KEY,
    id_contrato INT NOT NULL,
    tipo_consulta ENUM('inicial', 'evaluacion') NOT NULL,
    fecha_consulta DATE NOT NULL,
    peso DECIMAL(5, 2) NOT NULL,
    altura DECIMAL(5, 2) NOT NULL,
    imc DECIMAL(5, 2) GENERATED ALWAYS AS (peso / ((altura / 100) * (altura / 100))) STORED,
    grasa_corporal DECIMAL(5, 2),
    masa_muscular DECIMAL(5, 2),
    observaciones TEXT,
    diagnostico TEXT,
    habitos_alimenticios TEXT,
    antecedentes_clinicos TEXT,
    FOREIGN KEY (id_contrato) REFERENCES contratos_servicio(id_contrato) ON DELETE CASCADE,
    INDEX idx_contrato (id_contrato),
    INDEX idx_fecha (fecha_consulta)
) ENGINE=InnoDB;

-- ============================================
-- MIGRACIÓN 009: Crear tabla analisis_clinicos
-- ============================================

CREATE TABLE analisis_clinicos (
    id_analisis INT AUTO_INCREMENT PRIMARY KEY,
    id_consulta INT NOT NULL,
    tipo_analisis VARCHAR(150) NOT NULL,
    fecha_solicitud DATE NOT NULL,
    fecha_resultado DATE NULL,
    resultado TEXT,
    archivo_adjunto VARCHAR(255) NULL,
    FOREIGN KEY (id_consulta) REFERENCES consultas(id_consulta) ON DELETE CASCADE,
    INDEX idx_consulta (id_consulta)
) ENGINE=InnoDB;

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

-- ============================================
-- MIGRACIÓN 011: Crear tabla planes_alimentarios
-- ============================================

CREATE TABLE planes_alimentarios (
    id_plan INT AUTO_INCREMENT PRIMARY KEY,
    nombre_plan VARCHAR(150) NOT NULL,
    descripcion TEXT,
    objetivo VARCHAR(255),
    tiempos_comida_dia INT NOT NULL,
    estado ENUM('activo', 'inactivo') DEFAULT 'activo',
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_nombre (nombre_plan)
) ENGINE=InnoDB;

-- ============================================
-- MIGRACIÓN 012: Crear tabla plan_tiempos_comida
-- ============================================

CREATE TABLE plan_tiempos_comida (
    id_tiempo_comida INT AUTO_INCREMENT PRIMARY KEY,
    id_plan INT NOT NULL,
    nombre_tiempo VARCHAR(50) NOT NULL,
    hora_sugerida TIME,
    orden INT NOT NULL,
    FOREIGN KEY (id_plan) REFERENCES planes_alimentarios(id_plan) ON DELETE CASCADE,
    INDEX idx_plan (id_plan)
) ENGINE=InnoDB;

-- ============================================
-- MIGRACIÓN 013: Crear tabla plan_recetas
-- ============================================

CREATE TABLE plan_recetas (
    id_plan_receta INT AUTO_INCREMENT PRIMARY KEY,
    id_tiempo_comida INT NOT NULL,
    id_receta INT NOT NULL,
    porcion DECIMAL(5, 2) DEFAULT 1,
    FOREIGN KEY (id_tiempo_comida) REFERENCES plan_tiempos_comida(id_tiempo_comida) ON DELETE CASCADE,
    FOREIGN KEY (id_receta) REFERENCES recetas(id_receta) ON DELETE CASCADE,
    INDEX idx_tiempo (id_tiempo_comida),
    INDEX idx_receta (id_receta)
) ENGINE=InnoDB;

-- ============================================
-- MIGRACIÓN 014: Crear tabla asignacion_planes
-- ============================================

CREATE TABLE asignacion_planes (
    id_asignacion INT AUTO_INCREMENT PRIMARY KEY,
    id_contrato INT NOT NULL,
    id_plan INT NOT NULL,
    fecha_asignacion DATE NOT NULL,
    observaciones TEXT,
    FOREIGN KEY (id_contrato) REFERENCES contratos_servicio(id_contrato) ON DELETE CASCADE,
    FOREIGN KEY (id_plan) REFERENCES planes_alimentarios(id_plan),
    INDEX idx_contrato (id_contrato)
) ENGINE=InnoDB;

-- ============================================
-- MIGRACIÓN 015: Crear tabla calendario_entregas
-- ============================================

CREATE TABLE calendario_entregas (
    id_calendario INT AUTO_INCREMENT PRIMARY KEY,
    id_contrato INT NOT NULL,
    fecha_entrega DATE NOT NULL,
    id_direccion INT NOT NULL,
    requiere_entrega BOOLEAN DEFAULT TRUE,
    observaciones VARCHAR(255),
    fecha_modificacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_contrato) REFERENCES contratos_servicio(id_contrato) ON DELETE CASCADE,
    FOREIGN KEY (id_direccion) REFERENCES direcciones(id_direccion),
    INDEX idx_fecha (fecha_entrega),
    INDEX idx_contrato (id_contrato),
    UNIQUE KEY unique_contrato_fecha (id_contrato, fecha_entrega)
) ENGINE=InnoDB;

-- ============================================
-- MIGRACIÓN 016: Crear tabla ordenes_produccion
-- ============================================

CREATE TABLE ordenes_produccion (
    id_orden INT AUTO_INCREMENT PRIMARY KEY,
    fecha_produccion DATE NOT NULL,
    fecha_entrega DATE NOT NULL,
    estado ENUM('pendiente', 'en_proceso', 'completada') DEFAULT 'pendiente',
    id_usuario_asignado INT NULL,
    fecha_inicio DATETIME NULL,
    fecha_completado DATETIME NULL,
    FOREIGN KEY (id_usuario_asignado) REFERENCES usuarios(id_usuario),
    INDEX idx_fecha_entrega (fecha_entrega),
    INDEX idx_estado (estado),
    UNIQUE KEY unique_fecha_entrega (fecha_entrega)
) ENGINE=InnoDB;

-- ============================================
-- MIGRACIÓN 017: Crear tabla orden_produccion_detalle
-- ============================================

CREATE TABLE orden_produccion_detalle (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_orden INT NOT NULL,
    id_receta INT NOT NULL,
    cantidad_porciones INT NOT NULL,
    estado ENUM('pendiente', 'preparando', 'completado') DEFAULT 'pendiente',
    FOREIGN KEY (id_orden) REFERENCES ordenes_produccion(id_orden) ON DELETE CASCADE,
    FOREIGN KEY (id_receta) REFERENCES recetas(id_receta),
    INDEX idx_orden (id_orden)
) ENGINE=InnoDB;

-- ============================================
-- MIGRACIÓN 018: Crear tabla paquetes_entrega
-- ============================================

CREATE TABLE paquetes_entrega (
    id_paquete INT AUTO_INCREMENT PRIMARY KEY,
    id_orden INT NOT NULL,
    id_calendario INT NOT NULL,
    numero_paquete VARCHAR(50) UNIQUE NOT NULL,
    fecha_empaquetado DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('empaquetado', 'asignado', 'en_ruta', 'entregado', 'no_entregado') DEFAULT 'empaquetado',
    FOREIGN KEY (id_orden) REFERENCES ordenes_produccion(id_orden) ON DELETE CASCADE,
    FOREIGN KEY (id_calendario) REFERENCES calendario_entregas(id_calendario) ON DELETE CASCADE,
    INDEX idx_orden (id_orden),
    INDEX idx_numero (numero_paquete),
    INDEX idx_estado (estado)
) ENGINE=InnoDB;

-- ============================================
-- MIGRACIÓN 019: Crear tabla rutas_entrega
-- ============================================

CREATE TABLE rutas_entrega (
    id_ruta INT AUTO_INCREMENT PRIMARY KEY,
    fecha_ruta DATE NOT NULL,
    id_repartidor INT NOT NULL,
    estado ENUM('pendiente', 'en_curso', 'completada') DEFAULT 'pendiente',
    fecha_inicio DATETIME NULL,
    fecha_fin DATETIME NULL,
    FOREIGN KEY (id_repartidor) REFERENCES usuarios(id_usuario),
    INDEX idx_fecha (fecha_ruta),
    INDEX idx_repartidor (id_repartidor)
) ENGINE=InnoDB;

-- ============================================
-- MIGRACIÓN 020: Crear tabla ruta_paquetes
-- ============================================

CREATE TABLE ruta_paquetes (
    id_ruta_paquete INT AUTO_INCREMENT PRIMARY KEY,
    id_ruta INT NOT NULL,
    id_paquete INT NOT NULL,
    orden_entrega INT NOT NULL,
    FOREIGN KEY (id_ruta) REFERENCES rutas_entrega(id_ruta) ON DELETE CASCADE,
    FOREIGN KEY (id_paquete) REFERENCES paquetes_entrega(id_paquete) ON DELETE CASCADE,
    INDEX idx_ruta (id_ruta),
    UNIQUE KEY unique_ruta_paquete (id_ruta, id_paquete)
) ENGINE=InnoDB;

-- ============================================
-- MIGRACIÓN 021: Crear tabla entregas_realizadas
-- ============================================

CREATE TABLE entregas_realizadas (
    id_entrega INT AUTO_INCREMENT PRIMARY KEY,
    id_paquete INT NOT NULL,
    fecha_hora_entrega DATETIME NOT NULL,
    latitud_entrega DECIMAL(10, 8) NULL,
    longitud_entrega DECIMAL(11, 8) NULL,
    estado_entrega ENUM('entregado', 'no_recibido', 'reprogramado') NOT NULL,
    receptor VARCHAR(100),
    observaciones TEXT,
    firma_digital VARCHAR(255) NULL,
    foto_evidencia VARCHAR(255) NULL,
    codigo_confirmacion VARCHAR(50) NULL,
    FOREIGN KEY (id_paquete) REFERENCES paquetes_entrega(id_paquete) ON DELETE CASCADE,
    INDEX idx_paquete (id_paquete),
    INDEX idx_fecha (fecha_hora_entrega)
) ENGINE=InnoDB;

-- ============================================
-- MIGRACIÓN 022: Crear tabla logs_sistema
-- ============================================

CREATE TABLE logs_sistema (
    id_log INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NULL,
    accion VARCHAR(100) NOT NULL,
    tabla_afectada VARCHAR(50),
    id_registro INT,
    descripcion TEXT,
    ip_address VARCHAR(45),
    fecha_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE SET NULL,
    INDEX idx_usuario (id_usuario),
    INDEX idx_fecha (fecha_hora),
    INDEX idx_tabla (tabla_afectada)
) ENGINE=InnoDB;