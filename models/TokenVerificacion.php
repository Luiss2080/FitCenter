<?php
/**
 * Modelo TokenVerificacion para FitCenter
 */

require_once 'BaseModel.php';

class TokenVerificacion extends BaseModel {
    protected $table = 'tokens_verificacion';
    
    /**
     * Generar nuevo token de verificación
     */
    public function generarToken($usuarioId, $email, $tipo = 'email_verification') {
        // Generar token único
        $token = bin2hex(random_bytes(32));
        
        // Calcular fecha de expiración (24 horas para email, 1 hora para password)
        $horasExpiracion = $tipo === 'password_reset' ? 1 : 24;
        $fechaExpiracion = date('Y-m-d H:i:s', strtotime("+$horasExpiracion hours"));
        
        $data = [
            'usuario_id' => $usuarioId,
            'email' => $email,
            'token' => $token,
            'tipo' => $tipo,
            'expira_en' => $fechaExpiracion,
            'creado_en' => date('Y-m-d H:i:s')
        ];
        
        if ($this->create($data)) {
            return $token;
        }
        
        return false;
    }
    
    /**
     * Verificar token
     */
    public function verificarToken($token, $tipo = null) {
        $conditions = 'token = :token AND usado = 0 AND expira_en > NOW()';
        $params = ['token' => $token];
        
        if ($tipo) {
            $conditions .= ' AND tipo = :tipo';
            $params['tipo'] = $tipo;
        }
        
        return $this->findOneWhere($conditions, $params);
    }
    
    /**
     * Marcar token como usado
     */
    public function marcarUsado($token) {
        $tokenData = $this->findOneWhere('token = :token', ['token' => $token]);
        
        if ($tokenData) {
            return $this->update($tokenData['id'], [
                'usado' => 1
            ]);
        }
        
        return false;
    }
    
    /**
     * Obtener tokens activos por usuario
     */
    public function getTokensActivos($usuarioId, $tipo = null) {
        $conditions = 'usuario_id = :usuario_id AND usado = 0 AND expira_en > NOW()';
        $params = ['usuario_id' => $usuarioId];
        
        if ($tipo) {
            $conditions .= ' AND tipo = :tipo';
            $params['tipo'] = $tipo;
        }
        
        return $this->findWhere($conditions, $params);
    }
    
    /**
     * Invalidar tokens antiguos de un usuario
     */
    public function invalidarTokensUsuario($usuarioId, $tipo = null) {
        $conditions = 'usuario_id = :usuario_id AND usado = 0';
        $params = ['usuario_id' => $usuarioId];
        
        if ($tipo) {
            $conditions .= ' AND tipo = :tipo';
            $params['tipo'] = $tipo;
        }
        
        // Marcar como usados
        $stmt = $this->pdo->prepare("
            UPDATE tokens_verificacion 
            SET usado = 1 
            WHERE $conditions
        ");
        
        return $stmt->execute($params);
    }
    
    /**
     * Limpiar tokens expirados
     */
    public function limpiarExpirados() {
        $stmt = $this->query("
            DELETE FROM tokens_verificacion 
            WHERE expira_en < NOW() OR usado = 1
        ");
        
        return $stmt->rowCount();
    }
    
    /**
     * Obtener estadísticas de tokens
     */
    public function getStats() {
        $stats = [];
        
        // Total de tokens
        $stats['total'] = $this->count();
        
        // Tokens activos
        $stats['activos'] = $this->count('usado = 0 AND expira_en > NOW()');
        
        // Tokens expirados
        $stats['expirados'] = $this->count('expira_en < NOW() AND usado = 0');
        
        // Tokens usados
        $stats['usados'] = $this->count('usado = 1');
        
        // Por tipo
        $stmt = $this->query("
            SELECT tipo, COUNT(*) as cantidad
            FROM tokens_verificacion 
            GROUP BY tipo
        ");
        $stats['por_tipo'] = $stmt->fetchAll();
        
        // Tokens generados últimos 30 días
        $stats['nuevos_mes'] = $this->count(
            'creado_en >= DATE_SUB(NOW(), INTERVAL 30 DAY)'
        );
        
        return $stats;
    }
    
    /**
     * Reenviar token de verificación
     */
    public function reenviarVerificacion($usuarioId, $email) {
        // Invalidar tokens anteriores de verificación de email
        $this->invalidarTokensUsuario($usuarioId, 'email_verification');
        
        // Generar nuevo token
        return $this->generarToken($usuarioId, $email, 'email_verification');
    }
}
?>