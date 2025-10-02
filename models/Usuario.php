<?php
/**
 * Modelo Usuario para CareCenter
 */

require_once __DIR__ . '/ModeloBase.php';

class Usuario extends ModeloBase {
    protected $tabla = 'usuarios';
    
    protected $camposPermitidos = [
        'nombre', 'email', 'password', 'telefono', 'rol_id', 
        'activo', 'ultimo_acceso', 'token_recuperacion', 
        'token_expiracion', 'avatar'
    ];
    
    protected $camposRequeridos = [
        'nombre', 'email', 'password', 'rol_id'
    ];
    
    /**
     * Buscar usuario por email
     */
    public function buscarPorEmail($email) {
        try {
            $sql = "SELECT u.*, r.nombre as rol_nombre 
                    FROM {$this->tabla} u 
                    LEFT JOIN roles r ON u.rol_id = r.id 
                    WHERE u.email = :email AND u.eliminado = 0";
                    
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            
            return $stmt->fetch();
            
        } catch (PDOException $e) {
            throw new Exception('Error al buscar usuario por email: ' . $e->getMessage());
        }
    }
    
    /**
     * Buscar usuario por ID con información del rol
     */
    public function buscarPorId($id) {
        try {
            $sql = "SELECT u.*, r.nombre as rol_nombre 
                    FROM {$this->tabla} u 
                    LEFT JOIN roles r ON u.rol_id = r.id 
                    WHERE u.id = :id AND u.eliminado = 0";
                    
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch();
            
        } catch (PDOException $e) {
            throw new Exception('Error al buscar usuario: ' . $e->getMessage());
        }
    }
    
    /**
     * Obtener todos los usuarios con información del rol
     */
    public function obtenerTodos($limite = null, $offset = 0, $orden = 'u.id ASC') {
        try {
            $sql = "SELECT u.*, r.nombre as rol_nombre 
                    FROM {$this->tabla} u 
                    LEFT JOIN roles r ON u.rol_id = r.id 
                    WHERE u.eliminado = 0 
                    ORDER BY {$orden}";
            
            if ($limite) {
                $sql .= " LIMIT :limite OFFSET :offset";
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
                $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            } else {
                $stmt = $this->db->prepare($sql);
            }
            
            $stmt->execute();
            return $stmt->fetchAll();
            
        } catch (PDOException $e) {
            throw new Exception('Error al obtener usuarios: ' . $e->getMessage());
        }
    }
    
    /**
     * Registrar último acceso del usuario
     */
    public function registrarUltimoAcceso($id) {
        try {
            $sql = "UPDATE {$this->tabla} SET ultimo_acceso = :ultimo_acceso WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':ultimo_acceso', date('Y-m-d H:i:s'));
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute();
            
        } catch (PDOException $e) {
            throw new Exception('Error al registrar acceso: ' . $e->getMessage());
        }
    }
    
    /**
     * Establecer token de recuperación de contraseña
     */
    public function establecerTokenRecuperacion($id, $token) {
        try {
            $expiracion = date('Y-m-d H:i:s', strtotime('+1 hour')); // Token válido por 1 hora
            
            $sql = "UPDATE {$this->tabla} 
                    SET token_recuperacion = :token, token_expiracion = :expiracion 
                    WHERE id = :id";
                    
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':token', $token);
            $stmt->bindValue(':expiracion', $expiracion);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute();
            
        } catch (PDOException $e) {
            throw new Exception('Error al establecer token: ' . $e->getMessage());
        }
    }
    
    /**
     * Validar token de recuperación
     */
    public function validarTokenRecuperacion($token) {
        try {
            $sql = "SELECT * FROM {$this->tabla} 
                    WHERE token_recuperacion = :token 
                    AND token_expiracion > :ahora 
                    AND eliminado = 0";
                    
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':token', $token);
            $stmt->bindValue(':ahora', date('Y-m-d H:i:s'));
            $stmt->execute();
            
            return $stmt->fetch();
            
        } catch (PDOException $e) {
            throw new Exception('Error al validar token: ' . $e->getMessage());
        }
    }
    
    /**
     * Cambiar contraseña
     */
    public function cambiarContrasena($id, $nuevaContrasena) {
        try {
            $contrasenaHash = password_hash($nuevaContrasena, PASSWORD_DEFAULT);
            
            $sql = "UPDATE {$this->tabla} 
                    SET password = :password, 
                        token_recuperacion = NULL, 
                        token_expiracion = NULL,
                        updated_at = :updated_at
                    WHERE id = :id";
                    
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':password', $contrasenaHash);
            $stmt->bindValue(':updated_at', date('Y-m-d H:i:s'));
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute();
            
        } catch (PDOException $e) {
            throw new Exception('Error al cambiar contraseña: ' . $e->getMessage());
        }
    }
    
    /**
     * Activar/desactivar usuario
     */
    public function cambiarEstado($id, $activo) {
        try {
            $sql = "UPDATE {$this->tabla} 
                    SET activo = :activo, updated_at = :updated_at 
                    WHERE id = :id";
                    
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':activo', $activo ? 1 : 0, PDO::PARAM_INT);
            $stmt->bindValue(':updated_at', date('Y-m-d H:i:s'));
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute();
            
        } catch (PDOException $e) {
            throw new Exception('Error al cambiar estado: ' . $e->getMessage());
        }
    }
    
    /**
     * Buscar usuarios por rol
     */
    public function buscarPorRol($rolId) {
        try {
            $sql = "SELECT u.*, r.nombre as rol_nombre 
                    FROM {$this->tabla} u 
                    LEFT JOIN roles r ON u.rol_id = r.id 
                    WHERE u.rol_id = :rol_id AND u.eliminado = 0 AND u.activo = 1
                    ORDER BY u.nombre ASC";
                    
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':rol_id', $rolId, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll();
            
        } catch (PDOException $e) {
            throw new Exception('Error al buscar por rol: ' . $e->getMessage());
        }
    }
    
    /**
     * Obtener estadísticas de usuarios
     */
    public function obtenerEstadisticas() {
        try {
            $sql = "SELECT 
                        COUNT(*) as total,
                        SUM(CASE WHEN activo = 1 THEN 1 ELSE 0 END) as activos,
                        SUM(CASE WHEN activo = 0 THEN 1 ELSE 0 END) as inactivos,
                        COUNT(CASE WHEN DATE(created_at) = CURDATE() THEN 1 END) as nuevos_hoy
                    FROM {$this->tabla} 
                    WHERE eliminado = 0";
                    
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            
            return $stmt->fetch();
            
        } catch (PDOException $e) {
            throw new Exception('Error al obtener estadísticas: ' . $e->getMessage());
        }
    }
    
    /**
     * Validaciones específicas para usuario
     */
    protected function validar($datos) {
        parent::validar($datos);
        
        $errores = [];
        
        // Validar email
        if (isset($datos['email']) && !filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
            $errores[] = 'Email inválido';
        }
        
        // Validar contraseña (solo en creación)
        if (isset($datos['password']) && strlen($datos['password']) < 6) {
            $errores[] = 'La contraseña debe tener al menos 6 caracteres';
        }
        
        // Validar teléfono
        if (isset($datos['telefono']) && !empty($datos['telefono'])) {
            if (!preg_match('/^[0-9+\-\s()]+$/', $datos['telefono'])) {
                $errores[] = 'Formato de teléfono inválido';
            }
        }
        
        if (!empty($errores)) {
            throw new Exception('Errores de validación: ' . implode(', ', $errores));
        }
    }
    
    /**
     * Crear usuario con validaciones adicionales
     */
    public function crear($datos) {
        // Verificar que el email no exista
        if ($this->buscarPorEmail($datos['email'])) {
            throw new Exception('El email ya está registrado');
        }
        
        // Hashear contraseña si no está hasheada
        if (isset($datos['password']) && !password_get_info($datos['password'])['algo']) {
            $datos['password'] = password_hash($datos['password'], PASSWORD_DEFAULT);
        }
        
        // Establecer valores por defecto
        $datos['activo'] = $datos['activo'] ?? 1;
        
        return parent::crear($datos);
    }
    
    /**
     * Actualizar último acceso del usuario
     */
    public function actualizarUltimoAcceso($usuarioId) {
        try {
            $sql = "UPDATE {$this->tabla} 
                    SET ultimo_acceso = NOW() 
                    WHERE id = :id";
                    
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $usuarioId);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            Logger::error('Error al actualizar último acceso: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Guardar token "recordar sesión"
     */
    public function guardarTokenRecordar($usuarioId, $token) {
        try {
            $sql = "UPDATE {$this->tabla} 
                    SET remember_token = :token, 
                        remember_token_expires = DATE_ADD(NOW(), INTERVAL 30 DAY)
                    WHERE id = :id";
                    
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':token', hash('sha256', $token));
            $stmt->bindValue(':id', $usuarioId);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            Logger::error('Error al guardar token recordar: ' . $e->getMessage());
            return false;
        }
    }
    
}