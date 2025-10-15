<?php
/**
 * Modelo Usuario para FitCenter
 */

require_once 'BaseModel.php';

class Usuario extends BaseModel {
    protected $table = 'usuarios';
    
    /**
     * Buscar usuario por email
     */
    public function findByEmail($email) {
        return $this->findOneWhere('email = :email', ['email' => $email]);
    }
    
    /**
     * Verificar si el email ya existe
     */
    public function emailExists($email, $excludeId = null) {
        $conditions = 'email = :email';
        $params = ['email' => $email];
        
        if ($excludeId) {
            $conditions .= ' AND id != :id';
            $params['id'] = $excludeId;
        }
        
        return $this->exists($conditions, $params);
    }
    
    /**
     * Crear nuevo usuario
     */
    public function createUser($data) {
        // Hash de la contraseña
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        // Valores por defecto
        $data['creado_en'] = date('Y-m-d H:i:s');
        $data['estado'] = $data['estado'] ?? 'pendiente';
        
        return $this->create($data);
    }
    
    /**
     * Verificar contraseña
     */
    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
    
    /**
     * Actualizar último login
     */
    public function updateLastLogin($userId) {
        return $this->update($userId, ['ultimo_login' => date('Y-m-d H:i:s')]);
    }
    
    /**
     * Activar usuario
     */
    public function activateUser($userId) {
        return $this->update($userId, [
            'estado' => 'activo',
            'email_verificado' => 1,
            'fecha_verificacion' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Buscar usuarios por rol
     */
    public function findByRole($rol) {
        return $this->findWhere('rol = :rol', ['rol' => $rol]);
    }
    
    /**
     * Buscar usuarios activos
     */
    public function findActive() {
        return $this->findWhere('estado = :estado', ['estado' => 'activo']);
    }
    
    /**
     * Cambiar contraseña
     */
    public function changePassword($userId, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        return $this->update($userId, ['password' => $hashedPassword]);
    }
    
    /**
     * Obtener estadísticas de usuarios
     */
    public function getStats() {
        $stats = [];
        
        // Total de usuarios
        $stats['total'] = $this->count();
        
        // Por rol
        $stmt = $this->query("
            SELECT rol, COUNT(*) as cantidad 
            FROM usuarios 
            GROUP BY rol
        ");
        $stats['por_rol'] = $stmt->fetchAll();
        
        // Por estado
        $stmt = $this->query("
            SELECT estado, COUNT(*) as cantidad 
            FROM usuarios 
            GROUP BY estado
        ");
        $stats['por_estado'] = $stmt->fetchAll();
        
        // Nuevos usuarios últimos 30 días
        $stats['nuevos_mes'] = $this->count(
            'creado_en >= DATE_SUB(NOW(), INTERVAL 30 DAY)'
        );
        
        return $stats;
    }
}
?>