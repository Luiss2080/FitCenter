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