<?php
define('CLI_MODE', true);
require_once 'config/config.php';

echo "🔍 VERIFICANDO ESTRUCTURA DE TABLAS\n";
echo "===================================\n\n";

try {
    // Verificar estructura de tabla usuarios
    echo "📋 Estructura de la tabla 'usuarios':\n";
    $stmt = $pdo->query("DESCRIBE usuarios");
    $columns = $stmt->fetchAll();
    
    foreach ($columns as $column) {
        echo "  - {$column['Field']} ({$column['Type']}) - {$column['Null']} - {$column['Key']}\n";
    }
    
    echo "\n";
    
    // Verificar otras tablas
    $tablas = ['tokens_verificacion', 'configuracion_sistema', 'log_actividades'];
    
    foreach ($tablas as $tabla) {
        echo "📋 Estructura de la tabla '$tabla':\n";
        $stmt = $pdo->query("DESCRIBE $tabla");
        $columns = $stmt->fetchAll();
        
        foreach ($columns as $column) {
            echo "  - {$column['Field']} ({$column['Type']})\n";
        }
        echo "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>