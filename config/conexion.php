<?php
/**
 * Conexión a la base de datos FitCenter
 */

// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'fitcenter_gym');
define('DB_USER', 'root');
define('DB_PASS', '');

// Crear conexión PDO
try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4', 
        DB_USER, 
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    die('Error de conexión a la base de datos: ' . $e->getMessage());
}
?>