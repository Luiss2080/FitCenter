<?php
/**
 * Clase para manejo de tokens de verificación
 * FitCenter - Sistema de gestión para gimnasios
 */

require_once dirname(__DIR__) . '/config/conexion.php';

class TokenService {
    private $pdo;
    private $config;
    
    public function __construct($pdo = null) {
        if ($pdo === null) {
            global $pdo;
            $this->pdo = $pdo;
        } else {
            $this->pdo = $pdo;
        }
        $this->config = $this->getConfiguracion();
    }
    
    /**
     * Obtener configuración desde base de datos
     */
    private function getConfiguracion() {
        try {
            $stmt = $this->pdo->prepare("
                SELECT clave, valor 
                FROM configuracion_sistema 
                WHERE categoria = 'seguridad' AND activo = 1
            ");
            $stmt->execute();
            $configuraciones = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
            
            return [
                'reset_expiry_hours' => (int)($configuraciones['reset_password_expiry_hours'] ?? 2),
                'verification_expiry_hours' => (int)($configuraciones['email_verification_expiry_hours'] ?? 24)
            ];
        } catch (PDOException $e) {
            return [
                'reset_expiry_hours' => 2,
                'verification_expiry_hours' => 24
            ];
        }
    }
    
    /**
     * Generar token único
     */
    public function generarToken() {
        return bin2hex(random_bytes(32));
    }
    
    /**
     * Crear token de verificación de email
     */
    public function crearTokenVerificacion($idUsuario, $email) {
        $token = $this->generarToken();
        $expiraHoras = '+' . $this->config['verification_expiry_hours'] . ' hours';
        $expira = date('Y-m-d H:i:s', strtotime($expiraHoras));
        
        $stmt = $this->pdo->prepare("
            INSERT INTO tokens_verificacion (usuario_id, email, token, tipo, expira_en) 
            VALUES (?, ?, ?, 'verificacion_email', ?)
        ");
        
        if ($stmt->execute([$idUsuario, $email, $token, $expira])) {
            return $token;
        }
        
        return false;
    }
    
    /**
     * Crear token de recuperación de contraseña
     */
    public function crearTokenRecuperacion($idUsuario, $email) {
        $token = $this->generarToken();
        $expiraHoras = '+' . $this->config['reset_expiry_hours'] . ' hours';
        $expira = date('Y-m-d H:i:s', strtotime($expiraHoras));
        
        $stmt = $this->pdo->prepare("
            INSERT INTO tokens_verificacion (usuario_id, email, token, tipo, expira_en) 
            VALUES (?, ?, ?, 'reset_password', ?)
        ");
        
        if ($stmt->execute([$idUsuario, $email, $token, $expira])) {
            return $token;
        }
        
        return false;
    }
    
    /**
     * Validar token
     */
    public function validarToken($token, $tipo) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM tokens_verificacion 
            WHERE token = ? AND tipo = ? AND usado = 0 AND expira_en > NOW()
        ");
        
        $stmt->execute([$token, $tipo]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Marcar token como usado
     */
    public function marcarTokenUsado($token) {
        $stmt = $this->pdo->prepare("
            UPDATE tokens_verificacion 
            SET usado = 1 
            WHERE token = ?
        ");
        
        return $stmt->execute([$token]);
    }
    
    /**
     * Limpiar tokens expirados
     */
    public function limpiarTokensExpirados() {
        $stmt = $this->pdo->prepare("DELETE FROM tokens_verificacion WHERE expira_en < NOW()");
        return $stmt->execute();
    }
    
    /**
     * Verificar email de usuario
     */
    public function verificarEmailUsuario($idUsuario) {
        $stmt = $this->pdo->prepare("UPDATE usuarios SET email_verificado = 1 WHERE id = ?");
        return $stmt->execute([$idUsuario]);
    }
    
    /**
     * Actualizar contraseña de usuario
     */
    public function actualizarPassword($idUsuario, $nuevaPassword) {
        $hashPassword = password_hash($nuevaPassword, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
        return $stmt->execute([$hashPassword, $idUsuario]);
    }
    
    /**
     * Obtener usuario por email
     */
    public function obtenerUsuarioPorEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>