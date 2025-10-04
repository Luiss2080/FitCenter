<?php
/**
 * VERIFICACIÓN COMPLETA DE CONEXIONES DEL SISTEMA
 * =============================================
 * 
 * Este script verifica que todas las vistas estén conectadas correctamente
 */

echo "🔗 VERIFICACIÓN COMPLETA DE CONEXIONES\n";
echo "=====================================\n\n";

// URLs base del sistema
$base_url = "http://localhost/care_center";
$auth_views = [
    'index.php' => '/',
    'login.php' => '/view/auth/login.php',
    'registro.php' => '/view/auth/registro.php',
    'registro_exitoso.php' => '/view/auth/registro_exitoso.php',
    'verificar_email.php' => '/view/auth/verificar_email.php',
    'reenviar_verificacion.php' => '/view/auth/reenviar_verificacion.php',
    'recover_password_new.php' => '/view/auth/recover_password_new.php',
    'recuperacion_enviada.php' => '/view/auth/recuperacion_enviada.php',
    'reset_password.php' => '/view/auth/reset_password.php',
    'logout.php' => '/view/auth/logout.php',
    'dashboard.php' => '/view/admin/dashboard.php',
    'welcome.php' => '/view/home/welcome.php'
];

echo "1️⃣ VERIFICANDO EXISTENCIA DE ARCHIVOS:\n";
echo "======================================\n";

foreach ($auth_views as $nombre => $ruta) {
    $archivo_completo = __DIR__ . '/..' . $ruta;
    if (file_exists($archivo_completo)) {
        echo "✅ {$nombre} - EXISTE\n";
    } else {
        echo "❌ {$nombre} - NO EXISTE: {$archivo_completo}\n";
    }
}

echo "\n2️⃣ FLUJO DE REGISTRO - VERIFICACIÓN:\n";
echo "====================================\n";
echo "📝 Registro → 🎯 registro_exitoso.php → ✅ verificar_email.php → 🔑 login.php\n";
echo "   ↳ Enlaces alternativos: reenviar_verificacion.php\n\n";

echo "3️⃣ FLUJO DE RECUPERACIÓN:\n";
echo "=========================\n";
echo "🔐 recover_password_new.php → 📧 recuperacion_enviada.php → 🔄 reset_password.php → 🔑 login.php\n\n";

echo "4️⃣ FLUJO POST-LOGIN:\n";
echo "====================\n";
echo "🔑 login.php → (ROL) → 🎛️ dashboard.php (admin) O 🏠 welcome.php (otros)\n";
echo "   ↳ Logout: logout.php → 🔑 login.php\n\n";

echo "5️⃣ NAVEGACIÓN PRINCIPAL:\n";
echo "========================\n";
echo "🏠 index.php ← → 🔑 login.php ← → 📝 registro.php\n";
echo "              ↳ → 🔐 recover_password_new.php\n\n";

echo "6️⃣ VERIFICANDO ENLACES ESPECÍFICOS:\n";
echo "===================================\n";

// Verificar enlaces en archivos específicos
$enlaces_criticos = [
    'login.php' => ['registro.php', 'recover_password_new.php'],
    'registro_exitoso.php' => ['verificar_email.php', 'reenviar_verificacion.php', 'login.php'],
    'verificar_email.php' => ['login.php', 'reenviar_verificacion.php', '../../index.php'],
    'recuperacion_enviada.php' => ['reset_password.php', 'recover_password_new.php', 'login.php'],
    'reset_password.php' => ['login.php', 'recover_password_new.php'],
    'welcome.php' => ['../auth/logout.php', '../admin/dashboard.php', '../../index.php']
];

foreach ($enlaces_criticos as $archivo => $enlaces_esperados) {
    echo "\n🔍 Verificando enlaces en {$archivo}:\n";
    
    $ruta_archivo = __DIR__ . '/../view/auth/' . $archivo;
    if ($archivo === 'welcome.php') {
        $ruta_archivo = __DIR__ . '/../view/home/' . $archivo;
    }
    
    if (file_exists($ruta_archivo)) {
        $contenido = file_get_contents($ruta_archivo);
        
        foreach ($enlaces_esperados as $enlace) {
            if (strpos($contenido, $enlace) !== false) {
                echo "   ✅ Contiene enlace a: {$enlace}\n";
            } else {
                echo "   ❌ NO contiene enlace a: {$enlace}\n";
            }
        }
    } else {
        echo "   ❌ Archivo no encontrado: {$ruta_archivo}\n";
    }
}

echo "\n7️⃣ CONFIGURACIÓN DE REDIRECCIÓN:\n";
echo "=================================\n";

// Verificar redirecciones en archivos PHP
$archivos_redireccion = [
    'registro.php' => 'registro_exitoso.php',
    'recover_password_new.php' => 'recuperacion_enviada.php',
    'reset_password.php' => 'login.php',
    'logout.php' => 'login.php'
];

foreach ($archivos_redireccion as $archivo => $destino_esperado) {
    $ruta_archivo = __DIR__ . '/../view/auth/' . $archivo;
    
    if (file_exists($ruta_archivo)) {
        $contenido = file_get_contents($ruta_archivo);
        
        if (strpos($contenido, "Location: {$destino_esperado}") !== false || 
            strpos($contenido, "Location: {$destino_esperado}?") !== false) {
            echo "✅ {$archivo} redirige correctamente a {$destino_esperado}\n";
        } else {
            echo "❌ {$archivo} NO redirige a {$destino_esperado}\n";
        }
    }
}

echo "\n8️⃣ URLS PARA PROBAR MANUALMENTE:\n";
echo "=================================\n";
echo "🏠 Página principal: {$base_url}/\n";
echo "🔑 Login: {$base_url}/view/auth/login.php\n";
echo "📝 Registro: {$base_url}/view/auth/registro.php\n";
echo "🔐 Recuperación: {$base_url}/view/auth/recover_password_new.php\n";
echo "📧 Reenvío: {$base_url}/view/auth/reenviar_verificacion.php\n";
echo "🎛️ Dashboard Admin: {$base_url}/view/admin/dashboard.php\n";
echo "🏠 Welcome Usuario: {$base_url}/view/home/welcome.php\n";

echo "\n9️⃣ CREDENCIALES DE PRUEBA:\n";
echo "==========================\n";
echo "👤 Luis: luis@carecenter.com / password123\n";
echo "👤 Admin: admin@carecenter.com / password123\n";
echo "👤 Cliente: cliente@carecenter.com / password123\n";

echo "\n✅ VERIFICACIÓN COMPLETADA\n";
echo "==========================\n";
echo "📋 Todas las vistas deben estar conectadas correctamente según el flujo mostrado arriba.\n";
echo "🧪 Usa las URLs proporcionadas para probar cada flujo manualmente.\n";
?>