<?php
/**
 * Simulación del registro del usuario Luis
 */

require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../utils/TokenService.php';
require_once __DIR__ . '/../utils/EmailService.php';

echo "👤 SIMULANDO REGISTRO DE USUARIO 'LUIS'\n";
echo "======================================\n\n";

try {
    // Datos del usuario Luis
    $nombre = 'Luis';
    $apellido = 'Rodriguez';
    $email = 'luis@carecenter.com';
    $telefono = '123456789';
    
    // Verificar si el email ya existe
    $stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        echo "❌ El email {$email} ya está registrado.\n";
        echo "🗑️ Eliminando usuario existente...\n";
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        echo "✅ Usuario eliminado.\n\n";
    }
    
    // Crear el usuario
    $password_hash = password_hash('password123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, apellido, email, password, telefono, rol, email_verificado, estado, fecha_registro) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    
    $resultado = $stmt->execute([
        $nombre,
        $apellido,
        $email,
        $password_hash,
        $telefono,
        'paciente',
        0,  // No verificado inicialmente
        'activo'
    ]);
    
    if ($resultado) {
        $usuario_id = $pdo->lastInsertId();
        echo "✅ Usuario '{$nombre}' creado exitosamente con ID: {$usuario_id}\n";
        echo "📧 Email: {$email}\n";
        echo "🔐 Contraseña: password123\n\n";
        
        // Crear token de verificación
        $tokenService = new TokenService($pdo);
        $token = $tokenService->crearTokenVerificacion($usuario_id, $email);
        
        if ($token) {
            echo "🎫 Token de verificación generado: " . substr($token, 0, 30) . "...\n";
            
            // Simular envío de email
            $emailService = new EmailService();
            $emailEnviado = $emailService->enviarVerificacionEmail($email, $nombre, $token);
            
            if ($emailEnviado) {
                echo "📧 Email de verificación enviado (simulado)\n\n";
                
                // Mostrar enlaces para probar
                echo "🔗 ENLACES PARA PROBAR:\n";
                echo "======================\n";
                echo "📝 Página de confirmación: http://localhost/care_center/view/auth/registro_exitoso.php?email={$email}&nombre={$nombre}&token={$token}\n";
                echo "✅ Verificación directa: http://localhost/care_center/view/auth/verificar_email.php?token={$token}\n";
                echo "🔑 Login después: http://localhost/care_center/view/auth/login.php\n\n";
                
                echo "📋 PASOS PARA PROBAR:\n";
                echo "1. Abre: http://localhost/care_center/view/auth/registro_exitoso.php?email={$email}&nombre={$nombre}&token={$token}\n";
                echo "2. Haz clic en 'Verificar Email Ahora'\n";
                echo "3. Una vez verificado, ve al login\n";
                echo "4. Usa: {$email} / password123\n";
            }
        }
    }
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>