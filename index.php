<?php
/**
 * Punto de entrada principal de CareCenter
 */

// Cargar bootstrap de la aplicación
require_once __DIR__ . '/../config/bootstrap.php';
require_once __DIR__ . '/../config/rutas.php';

try {
    // Obtener método y ruta de la petición
    $method = $_SERVER['REQUEST_METHOD'];
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    
    // Remover el directorio base si existe
    $baseDir = '/care_center';
    if (strpos($path, $baseDir) === 0) {
        $path = substr($path, strlen($baseDir));
    }
    
    // Si no hay ruta específica, redirigir a home
    if ($path === '/' || $path === '') {
        $path = '/';
    }
    
    // Crear router y resolver ruta
    $router = new Router();
    $router->resolve($method, $path);
    
} catch (Exception $e) {
    // Log del error
    Logger::error('Error en router principal: ' . $e->getMessage(), [
        'method' => $method ?? 'unknown',
        'path' => $path ?? 'unknown',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
    ]);
    
    // Mostrar página de error apropiada
    http_response_code(500);
    
    if (APP_ENV === 'development') {
        echo "<h1>Error 500 - Error interno del servidor</h1>";
        echo "<p><strong>Mensaje:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<p><strong>Archivo:</strong> " . htmlspecialchars($e->getFile()) . "</p>";
        echo "<p><strong>Línea:</strong> " . $e->getLine() . "</p>";
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    } else {
        include __DIR__ . '/../vistas/errores/500.php';
    }
}