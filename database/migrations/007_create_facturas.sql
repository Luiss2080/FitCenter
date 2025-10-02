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