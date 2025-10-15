<?php
/**
 * Script de prueba para los modelos de FitCenter
 */

define('CLI_MODE', true);
require_once 'config/config.php';
require_once 'models/Usuario.php';
require_once 'models/LogActividad.php';
require_once 'models/TokenVerificacion.php';

echo "🧪 PRUEBA DE MODELOS FITCENTER\n";
echo "==============================\n\n";

try {
    // Probar modelo Usuario
    echo "👤 Probando modelo Usuario...\n";
    $usuarioModel = new Usuario();
    
    // Buscar usuario por email
    $admin = $usuarioModel->findByEmail('admin@fitcenter.com');
    if ($admin) {
        echo "✅ Usuario admin encontrado: {$admin['nombre']}\n";
    }
    
    // Obtener estadísticas
    $stats = $usuarioModel->getStats();
    echo "📊 Total usuarios: {$stats['total']}\n";
    echo "📊 Nuevos este mes: {$stats['nuevos_mes']}\n";
    
    echo "\n";
    
    // Probar modelo LogActividad
    echo "📝 Probando modelo LogActividad...\n";
    $logModel = new LogActividad();
    
    // Obtener actividades recientes
    $recientes = $logModel->getRecientes(3);
    echo "📋 Últimas 3 actividades:\n";
    foreach ($recientes as $actividad) {
        echo "  - {$actividad['email']}: {$actividad['accion']}\n";
    }
    
    // Obtener estadísticas
    $logStats = $logModel->getStats(30);
    echo "📊 Total actividades: {$logStats['total']}\n";
    
    echo "\n";
    
    // Probar modelo TokenVerificacion
    echo "🔑 Probando modelo TokenVerificacion...\n";
    $tokenModel = new TokenVerificacion();
    
    // Obtener estadísticas
    $tokenStats = $tokenModel->getStats();
    echo "📊 Total tokens: {$tokenStats['total']}\n";
    echo "📊 Tokens activos: {$tokenStats['activos']}\n";
    echo "📊 Tokens expirados: {$tokenStats['expirados']}\n";
    echo "📊 Tokens usados: {$tokenStats['usados']}\n";
    
    echo "\n";
    
    // Probar creación de nuevo log
    echo "🆕 Probando registro de nueva actividad...\n";
    $newLogId = $logModel->registrar(
        1, 
        'admin@fitcenter.com', 
        'Prueba de sistema', 
        'Verificación de modelos desde script CLI',
        '127.0.0.1',
        'admin_action',
        'exitoso'
    );
    
    if ($newLogId) {
        echo "✅ Nueva actividad registrada con ID: $newLogId\n";
    } else {
        echo "❌ Error al registrar nueva actividad\n";
    }
    
    echo "\n✅ PRUEBA DE MODELOS COMPLETADA EXITOSAMENTE\n";
    echo "============================================\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>