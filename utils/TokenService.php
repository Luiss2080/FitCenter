<?php
/**
 * Clase para manejo de tokens de verificación
 * CareCenter - Sistema de gestión nutricional
 */

class TokenService {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
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
        $expira = date('Y-m-d H:i:s', strtotime('+24 hours'));
        
        $stmt = $this->pdo->prepare("
            INSERT INTO tokens_verificacion (id_usuario, token, tipo, email, expira_en) 
            VALUES (?, ?, 'verificacion_email', ?, ?)
        ");
        
        if ($stmt->execute([$idUsuario, $token, $email, $expira])) {
            return $token;
        }
        
        return false;
    }
    
    /**
     * Crear token de recuperación de contraseña
     */
    public function crearTokenRecuperacion($idUsuario, $email) {
        $token = $this->generarToken();
        $expira = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        $stmt = $this->pdo->prepare("
            INSERT INTO tokens_verificacion (id_usuario, token, tipo, email, expira_en) 
            VALUES (?, ?, 'reset_password', ?, ?)
        ");
        
        if ($stmt->execute([$idUsuario, $token, $email, $expira])) {
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
            SET usado = 1, fecha_uso = NOW() 
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
        $stmt = $this->pdo->prepare("UPDATE usuarios SET email_verificado = 1 WHERE id_usuario = ?");
        return $stmt->execute([$idUsuario]);
    }
    
    /**
     * Actualizar contraseña de usuario
     */
    public function actualizarPassword($idUsuario, $nuevaPassword) {
        $hashPassword = password_hash($nuevaPassword, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("UPDATE usuarios SET password = ? WHERE id_usuario = ?");
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