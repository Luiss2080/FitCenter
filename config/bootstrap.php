<?php
/**
 * Bootstrap de la aplicación CareCenter
 * Inicialización de componentes principales y configuración del sistema
 */

// ====================================================================
// CONFIGURACIÓN INICIAL
// ====================================================================

// Prevenir acceso directo al archivo
if (!defined('CARECENTER_LOADED')) {
    define('CARECENTER_LOADED', true);
}

// ====================================================================
// CARGA DE VARIABLES DE ENTORNO
// ====================================================================

// Cargar Composer autoloader si existe
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
    
    // Cargar dotenv si está disponible
    if (class_exists('\\Dotenv\\Dotenv')) {
        try {
            /** @var \Dotenv\Dotenv $dotenv */
            $dotenv = call_user_func(['\\Dotenv\\Dotenv', 'createImmutable'], dirname(__DIR__));
            $dotenv->safeLoad();
        } catch (Exception $e) {
            // Silenciar errores de dotenv en caso de problemas
            error_log('Error cargando dotenv: ' . $e->getMessage());
        }
    }
} else {
    // Cargar .env manualmente si no hay Composer
    $envFile = dirname(__DIR__) . '/.env';
    if (file_exists($envFile)) {
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) continue;
            if (strpos($line, '=') !== false) {
                list($name, $value) = explode('=', $line, 2);
                $name = trim($name);
                $value = trim($value, " \t\n\r\0\x0B\"'");
                if (!array_key_exists($name, $_ENV)) {
                    $_ENV[$name] = $value;
                    $_SERVER[$name] = $value;
                }
            }
        }
    }
}

// ====================================================================
// CONFIGURACIÓN DE PHP
// ====================================================================

// Cargar constantes primero
require_once __DIR__ . '/constantes.php';

// Configurar timezone
date_default_timezone_set(APP_TIMEZONE);

// Configuración de errores según el entorno
if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    ini_set('log_errors', '1');
} else {
    error_reporting(E_ERROR | E_WARNING | E_PARSE);
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
}

// Configurar logs de PHP
ini_set('error_log', LOGS_PATH . '/php_errors.log');

// Configuración de memoria y tiempo
ini_set('memory_limit', '256M');
ini_set('max_execution_time', '60');

// Configuración de sesiones
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_secure', FORCE_HTTPS ? '1' : '0');
ini_set('session.use_strict_mode', '1');
ini_set('session.name', SESSION_COOKIE_NAME);
ini_set('session.gc_maxlifetime', SESSION_LIFETIME);

// ====================================================================
// CARGA DE COMPONENTES PRINCIPALES
// ====================================================================

// Cargar base de datos
require_once __DIR__ . '/database.php';

// Cargar gestión de sesiones
require_once __DIR__ . '/sesion.php';

// Cargar sistema de permisos
require_once __DIR__ . '/permisos.php';

// ====================================================================
// CARGA DE UTILIDADES
// ====================================================================

// Cargar utilidades principales
require_once UTILS_PATH . '/Logger.php';
require_once UTILS_PATH . '/Validador.php';

// Cargar modelo base
require_once MODELS_PATH . '/ModeloBase.php';

// ====================================================================
// CONFIGURACIÓN DE MANEJO DE ERRORES
// ====================================================================

// Manejador personalizado de errores
set_error_handler(function($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        return false;
    }
    
    $errorTypes = [
        E_ERROR => 'ERROR',
        E_WARNING => 'WARNING', 
        E_PARSE => 'PARSE ERROR',
        E_NOTICE => 'NOTICE',
        E_DEPRECATED => 'DEPRECATED'
    ];
    
    // Solo agregar E_STRICT si está definido (PHP < 8.0)
    if (defined('E_STRICT')) {
        $errorTypes[E_STRICT] = 'STRICT';
    }
    
    $errorType = $errorTypes[$severity] ?? 'UNKNOWN';
    
    Logger::error("PHP {$errorType}: {$message}", [
        'file' => $file,
        'line' => $line,
        'severity' => $severity
    ]);
    
    // En desarrollo, mostrar el error
    if (APP_DEBUG) {
        echo "<div style='background: #f8d7da; color: #721c24; padding: 10px; margin: 5px; border: 1px solid #f5c6cb; border-radius: 4px;'>";
        echo "<strong>PHP {$errorType}:</strong> {$message}<br>";
        echo "<small>Archivo: {$file} | Línea: {$line}</small>";
        echo "</div>";
    }
    
    return true;
});

// Manejador de excepciones no capturadas
set_exception_handler(function($exception) {
    Logger::critical('Excepción no capturada: ' . $exception->getMessage(), [
        'file' => $exception->getFile(),
        'line' => $exception->getLine(),
        'trace' => $exception->getTraceAsString()
    ]);
    
    if (APP_DEBUG) {
        echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; margin: 10px; border: 1px solid #f5c6cb; border-radius: 4px;'>";
        echo "<h3>Excepción Fatal</h3>";
        echo "<strong>Mensaje:</strong> " . htmlspecialchars($exception->getMessage()) . "<br>";
        echo "<strong>Archivo:</strong> " . htmlspecialchars($exception->getFile()) . "<br>";
        echo "<strong>Línea:</strong> " . $exception->getLine() . "<br>";
        echo "<pre>" . htmlspecialchars($exception->getTraceAsString()) . "</pre>";
        echo "</div>";
    } else {
        http_response_code(500);
        include VIEWS_PATH . '/errores/500.php';
    }
});

// ====================================================================
// INICIALIZACIÓN DE SESIÓN
// ====================================================================

// Inicializar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ====================================================================
// CONFIGURACIONES ADICIONALES
// ====================================================================

// Configurar headers de seguridad si no es CLI
if (!defined('STDIN')) {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    header('X-XSS-Protection: 1; mode=block');
    
    if (FORCE_HTTPS) {
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
    }
}

// ====================================================================
// LOG DE INICIO
// ====================================================================

// Registrar inicio de la aplicación
Logger::info('Aplicación CareCenter iniciada correctamente', [
    'timestamp' => date('Y-m-d H:i:s'),
    'environment' => APP_ENV,
    'version' => APP_VERSION,
    'php_version' => PHP_VERSION,
    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'CLI',
    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'CLI'
]);

// ====================================================================
// FUNCIONES AUXILIARES GLOBALES
// ====================================================================

if (!function_exists('redirect')) {
    function redirect($url, $statusCode = 302) {
        if (!headers_sent()) {
            header("Location: {$url}", true, $statusCode);
            exit;
        }
    }
}

if (!function_exists('abort')) {
    function abort($code = 404, $message = '') {
        http_response_code($code);
        $errorFile = VIEWS_PATH . "/errores/{$code}.php";
        if (file_exists($errorFile)) {
            include $errorFile;
        } else {
            echo $message ?: "Error {$code}";
        }
        exit;
    }
}

if (!function_exists('asset')) {
    function asset($path) {
        return ASSETS_URL . '/' . ltrim($path, '/');
    }
}