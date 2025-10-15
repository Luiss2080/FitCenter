<?php
/**
 * Script de prueba de conexión y verificación de base de datos FitCenter
 */

// Definir que estamos en modo CLI
define('CLI_MODE', true);

echo "🗄️ PRUEBA DE CONEXIÓN A BASE DE DATOS FITCENTER\n";
echo "===============================================\n\n";

// Incluir configuración
require_once __DIR__ . '/../config/config.php';

try {
    echo "✅ Configuración cargada correctamente\n";
    echo "📊 Base de datos: " . DB_NAME . "\n";
    echo "🖥️ Servidor: " . DB_HOST . "\n";
    echo "👤 Usuario: " . DB_USER . "\n\n";

    // Verificar conexión
    echo "🔌 Probando conexión...\n";
    
    // La conexión ya se establece en config.php
    echo "✅ Conexión establecida exitosamente\n\n";

    // Verificar tablas
    echo "📋 Verificando tablas de la base de datos...\n";
    
    $tablas_requeridas = [
        'usuarios',
        'tokens_verificacion', 
        'configuracion_sistema',
        'log_actividades'
    ];

    foreach ($tablas_requeridas as $tabla) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$tabla'");
        if ($stmt->rowCount() > 0) {
            echo "✅ Tabla '$tabla' existe\n";
            
            // Contar registros
            $count = $pdo->query("SELECT COUNT(*) FROM $tabla")->fetchColumn();
            echo "   📊 Registros: $count\n";
        } else {
            echo "❌ Tabla '$tabla' NO existe\n";
        }
    }

    echo "\n";

    // Verificar usuarios de prueba
    echo "👥 Verificando usuarios de prueba...\n";
    
    $stmt = $pdo->query("SELECT id, email, nombre, rol, estado FROM usuarios ORDER BY id");
    $usuarios = $stmt->fetchAll();

    if (count($usuarios) > 0) {
        foreach ($usuarios as $usuario) {
            $estado_icon = $usuario['estado'] === 'activo' ? '✅' : '⚠️';
            echo "$estado_icon {$usuario['rol']}: {$usuario['email']} ({$usuario['nombre']})\n";
        }
    } else {
        echo "⚠️ No hay usuarios en la base de datos\n";
    }

    echo "\n";

    // Verificar log de actividades
    echo "📝 Verificando log de actividades...\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM log_actividades");
    $total_logs = $stmt->fetchColumn();
    echo "📊 Total de actividades registradas: $total_logs\n";

    if ($total_logs > 0) {
        $stmt = $pdo->query("
            SELECT email, accion, tipo_evento, resultado, creado_en 
            FROM log_actividades 
            ORDER BY creado_en DESC 
            LIMIT 5
        ");
        $actividades = $stmt->fetchAll();
        
        echo "🕒 Últimas 5 actividades:\n";
        foreach ($actividades as $actividad) {
            $resultado_icon = $actividad['resultado'] === 'exitoso' ? '✅' : '⚠️';
            echo "   $resultado_icon {$actividad['email']}: {$actividad['accion']} ({$actividad['creado_en']})\n";
        }
    }

    echo "\n";

    // Verificar configuración del sistema
    echo "⚙️ Verificando configuración del sistema...\n";
    
    $stmt = $pdo->query("SELECT clave, valor FROM configuracion_sistema");
    $configuraciones = $stmt->fetchAll();

    if (count($configuraciones) > 0) {
        foreach ($configuraciones as $config) {
            echo "✅ {$config['clave']}: {$config['valor']}\n";
        }
    } else {
        echo "⚠️ No hay configuraciones del sistema\n";
    }

    echo "\n";

    // Verificar tokens de verificación
    echo "🔑 Verificando tokens de verificación...\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM tokens_verificacion WHERE usado = 0 AND expira_en > NOW()");
    $tokens_activos = $stmt->fetchColumn();
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM tokens_verificacion");
    $total_tokens = $stmt->fetchColumn();
    
    echo "📊 Tokens totales: $total_tokens\n";
    echo "🔓 Tokens activos: $tokens_activos\n";

    echo "\n✅ VERIFICACIÓN COMPLETADA EXITOSAMENTE\n";
    echo "=======================================\n";

} catch (PDOException $e) {
    echo "❌ ERROR DE CONEXIÓN: " . $e->getMessage() . "\n";
    echo "\n🔧 POSIBLES SOLUCIONES:\n";
    echo "1. Verificar que XAMPP esté ejecutándose\n";
    echo "2. Verificar que MySQL esté activo\n";
    echo "3. Confirmar que la base de datos 'fitcenter' existe\n";
    echo "4. Verificar credenciales en config/conexion.php\n";
    
} catch (Exception $e) {
    echo "❌ ERROR GENERAL: " . $e->getMessage() . "\n";
}
?>