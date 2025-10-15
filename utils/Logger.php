<?php
/**
 * Clase Logger para FitCenter
 * Manejo de logs de la aplicación y actividades de usuarios
 */

require_once dirname(__DIR__) . '/config/conexion.php';

class Logger {
    private static $logPath;
    private static $logFile;
    private static $pdo;
    private static $levels = [
        'DEBUG' => 0,
        'INFO' => 1,
        'WARNING' => 2,
        'ERROR' => 3,
        'CRITICAL' => 4
    ];
    
    public static function init() {
        self::$logPath = defined('LOGS_PATH') ? LOGS_PATH : __DIR__ . '/../logs';
        self::$logFile = self::$logPath . '/app.log';
        
        // Crear directorio de logs si no existe
        if (!is_dir(self::$logPath)) {
            mkdir(self::$logPath, 0755, true);
        }
        
        // Obtener conexión PDO
        global $pdo;
        self::$pdo = $pdo;
    }
    
    public static function debug($mensaje, $contexto = []) {
        self::log('DEBUG', $mensaje, $contexto);
    }
    
    public static function info($mensaje, $contexto = []) {
        self::log('INFO', $mensaje, $contexto);
    }
    
    public static function warning($mensaje, $contexto = []) {
        self::log('WARNING', $mensaje, $contexto);
    }
    
    public static function error($mensaje, $contexto = []) {
        self::log('ERROR', $mensaje, $contexto);
    }
    
    public static function critical($mensaje, $contexto = []) {
        self::log('CRITICAL', $mensaje, $contexto);
    }
    
    private static function log($nivel, $mensaje, $contexto = []) {
        // Inicializar si no se ha hecho
        if (self::$logPath === null) {
            self::init();
        }
        
        // Verificar nivel mínimo
        $nivelMinimo = defined('LOG_LEVEL') ? strtoupper(LOG_LEVEL) : 'INFO';
        if (self::$levels[$nivel] < self::$levels[$nivelMinimo]) {
            return;
        }
        
        // Formatear mensaje
        $timestamp = date('Y-m-d H:i:s');
        $ip = self::obtenerIpCliente();
        $usuario = self::obtenerUsuarioActual();
        $url = self::obtenerUrlActual();
        
        $logEntry = sprintf(
            "[%s] %s: %s | IP: %s | Usuario: %s | URL: %s",
            $timestamp,
            $nivel,
            $mensaje,
            $ip,
            $usuario,
            $url
        );
        
        // Agregar contexto si existe
        if (!empty($contexto)) {
            $logEntry .= " | Contexto: " . json_encode($contexto, JSON_UNESCAPED_UNICODE);
        }
        
        $logEntry .= PHP_EOL;
        
        // Escribir al archivo
        self::escribirArchivo($logEntry);
        
        // Si es error crítico, también enviar notificación
        if ($nivel === 'CRITICAL') {
            self::notificarErrorCritico($mensaje, $contexto);
        }
    }
    
    private static function escribirArchivo($contenido) {
        try {
            // Verificar tamaño del archivo (rotación)
            if (file_exists(self::$logFile) && filesize(self::$logFile) > 10 * 1024 * 1024) { // 10MB
                self::rotarArchivo();
            }
            
            file_put_contents(self::$logFile, $contenido, FILE_APPEND | LOCK_EX);
            
        } catch (Exception $e) {
            // En caso de error escribiendo logs, usar error_log nativo
            error_log("Logger Error: " . $e->getMessage());
        }
    }
    
    private static function rotarArchivo() {
        try {
            $backupFile = self::$logPath . '/app_' . date('Y-m-d_H-i-s') . '.log';
            rename(self::$logFile, $backupFile);
            
            // Limpiar logs antiguos (mantener solo últimos 30 días)
            self::limpiarLogsAntiguos();
            
        } catch (Exception $e) {
            error_log("Error rotando logs: " . $e->getMessage());
        }
    }
    
    private static function limpiarLogsAntiguos() {
        try {
            $archivos = glob(self::$logPath . '/app_*.log');
            $tiempoLimite = time() - (30 * 24 * 60 * 60); // 30 días
            
            foreach ($archivos as $archivo) {
                if (filemtime($archivo) < $tiempoLimite) {
                    unlink($archivo);
                }
            }
            
        } catch (Exception $e) {
            error_log("Error limpiando logs antiguos: " . $e->getMessage());
        }
    }
    
    private static function obtenerIpCliente() {
        // Verificar diferentes headers para obtener la IP real
        $headers = [
            'HTTP_CF_CONNECTING_IP',     // Cloudflare
            'HTTP_CLIENT_IP',            // Proxy
            'HTTP_X_FORWARDED_FOR',      // Load Balancer/Proxy
            'HTTP_X_FORWARDED',          // Proxy
            'HTTP_X_CLUSTER_CLIENT_IP',  // Cluster
            'HTTP_FORWARDED_FOR',        // Proxy
            'HTTP_FORWARDED',            // Proxy
            'REMOTE_ADDR'                // Standard
        ];
        
        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ip = $_SERVER[$header];
                // Si hay múltiples IPs, tomar la primera
                if (strpos($ip, ',') !== false) {
                    $ip = trim(explode(',', $ip)[0]);
                }
                // Validar que sea una IP válida
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    }
    
    private static function obtenerUsuarioActual() {
        if (session_status() === PHP_SESSION_ACTIVE && isset($_SESSION['usuario_id'])) {
            return $_SESSION['usuario_id'] . '(' . ($_SESSION['usuario_nombre'] ?? 'Sin nombre') . ')';
        }
        return 'Anónimo';
    }
    
    private static function obtenerUrlActual() {
        if (!isset($_SERVER['REQUEST_METHOD'])) {
            return 'CLI';
        }
        
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        
        return $_SERVER['REQUEST_METHOD'] . ' ' . $protocol . '://' . $host . $uri;
    }
    
    private static function notificarErrorCritico($mensaje, $contexto) {
        // Implementar notificación por email o webhook para errores críticos
        try {
            // Por ahora solo log en el sistema
            error_log("CRITICAL ERROR en CareCenter: " . $mensaje . " | Contexto: " . json_encode($contexto));
            
            // TODO: Implementar envío de email o notificación Slack
            // Mailer::enviarNotificacionCritica($mensaje, $contexto);
            
        } catch (Exception $e) {
            error_log("Error enviando notificación crítica: " . $e->getMessage());
        }
    }
    
    /**
     * Obtener logs recientes
     */
    public static function obtenerLogsRecientes($lineas = 100) {
        try {
            if (!file_exists(self::$logFile)) {
                return [];
            }
            
            $logs = [];
            $handle = fopen(self::$logFile, 'r');
            
            if ($handle) {
                // Leer desde el final del archivo
                fseek($handle, -1, SEEK_END);
                $pos = ftell($handle);
                $line = '';
                $lines = [];
                
                while ($pos >= 0 && count($lines) < $lineas) {
                    fseek($handle, $pos, SEEK_SET);
                    $char = fgetc($handle);
                    
                    if ($char === "\n" || $pos === 0) {
                        if (trim($line) !== '') {
                            $lines[] = strrev($line);
                        }
                        $line = '';
                    } else {
                        $line .= $char;
                    }
                    
                    $pos--;
                }
                
                fclose($handle);
                
                return array_reverse($lines);
            }
            
            return [];
            
        } catch (Exception $e) {
            error_log("Error obteniendo logs recientes: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Limpiar todos los logs
     */
    public static function limpiarLogs() {
        try {
            if (file_exists(self::$logFile)) {
                unlink(self::$logFile);
            }
            
            $archivos = glob(self::$logPath . '/app_*.log');
            foreach ($archivos as $archivo) {
                unlink($archivo);
            }
            
            self::info('Logs limpiados manualmente');
            return true;
            
        } catch (Exception $e) {
            error_log("Error limpiando logs: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Registrar actividad de usuario en la base de datos
     */
    public static function registrarActividad($usuarioId, $email, $accion, $descripcion, $tipoEvento, $resultado = 'exitoso', $datosAdicionales = null) {
        if (self::$pdo === null) {
            self::init();
        }
        
        try {
            $stmt = self::$pdo->prepare("
                INSERT INTO log_actividades 
                (usuario_id, email, accion, descripcion, ip_address, user_agent, datos_adicionales, tipo_evento, resultado) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $ipAddress = self::obtenerIpCliente();
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
            $datosJson = $datosAdicionales ? json_encode($datosAdicionales, JSON_UNESCAPED_UNICODE) : null;
            
            return $stmt->execute([
                $usuarioId,
                $email,
                $accion,
                $descripcion,
                $ipAddress,
                $userAgent,
                $datosJson,
                $tipoEvento,
                $resultado
            ]);
            
        } catch (PDOException $e) {
            self::error('Error registrando actividad: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Métodos específicos para actividades del sistema FitCenter
     */
    
    public static function registroUsuario($usuarioId, $email, $nombre, $rol = 'cliente') {
        return self::registrarActividad(
            $usuarioId,
            $email,
            'Registro de cuenta',
            "Usuario registrado: $nombre (rol: $rol)",
            'registro',
            'exitoso',
            ['nombre' => $nombre, 'rol' => $rol]
        );
    }

    public static function loginExitoso($usuarioId, $email, $nombre) {
        return self::registrarActividad(
            $usuarioId,
            $email,
            'Inicio de sesión',
            "Login exitoso: $nombre",
            'login',
            'exitoso',
            ['nombre' => $nombre]
        );
    }

    public static function loginFallido($email, $motivo = 'Credenciales incorrectas') {
        return self::registrarActividad(
            null,
            $email,
            'Intento de login fallido',
            "Login fallido para $email: $motivo",
            'login',
            'fallido',
            ['motivo' => $motivo]
        );
    }

    public static function logoutUsuario($usuarioId, $email, $nombre) {
        return self::registrarActividad(
            $usuarioId,
            $email,
            'Cierre de sesión',
            "Logout: $nombre",
            'logout',
            'exitoso',
            ['nombre' => $nombre]
        );
    }

    public static function solicitudResetPassword($usuarioId, $email, $nombre) {
        return self::registrarActividad(
            $usuarioId,
            $email,
            'Solicitud reset contraseña',
            "Solicitud de reset de contraseña: $nombre",
            'password_reset',
            'pendiente',
            ['nombre' => $nombre]
        );
    }

    public static function resetPasswordExitoso($usuarioId, $email, $nombre) {
        return self::registrarActividad(
            $usuarioId,
            $email,
            'Contraseña cambiada',
            "Reset de contraseña exitoso: $nombre",
            'password_reset',
            'exitoso',
            ['nombre' => $nombre]
        );
    }

    public static function verificacionEmail($usuarioId, $email, $nombre) {
        return self::registrarActividad(
            $usuarioId,
            $email,
            'Verificación de email',
            "Email verificado exitosamente: $nombre",
            'email_verification',
            'exitoso',
            ['nombre' => $nombre]
        );
    }

    public static function accionAdmin($usuarioId, $email, $accion, $descripcion, $datosAdicionales = null) {
        return self::registrarActividad(
            $usuarioId,
            $email,
            $accion,
            $descripcion,
            'admin_action',
            'exitoso',
            $datosAdicionales
        );
    }
}

// Inicializar logger
Logger::init();