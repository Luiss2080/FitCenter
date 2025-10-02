<?php
/**
 * Constantes básicas para CareCenter
 */

// Configuración básica de la aplicación
define('APP_NAME', 'CareCenter');
define('APP_VERSION', '1.0.0');

// Zonas horarias
date_default_timezone_set('America/Mexico_City');

// Rutas básicas
define('BASE_URL', '/care_center');
define('UPLOADS_PATH', 'public/uploads');

// Configuración de errores (cambiar a false en producción)
define('DEBUG_MODE', true);

if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}
?>