<?php
// Diagnóstico del Sistema CareCenter
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnóstico del Sistema</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 20px; 
            background: #f5f5f5; 
        }
        .test { 
            margin: 10px 0; 
            padding: 10px; 
            border-radius: 5px; 
        }
        .ok { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
        .info { background: #d1ecf1; color: #0c5460; }
    </style>
</head>
<body>
    <h1>Diagnóstico del Sistema CareCenter</h1>
    
    <?php
    echo '<div class="test info"><strong>PHP Version:</strong> ' . phpversion() . '</div>';
    
    // Verificar conexión a la base de datos
    try {
        include 'config/conexion.php';
        echo '<div class="test ok"><strong>Base de Datos:</strong> ✓ Conexión exitosa</div>';
    } catch (Exception $e) {
        echo '<div class="test error"><strong>Base de Datos:</strong> ✗ Error: ' . $e->getMessage() . '</div>';
    }
    
    // Verificar archivos principales
    $archivos = [
        'config/conexion.php' => 'Conexión DB',
        'view/home/welcome.php' => 'Página de Bienvenida',
        'view/auth/login.php' => 'Login',
        'view/admin/dashboard.php' => 'Dashboard',
        'public/css/app.css' => 'CSS Principal',
        'public/js/app.js' => 'JavaScript Principal'
    ];
    
    foreach ($archivos as $archivo => $nombre) {
        if (file_exists($archivo)) {
            echo '<div class="test ok"><strong>' . $nombre . ':</strong> ✓ Archivo existe</div>';
        } else {
            echo '<div class="test error"><strong>' . $nombre . ':</strong> ✗ Archivo no encontrado</div>';
        }
    }
    
    // Verificar permisos de escritura
    $directorios = ['logs', 'public/uploads'];
    foreach ($directorios as $dir) {
        if (is_writable($dir)) {
            echo '<div class="test ok"><strong>Escritura en ' . $dir . ':</strong> ✓ Permisos correctos</div>';
        } else {
            echo '<div class="test error"><strong>Escritura en ' . $dir . ':</strong> ✗ Sin permisos</div>';
        }
    }
    
    echo '<div class="test info"><strong>Servidor Web:</strong> ' . $_SERVER['SERVER_SOFTWARE'] . '</div>';
    echo '<div class="test info"><strong>Hora Actual:</strong> ' . date('Y-m-d H:i:s') . '</div>';
    ?>
    
    <hr>
    <h2>Enlaces de Prueba</h2>
    <p><a href="view/home/welcome.php">Página de Bienvenida</a></p>
    <p><a href="view/auth/login.php">Login</a></p>
    <p><a href="public/index.php">Index Principal</a></p>
</body>
</html>