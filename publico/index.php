<?php
/**
 * Punto de entrada principal de CareCenter
 * Inicialización y ruteo de la aplicación
 */

// ====================================================================
// INICIALIZACIÓN
// ====================================================================

// Cargar bootstrap de la aplicación
require_once __DIR__ . '/../config/bootstrap.php';

// Cargar router
require_once __DIR__ . '/router.php';

// ====================================================================
// MANEJO DE PETICIONES
// ====================================================================

try {
    // Obtener método y ruta de la petición
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    $path = $_SERVER['REQUEST_URI'] ?? '/';
    
    // Log de petición (solo en modo debug)
    if (APP_DEBUG) {
        Logger::debug('Petición HTTP recibida', [
            'method' => $method,
            'path' => $path,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'referer' => $_SERVER['HTTP_REFERER'] ?? null
        ]);
    }
    
    // Manejar peticiones preflight de CORS
    if ($method === 'OPTIONS') {
        handleCorsPreflightRequest();
        exit;
    }
    
    // Verificar modo de mantenimiento
    if (env('MAINTENANCE_MODE', false) && !isMaintenanceBypass()) {
        showMaintenancePage();
        exit;
    }
    
    // Crear y ejecutar router
    $router = new Router();
    $router->resolve($method, $path);
    
} catch (Exception $e) {
    handleException($e);
} catch (Error $e) {
    handleException($e);
}

// ====================================================================
// FUNCIONES AUXILIARES
// ====================================================================

/**
 * Manejar peticiones preflight de CORS
 */
function handleCorsPreflightRequest() {
    $allowedOrigins = env('CORS_ALLOWED_ORIGINS', '*');
    $allowedMethods = env('CORS_ALLOWED_METHODS', 'GET,POST,PUT,DELETE,OPTIONS');
    $allowedHeaders = env('CORS_ALLOWED_HEADERS', '*');
    
    header("Access-Control-Allow-Origin: {$allowedOrigins}");
    header("Access-Control-Allow-Methods: {$allowedMethods}");
    header("Access-Control-Allow-Headers: {$allowedHeaders}");
    header("Access-Control-Max-Age: 86400"); // 24 horas
    
    http_response_code(200);
}

/**
 * Verificar si se debe omitir el modo de mantenimiento
 */
function isMaintenanceBypass() {
    // IPs que pueden omitir el mantenimiento
    $bypassIps = ['127.0.0.1', '::1'];
    $clientIp = $_SERVER['REMOTE_ADDR'] ?? '';
    
    if (in_array($clientIp, $bypassIps)) {
        return true;
    }
    
    // Token especial para omitir mantenimiento
    $bypassToken = env('MAINTENANCE_BYPASS_TOKEN');
    if ($bypassToken && isset($_GET['bypass']) && $_GET['bypass'] === $bypassToken) {
        return true;
    }
    
    return false;
}

/**
 * Mostrar página de mantenimiento
 */
function showMaintenancePage() {
    http_response_code(503);
    header('Retry-After: 300'); // 5 minutos
    
    $maintenanceFile = VIEWS_PATH . '/mantenimiento.php';
    if (file_exists($maintenanceFile)) {
        include $maintenanceFile;
    } else {
        $message = env('MAINTENANCE_MESSAGE', 'Sistema en mantenimiento. Volveremos pronto.');
        echo "<!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Mantenimiento - CareCenter</title>
            <style>
                body { font-family: Arial, sans-serif; text-align: center; padding: 50px; background: #f8f9fa; }
                .container { max-width: 600px; margin: 0 auto; background: white; padding: 40px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                h1 { color: #007bff; margin-bottom: 20px; }
                p { color: #6c757d; line-height: 1.6; }
            </style>
        </head>
        <body>
            <div class='container'>
                <h1>🔧 Sistema en Mantenimiento</h1>
                <p>{$message}</p>
                <p><small>Por favor, inténtalo de nuevo en unos minutos.</small></p>
            </div>
        </body>
        </html>";
    }
}

/**
 * Manejar excepciones y errores
 */
function handleException($exception) {
    $method = $_SERVER['REQUEST_METHOD'] ?? 'unknown';
    $path = $_SERVER['REQUEST_URI'] ?? 'unknown';
    
    // Log del error
    Logger::error('Error fatal en aplicación: ' . $exception->getMessage(), [
        'method' => $method,
        'path' => $path,
        'file' => $exception->getFile(),
        'line' => $exception->getLine(),
        'trace' => $exception->getTraceAsString(),
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
    ]);
    
    // Responder según el tipo de petición
    if (isAjaxRequest()) {
        handleAjaxError($exception);
    } else {
        handleWebError($exception);
    }
}

/**
 * Verificar si es petición AJAX
 */
function isAjaxRequest() {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

/**
 * Manejar errores en peticiones AJAX
 */
function handleAjaxError($exception) {
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    
    $response = ['error' => 'Error interno del servidor'];
    
    if (APP_DEBUG) {
        $response['debug'] = [
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString()
        ];
    }
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}

/**
 * Manejar errores en peticiones web
 */
function handleWebError($exception) {
    http_response_code(500);
    
    if (APP_DEBUG) {
        // Mostrar error detallado en desarrollo
        echo "<!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Error 500 - CareCenter</title>
            <style>
                body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 20px; background: #f8f9fa; }
                .error-container { max-width: 1000px; margin: 0 auto; background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden; }
                .error-header { background: #dc3545; color: white; padding: 20px; }
                .error-body { padding: 20px; }
                .error-details { background: #f8f9fa; padding: 15px; border-radius: 4px; margin: 15px 0; }
                .error-trace { background: #212529; color: #f8f9fa; padding: 15px; border-radius: 4px; font-family: 'Courier New', monospace; font-size: 12px; overflow-x: auto; white-space: pre-wrap; }
                h1 { margin: 0; font-size: 24px; }
                h2 { color: #495057; margin-top: 25px; }
                .back-link { display: inline-block; margin-top: 20px; padding: 10px 15px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; }
            </style>
        </head>
        <body>
            <div class='error-container'>
                <div class='error-header'>
                    <h1>🚨 Error 500 - Error Interno del Servidor</h1>
                    <p>Se produjo un error inesperado en la aplicación</p>
                </div>
                <div class='error-body'>
                    <div class='error-details'>
                        <h2>Detalles del Error:</h2>
                        <p><strong>Mensaje:</strong> " . htmlspecialchars($exception->getMessage()) . "</p>
                        <p><strong>Archivo:</strong> " . htmlspecialchars($exception->getFile()) . "</p>
                        <p><strong>Línea:</strong> " . $exception->getLine() . "</p>
                        <p><strong>Fecha:</strong> " . date('Y-m-d H:i:s') . "</p>
                    </div>
                    
                    <h2>Stack Trace:</h2>
                    <div class='error-trace'>" . htmlspecialchars($exception->getTraceAsString()) . "</div>
                    
                    <a href='/' class='back-link'>← Volver al Inicio</a>
                </div>
            </div>
        </body>
        </html>";
    } else {
        // Mostrar página de error genérica en producción
        $errorFile = VIEWS_PATH . '/errores/500.php';
        if (file_exists($errorFile)) {
            include $errorFile;
        } else {
            echo "<!DOCTYPE html>
            <html lang='es'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Error 500 - CareCenter</title>
                <style>
                    body { font-family: Arial, sans-serif; text-align: center; padding: 50px; background: #f8f9fa; }
                    .container { max-width: 600px; margin: 0 auto; background: white; padding: 40px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                    h1 { color: #dc3545; margin-bottom: 20px; }
                    p { color: #6c757d; line-height: 1.6; }
                    a { color: #007bff; text-decoration: none; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <h1>⚠️ Error Interno del Servidor</h1>
                    <p>Lo sentimos, se produjo un error inesperado.</p>
                    <p>Nuestro equipo técnico ha sido notificado y está trabajando para resolver el problema.</p>
                    <p><a href='/'>← Volver al inicio</a></p>
                </div>
            </body>
            </html>";
        }
    }
}