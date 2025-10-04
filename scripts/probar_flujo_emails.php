<?php
/**
 * Script para probar el flujo completo de registro y recuperación
 */

require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../utils/TokenService.php';
require_once __DIR__ . '/../utils/EmailService.php';

echo "🧪 PROBANDO FLUJO COMPLETO DE EMAILS\n";
echo "====================================\n\n";

// Limpiar logs previos
$logFile = __DIR__ . '/../logs/emails.log';
if (file_exists($logFile)) {
    file_put_contents($logFile, '');
}

try {
    $tokenService = new TokenService($pdo);
    $emailService = new EmailService();
    
    // 1. Simular registro de nuevo usuario
    echo "1️⃣ SIMULANDO REGISTRO DE NUEVO USUARIO\n";
    echo "--------------------------------------\n";
    
    $email_prueba = 'test_' . time() . '@carecenter.com';
    $nombre_prueba = 'Usuario Prueba';
    
    // Crear usuario en BD (simulado)
    $password_hash = password_hash('password123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, apellido, email, password, rol, email_verificado, estado, fecha_registro) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
    $resultado = $stmt->execute([
        $nombre_prueba,
        'Apellido',
        $email_prueba,
        $password_hash,
        'paciente',  // Usar 'paciente' en lugar de 'cliente'
        0,  // No verificado
        'activo'
    ]);
    
    if ($resultado) {
        $usuario_id = $pdo->lastInsertId();
        echo "   ✅ Usuario creado con ID: {$usuario_id}\n";
        echo "   📧 Email: {$email_prueba}\n";
        
        // Crear token de verificación
        $token = $tokenService->crearTokenVerificacion($usuario_id, $email_prueba);
        
        if ($token) {
            echo "   🎫 Token generado: " . substr($token, 0, 20) . "...\n";
            
            // Enviar email de verificación
            $envioEmail = $emailService->enviarVerificacionEmail($email_prueba, $nombre_prueba, $token);
            
            if ($envioEmail) {
                echo "   📧 Email de verificación ENVIADO\n";
            } else {
                echo "   ❌ Error enviando email de verificación\n";
            }
        }
    }
    
    echo "\n";
    
    // 2. Simular recuperación de contraseña para usuario verificado
    echo "2️⃣ SIMULANDO RECUPERACIÓN DE CONTRASEÑA\n";
    echo "---------------------------------------\n";
    
    // Usar usuario demo que ya está verificado
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = 'cliente@carecenter.com' AND email_verificado = 1");
    $stmt->execute();
    $usuario_verificado = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($usuario_verificado) {
        echo "   ✅ Usuario verificado encontrado: {$usuario_verificado['nombre']}\n";
        echo "   📧 Email: {$usuario_verificado['email']}\n";
        
        // Crear token de recuperación
        $tokenRecup = $tokenService->crearTokenRecuperacion($usuario_verificado['id_usuario'], $usuario_verificado['email']);
        
        if ($tokenRecup) {
            echo "   🎫 Token de recuperación: " . substr($tokenRecup, 0, 20) . "...\n";
            
            // Enviar email de recuperación
            $envioRecup = $emailService->enviarRecuperacionPassword(
                $usuario_verificado['email'], 
                $usuario_verificado['nombre'], 
                $tokenRecup
            );
            
            if ($envioRecup) {
                echo "   📧 Email de recuperación ENVIADO\n";
            } else {
                echo "   ❌ Error enviando email de recuperación\n";
            }
        }
    }
    
    echo "\n";
    
    // 3. Mostrar contenido del log de emails
    echo "3️⃣ EMAILS GENERADOS EN EL SISTEMA\n";
    echo "=================================\n";
    
    if (file_exists($logFile)) {
        $contenidoLog = file_get_contents($logFile);
        if (!empty($contenidoLog)) {
            echo $contenidoLog;
        } else {
            echo "   ⚠️ No se encontraron emails en el log\n";
        }
    } else {
        echo "   ⚠️ Archivo de log no existe\n";
    }
    
    echo "\n";
    
    // 4. Enlaces para probar en el navegador
    echo "4️⃣ ENLACES PARA PROBAR EN NAVEGADOR\n";
    echo "===================================\n";
    echo "🌐 Registro: http://localhost/care_center/view/auth/registro.php\n";
    echo "🌐 Login: http://localhost/care_center/view/auth/login.php\n";
    echo "🌐 Recuperar: http://localhost/care_center/view/auth/recover_password_new.php\n";
    echo "🌐 Verificar (con token): http://localhost/care_center/view/auth/verificar_email.php?token={$token}\n";
    echo "🌐 Reset (con token): http://localhost/care_center/view/auth/reset_password.php?token={$tokenRecup}\n";
    
    echo "\n📝 Revisar archivo: logs/emails.log para ver todos los emails simulados\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>