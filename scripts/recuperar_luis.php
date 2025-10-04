<?php
/**
 * Simulación de recuperación de contraseña para Luis
 */

require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../utils/TokenService.php';
require_once __DIR__ . '/../utils/EmailService.php';

echo "🔐 SIMULANDO RECUPERACIÓN DE CONTRASEÑA PARA LUIS\n";
echo "================================================\n\n";

try {
    $email = 'luis@carecenter.com';
    
    // Verificar que el usuario existe y está verificado
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ? AND email_verificado = 1");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($usuario) {
        echo "✅ Usuario encontrado: {$usuario['nombre']} {$usuario['apellido']}\n";
        echo "📧 Email: {$usuario['email']}\n";
        echo "✅ Email verificado: Sí\n\n";
        
        // Crear token de recuperación
        $tokenService = new TokenService($pdo);
        $token = $tokenService->crearTokenRecuperacion($usuario['id_usuario'], $email);
        
        if ($token) {
            echo "🎫 Token de recuperación generado: " . substr($token, 0, 30) . "...\n";
            
            // Simular envío de email
            $emailService = new EmailService();
            $emailEnviado = $emailService->enviarRecuperacionPassword($email, $usuario['nombre'], $token);
            
            if ($emailEnviado) {
                echo "📧 Email de recuperación enviado (simulado)\n\n";
                
                // Mostrar enlaces para probar
                echo "🔗 ENLACES PARA PROBAR RECUPERACIÓN:\n";
                echo "====================================\n";
                echo "📝 Página de confirmación: http://localhost/care_center/view/auth/recuperacion_enviada.php?email={$email}&nombre={$usuario['nombre']}&token={$token}\n";
                echo "🔐 Reset directo: http://localhost/care_center/view/auth/reset_password.php?token={$token}\n\n";
                
                echo "📋 PASOS PARA PROBAR RECUPERACIÓN:\n";
                echo "1. Abre la página de confirmación\n";
                echo "2. Haz clic en 'Cambiar Contraseña Ahora'\n";
                echo "3. Ingresa nueva contraseña\n";
                echo "4. Haz login con la nueva contraseña\n";
            }
        }
    } else {
        echo "❌ Usuario no encontrado o no verificado\n";
        echo "💡 Asegúrate de que el usuario Luis esté registrado y verificado\n";
    }
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
?>