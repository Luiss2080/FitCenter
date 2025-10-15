<?php
/**
 * Servicio de Email para FitCenter
 * Configuración dinámica desde base de datos
 */

require_once dirname(__DIR__) . '/config/conexion.php';

class EmailService {
    private $config;
    private $logFile;
    private $pdo;
    
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
        $this->config = $this->getEmailConfig();
        $this->logFile = dirname(__DIR__) . '/logs/emails.log';
        
        // Crear carpeta de logs si no existe
        $logDir = dirname($this->logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
    }
    
    /**
     * Configuración de email desde base de datos
     */
    private function getEmailConfig() {
        try {
            $stmt = $this->pdo->prepare("
                SELECT clave, valor 
                FROM configuracion_sistema 
                WHERE categoria = 'email' OR categoria = 'general'
                AND activo = 1
            ");
            $stmt->execute();
            $configuraciones = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
            
            return [
                'smtp_host' => $configuraciones['email_smtp_host'] ?? 'smtp.gmail.com',
                'smtp_port' => $configuraciones['email_smtp_port'] ?? '587',
                'smtp_secure' => $configuraciones['email_smtp_secure'] ?? 'tls',
                'from_email' => $configuraciones['email_from_address'] ?? 'noreply@fitcenter.com',
                'from_name' => $configuraciones['email_from_name'] ?? 'FitCenter - Sistema de Gestión',
                'base_url' => $configuraciones['sistema_url_base'] ?? 'http://localhost/FitCenter',
                'sistema_nombre' => $configuraciones['sistema_nombre'] ?? 'FitCenter',
                'reset_expiry_hours' => (int)($configuraciones['reset_password_expiry_hours'] ?? 2),
                'verification_expiry_hours' => (int)($configuraciones['email_verification_expiry_hours'] ?? 24)
            ];
        } catch (PDOException $e) {
            // Fallback en caso de error de BD
            return [
                'smtp_host' => 'smtp.gmail.com',
                'smtp_port' => '587',
                'smtp_secure' => 'tls',
                'from_email' => 'noreply@fitcenter.com',
                'from_name' => 'FitCenter - Sistema de Gestión',
                'base_url' => 'http://localhost/FitCenter',
                'sistema_nombre' => 'FitCenter',
                'reset_expiry_hours' => 2,
                'verification_expiry_hours' => 24
            ];
        }
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
            'asunto' => 'Verifica tu cuenta en ' . $this->config['sistema_nombre'],
            'enlace' => $enlaceVerificacion,
            'token' => $token,
            'fecha' => date('Y-m-d H:i:s'),
            'expira_horas' => $this->config['verification_expiry_hours']
        ];
        
        $this->logEmail($mensaje);
        
        // En desarrollo, siempre retorna true
        // En producción, aquí iría el código real de PHPMailer/SMTP
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
            'asunto' => 'Recuperar contraseña - ' . $this->config['sistema_nombre'],
            'enlace' => $enlaceRecuperacion,
            'token' => $token,
            'fecha' => date('Y-m-d H:i:s'),
            'expira_horas' => $this->config['reset_expiry_hours']
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
            $log .= "Bienvenido a " . $this->config['sistema_nombre'] . "!\n\n";
            $log .= "Para verificar tu cuenta, haz clic en:\n";
            $log .= $mensaje['enlace'] . "\n\n";
            $log .= "Este enlace expira en " . $mensaje['expira_horas'] . " horas.\n\n";
            $log .= "Saludos,\nEquipo " . $this->config['sistema_nombre'] . "\n";
        } else {
            $log .= "CONTENIDO DEL EMAIL:\n";
            $log .= "Hola " . $mensaje['nombre'] . ",\n\n";
            $log .= "Para restablecer tu contraseña en " . $this->config['sistema_nombre'] . ", haz clic en:\n";
            $log .= $mensaje['enlace'] . "\n\n";
            $log .= "Este enlace expira en " . $mensaje['expira_horas'] . " horas.\n\n";
            $log .= "Si no solicitaste este cambio, puedes ignorar este email.\n\n";
            $log .= "Saludos,\nEquipo " . $this->config['sistema_nombre'] . "\n";
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