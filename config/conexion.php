<?php
/**
 * Conexión a la base de datos FitCenter
 */

// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'fitcenter');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Crear conexión PDO
try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET, 
        DB_USER, 
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET
        ]
    );
    
    // Configurar zona horaria de MySQL
    $pdo->exec("SET time_zone = '-06:00'");
    
} catch (PDOException $e) {
    // Log del error
    error_log('Error de conexión a BD: ' . $e->getMessage());
    
    // Mostrar error amigable
    if (defined('DEBUG_MODE') && DEBUG_MODE) {
        die('Error de conexión a la base de datos: ' . $e->getMessage());
    } else {
        die('Error de conexión a la base de datos. Contacte al administrador.');
    }
}

// Función para obtener una nueva conexión (útil para transacciones)
function getNewConnection() {
    try {
        return new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET, 
            DB_USER, 
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET
            ]
        );
    } catch (PDOException $e) {
        throw new Exception('Error al crear nueva conexión: ' . $e->getMessage());
    }
}
?>