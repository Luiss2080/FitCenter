<?php
/**
 * Constantes básicas para FitCenter
 */

// Configuración básica de la aplicación
define('APP_NAME', 'FitCenter');
define('APP_VERSION', '1.0.0');
define('APP_DESCRIPTION', 'Sistema de gestión integral para gimnasios');

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