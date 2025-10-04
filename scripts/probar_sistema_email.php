<?php
/**
 * Script para probar el sistema completo de verificación de email
 */

require_once 'config/conexion.php';
require_once 'utils/TokenService.php';
require_once 'utils/EmailService.php';

echo "🧪 PROBANDO SISTEMA DE VERIFICACIÓN DE EMAIL\n";
echo "============================================\n\n";

try {
    // 1. Probar TokenService
    echo "1️⃣ Probando TokenService...\n";
    $tokenService = new TokenService($pdo);
    
    // Obtener usuario de prueba
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = 'cliente@carecenter.com'");
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($usuario) {
        echo "   ✅ Usuario encontrado: {$usuario['nombre']} {$usuario['apellido']}\n";
        
        // Crear token de verificación
        $tokenVerif = $tokenService->crearTokenVerificacion($usuario['id_usuario'], $usuario['email']);
        if ($tokenVerif) {
            echo "   ✅ Token de verificación creado: " . substr($tokenVerif, 0, 20) . "...\n";
            
            // Validar token
            $validacion = $tokenService->validarToken($tokenVerif, 'verificacion_email');
            if ($validacion) {
                echo "   ✅ Token validado correctamente\n";
            }
        }
        
        // Crear token de recuperación
        $tokenReset = $tokenService->crearTokenRecuperacion($usuario['id_usuario'], $usuario['email']);
        if ($tokenReset) {
            echo "   ✅ Token de recuperación creado: " . substr($tokenReset, 0, 20) . "...\n";
        }
    }
    
    // 2. Probar EmailService
    echo "\n2️⃣ Probando EmailService...\n";
    $emailService = new EmailService();
    
    if ($emailService->probarConexion()) {
        echo "   ✅ Servicio de email inicializado\n";
        
        // Simular envío de verificación
        $envioVerif = $emailService->enviarVerificacionEmail(
            'test@carecenter.com',
            'Usuario Prueba',
            'token_prueba_123'
        );
        
        if ($envioVerif) {
            echo "   ✅ Email de verificación simulado\n";
        }
        
        // Simular envío de recuperación
        $envioReset = $emailService->enviarRecuperacionPassword(
            'test@carecenter.com',
            'Usuario Prueba',
            'token_reset_456'
        );
        
        if ($envioReset) {
            echo "   ✅ Email de recuperación simulado\n";
        }
    }
    
    // 3. Verificar estructura de BD
    echo "\n3️⃣ Verificando estructura de base de datos...\n";
    
    // Verificar tabla tokens_verificacion
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM tokens_verificacion");
    $tokenCount = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "   ✅ Tabla tokens_verificacion: {$tokenCount['count']} registros\n";
    
    // Verificar columna email_verificado
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM usuarios WHERE email_verificado = 1");
    $verificadosCount = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "   ✅ Usuarios con email verificado: {$verificadosCount['count']}\n";
    
    // 4. Mostrar últimos emails (en modo desarrollo)
    echo "\n4️⃣ Últimos emails enviados (simulados)...\n";
    $ultimosEmails = $emailService->obtenerUltimosEmails(3);
    
    if (empty($ultimosEmails)) {
        echo "   ℹ️ No hay emails en el log aún\n";
    } else {
        foreach ($ultimosEmails as $index => $email) {
            echo "   📧 Email " . ($index + 1) . ": " . substr($email, 0, 100) . "...\n";
        }
    }
    
    echo "\n🎉 TODAS LAS PRUEBAS COMPLETADAS\n";
    echo "===============================\n\n";
    
    echo "🔗 URLs DISPONIBLES:\n";
    echo "   Registro: http://localhost/care_center/view/auth/registro.php\n";
    echo "   Login: http://localhost/care_center/view/auth/login.php\n";
    echo "   Recuperar: http://localhost/care_center/view/auth/recover_password_new.php\n";
    echo "   Reenviar: http://localhost/care_center/view/auth/reenviar_verificacion.php\n";
    
    echo "\n📝 CREDENCIALES DE PRUEBA:\n";
    echo "   admin@carecenter.com / password123 (Verificado)\n";
    echo "   cliente@carecenter.com / password123 (Verificado)\n";
    
    echo "\n💡 PARA PROBAR:\n";
    echo "1. Registra un nuevo usuario\n";
    echo "2. Revisa logs/emails.log para ver el enlace de verificación\n";
    echo "3. Copia el enlace y ábrelo en el navegador\n";
    echo "4. Inicia sesión con el usuario verificado\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
?>