<?php
/**
 * Configuración principal de FitCenter
 */

// Configuración básica de la aplicación
define('APP_NAME', 'FitCenter');
define('APP_VERSION', '1.0.0');
define('APP_DESCRIPTION', 'Sistema de gestión integral para gimnasios');
define('APP_URL', 'http://localhost/FitCenter');

// Zonas horarias
date_default_timezone_set('America/Mexico_City');

// Rutas básicas
define('BASE_URL', '/FitCenter');
define('UPLOADS_PATH', 'public/uploads');
define('LOGS_PATH', 'logs');

// Configuración de errores (cambiar a false en producción)
define('DEBUG_MODE', true);

if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Configuración de sesiones (solo para web)
if (!defined('CLI_MODE')) {
    ini_set('session.gc_maxlifetime', 7200); // 2 horas
    ini_set('session.cookie_lifetime', 7200);
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

// Configuración de email
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', ''); // Configurar según necesidades
define('SMTP_PASSWORD', ''); // Configurar según necesidades
define('FROM_EMAIL', 'noreply@fitcenter.com');
define('FROM_NAME', 'FitCenter');

// Configuración de seguridad
define('HASH_ALGO', 'sha256');
define('TOKEN_EXPIRY', 3600); // 1 hora en segundos

// Configuración de archivos
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif']);
define('ALLOWED_DOC_TYPES', ['pdf', 'doc', 'docx', 'txt']);

// Incluir conexión a base de datos
require_once __DIR__ . '/conexion.php';
?>