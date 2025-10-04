<?php
/**
 * Servicio de Email Simplificado para CareCenter
 * Para desarrollo local - Simula envío de emails
 */

class EmailService {
    private $config;
    private $logFile;
    
    public function __construct() {
        $this->config = $this->getEmailConfig();
        $this->logFile = dirname(__DIR__) . '/logs/emails.log';
        
        // Crear carpeta de logs si no existe
        $logDir = dirname($this->logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
    }
    
    /**
     * Configuración de email
     */
    private function getEmailConfig() {
        return [
            'from_email' => 'noreply@carecenter.com',
            'from_name' => 'CareCenter Sistema',
            'base_url' => 'http://localhost/care_center'
        ];
    }
    
    /**
     * Enviar email de verificación (simulado)
     */
    public function enviarVerificacionEmail($email, $nombre, $token) {
        $enlaceVerificacion = $this->config['base_url'] . "/view/auth/verificar_email.php?token=" . $token;
        
        $mensaje = [
            'tipo' => 'verificacion_email',
            'para' => $email,
            'nombre' => $nombre,
            'asunto' => 'Verifica tu cuenta en CareCenter',
            'enlace' => $enlaceVerificacion,
            'token' => $token,
            'fecha' => date('Y-m-d H:i:s')
        ];
        
        $this->logEmail($mensaje);
        
        // En desarrollo, siempre retorna true
        // En producción, aquí iría el código real de PHPMailer
        return true;
    }
    
    /**
     * Enviar email de recuperación de contraseña (simulado)
     */
    public function enviarRecuperacionPassword($email, $nombre, $token) {
        $enlaceRecuperacion = $this->config['base_url'] . "/view/auth/reset_password.php?token=" . $token;
        
        $mensaje = [
            'tipo' => 'reset_password',
            'para' => $email,
            'nombre' => $nombre,
            'asunto' => 'Recuperar contraseña - CareCenter',
            'enlace' => $enlaceRecuperacion,
            'token' => $token,
            'fecha' => date('Y-m-d H:i:s')
        ];
        
        $this->logEmail($mensaje);
        
        // En desarrollo, siempre retorna true
        return true;
    }
    
    /**
     * Registrar email en log para desarrollo
     */
    private function logEmail($mensaje) {
        $log = "\n" . str_repeat("=", 60) . "\n";
        $log .= "📧 EMAIL ENVIADO - " . strtoupper($mensaje['tipo']) . "\n";
        $log .= str_repeat("=", 60) . "\n";
        $log .= "Para: " . $mensaje['para'] . "\n";
        $log .= "Nombre: " . $mensaje['nombre'] . "\n";
        $log .= "Asunto: " . $mensaje['asunto'] . "\n";
        $log .= "Fecha: " . $mensaje['fecha'] . "\n";
        $log .= "Token: " . $mensaje['token'] . "\n";
        $log .= "Enlace: " . $mensaje['enlace'] . "\n";
        $log .= str_repeat("-", 60) . "\n";
        
        if ($mensaje['tipo'] === 'verificacion_email') {
            $log .= "CONTENIDO DEL EMAIL:\n";
            $log .= "Hola " . $mensaje['nombre'] . ",\n\n";
            $log .= "Para verificar tu cuenta en CareCenter, haz clic en:\n";
            $log .= $mensaje['enlace'] . "\n\n";
            $log .= "Este enlace expira en 24 horas.\n\n";
            $log .= "Saludos,\nEquipo CareCenter\n";
        } else {
            $log .= "CONTENIDO DEL EMAIL:\n";
            $log .= "Hola " . $mensaje['nombre'] . ",\n\n";
            $log .= "Para restablecer tu contraseña, haz clic en:\n";
            $log .= $mensaje['enlace'] . "\n\n";
            $log .= "Este enlace expira en 1 hora.\n\n";
            $log .= "Saludos,\nEquipo CareCenter\n";
        }
        
        $log .= str_repeat("=", 60) . "\n";
        
        file_put_contents($this->logFile, $log, FILE_APPEND | LOCK_EX);
    }
    
    /**
     * Probar servicio (siempre exitoso en desarrollo)
     */
    public function probarConexion() {
        return true;
    }
    
    /**
     * Obtener últimos emails enviados (para desarrollo)
     */
    public function obtenerUltimosEmails($cantidad = 5) {
        if (!file_exists($this->logFile)) {
            return [];
        }
        
        $contenido = file_get_contents($this->logFile);
        $emails = explode("📧 EMAIL ENVIADO", $contenido);
        
        // Remover el primer elemento vacío
        array_shift($emails);
        
        // Obtener los últimos N emails
        $emails = array_slice($emails, -$cantidad);
        
        return array_reverse($emails);
    }
    
    /**
     * Limpiar log de emails
     */
    public function limpiarLogEmails() {
        if (file_exists($this->logFile)) {
            return unlink($this->logFile);
        }
        return true;
    }
}
?>