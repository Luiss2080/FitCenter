<?php
/**
 * Script de verificación y corrección del sistema CareCenter
 */

echo "🔍 VERIFICACIÓN DEL SISTEMA CARECENTER\n";
echo "=====================================\n\n";

// Definir rutas base
$rootPath = dirname(__DIR__);
$errors = [];
$warnings = [];
$success = [];

// Verificar estructura de carpetas
echo "📁 Verificando estructura de carpetas...\n";
$carpetas_requeridas = [
    'config' => $rootPath . '/config',
    'controller' => $rootPath . '/controller', 
    'models' => $rootPath . '/models',
    'view' => $rootPath . '/view',
    'utils' => $rootPath . '/utils',
    'public' => $rootPath . '/public',
    'logs' => $rootPath . '/logs',
];

foreach ($carpetas_requeridas as $nombre => $ruta) {
    if (is_dir($ruta)) {
        $success[] = "✅ Carpeta '$nombre' existe";
    } else {
        $errors[] = "❌ Carpeta '$nombre' no existe: $ruta";
    }
}

// Verificar archivos críticos
echo "\n📄 Verificando archivos críticos...\n";
$archivos_criticos = [
    'Bootstrap' => $rootPath . '/config/bootstrap.php',
    'Constantes' => $rootPath . '/config/constantes.php',
    'Router' => $rootPath . '/public/router.php',
    'Index' => $rootPath . '/public/index.php',
    '.htaccess principal' => $rootPath . '/.htaccess',
    '.htaccess public' => $rootPath . '/public/.htaccess',
    '.env' => $rootPath . '/.env',
];

foreach ($archivos_criticos as $nombre => $ruta) {
    if (file_exists($ruta)) {
        $success[] = "✅ $nombre existe";
    } else {
        $errors[] = "❌ $nombre no existe: $ruta";
    }
}

// Verificar controladores
echo "\n🎮 Verificando controladores...\n";
$controladores = ['AutenticacionControlador', 'DashboardControlador', 'AdminControlador'];
foreach ($controladores as $controlador) {
    $archivo = $rootPath . "/controller/$controlador.php";
    if (file_exists($archivo)) {
        $success[] = "✅ Controlador '$controlador' existe";
    } else {
        $warnings[] = "⚠️ Controlador '$controlador' no encontrado";
    }
}

// Verificar vistas principales
echo "\n👁️ Verificando vistas principales...\n";
$vistas = [
    'Login' => $rootPath . '/view/autenticacion/login.php',
    'Dashboard' => $rootPath . '/view/dashboard/index.php',
    'Header' => $rootPath . '/view/layouts/header.php',
    'Footer' => $rootPath . '/view/layouts/footer.php',
];

foreach ($vistas as $nombre => $ruta) {
    if (file_exists($ruta)) {
        $success[] = "✅ Vista '$nombre' existe";
    } else {
        $warnings[] = "⚠️ Vista '$nombre' no encontrada: $ruta";
    }
}

// Verificar permisos de carpetas
echo "\n🔒 Verificando permisos...\n";
$carpetas_permisos = [$rootPath . '/logs', $rootPath . '/public/uploads'];
foreach ($carpetas_permisos as $carpeta) {
    if (is_dir($carpeta)) {
        if (is_writable($carpeta)) {
            $success[] = "✅ Carpeta escribible: " . basename($carpeta);
        } else {
            $warnings[] = "⚠️ Carpeta sin permisos de escritura: " . basename($carpeta);
        }
    }
}

// Mostrar resultados
echo "\n📊 RESUMEN DE VERIFICACIÓN\n";
echo "==========================\n";

if (!empty($success)) {
    echo "\n🟢 ÉXITOS (" . count($success) . "):\n";
    foreach ($success as $msg) {
        echo "   $msg\n";
    }
}

if (!empty($warnings)) {
    echo "\n🟡 ADVERTENCIAS (" . count($warnings) . "):\n";
    foreach ($warnings as $msg) {
        echo "   $msg\n";
    }
}

if (!empty($errors)) {
    echo "\n🔴 ERRORES CRÍTICOS (" . count($errors) . "):\n";
    foreach ($errors as $msg) {
        echo "   $msg\n";
    }
}

// Estado final
echo "\n🎯 ESTADO FINAL: ";
if (empty($errors)) {
    if (empty($warnings)) {
        echo "✅ SISTEMA PERFECTO\n";
    } else {
        echo "⚠️ SISTEMA OPERATIVO CON ADVERTENCIAS\n";
    }
    echo "\n🚀 El sistema debería funcionar correctamente.\n";
    echo "📝 Accede a: http://localhost/care_center/public/\n";
    echo "🔑 Login: http://localhost/care_center/public/login\n";
    echo "ℹ️ Info: http://localhost/care_center/public/info.php\n";
} else {
    echo "❌ SISTEMA CON ERRORES CRÍTICOS\n";
    echo "\n🛠️ Revisa los errores críticos antes de continuar.\n";
}

echo "\n" . str_repeat("=", 50) . "\n";