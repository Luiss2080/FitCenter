<?php
session_start();

$mensaje = '';
$tipo = 'info';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    try {
        require_once '../../config/conexion.php';
        require_once '../../utils/TokenService.php';
        
        $tokenService = new TokenService($pdo);
        
        // Validar token
        $tokenData = $tokenService->validarToken($token, 'verificacion_email');
        
        if ($tokenData) {
            // Verificar email del usuario
            if ($tokenService->verificarEmailUsuario($tokenData['id_usuario'])) {
                // Marcar token como usado
                $tokenService->marcarTokenUsado($token);
                
                $mensaje = '¡Email verificado exitosamente! Ya puedes iniciar sesión.';
                $tipo = 'success';
                
                // Limpiar tokens expirados
                $tokenService->limpiarTokensExpirados();
            } else {
                $mensaje = 'Error al verificar el email. Inténtalo de nuevo.';
                $tipo = 'error';
            }
        } else {
            $mensaje = 'El enlace de verificación es inválido o ha expirado. Solicita uno nuevo.';
            $tipo = 'error';
        }
        
    } catch (Exception $e) {
        $mensaje = 'Error del sistema. Inténtalo más tarde.';
        $tipo = 'error';
        error_log("Error verificando email: " . $e->getMessage());
    }
} else {
    $mensaje = 'No se proporcionó un token de verificación.';
    $tipo = 'error';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Email - CareCenter</title>
    <link rel="stylesheet" href="../../public/css/app.css">
    <style>
        .verification-container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
        }
        .status-icon {
            font-size: 4rem;
            margin-bottom: 20px;
        }
        .status-success { color: #28a745; }
        .status-error { color: #dc3545; }
        .celebration {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }
        .error-help {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }
    </style>
</head>
<body class="auth-body">
    <div class="verification-container">
        <?php if ($tipo === 'success'): ?>
            <!-- Verificación exitosa -->
            <div class="status-icon status-success">�</div>
            <h1 style="color: #28a745;">¡Email Verificado!</h1>
            
            <div class="celebration">
                <h3>✅ ¡Perfecto!</h3>
                <p>Tu email ha sido verificado correctamente. Ya puedes acceder a CareCenter.</p>
            </div>
            
            <div style="margin: 30px 0;">
                <a href="login.php" class="btn btn-primary" style="font-size: 18px; padding: 15px 30px;">
                    🔑 Iniciar Sesión Ahora
                </a>
            </div>
            
        <?php else: ?>
            <!-- Error en verificación -->
            <div class="status-icon status-error">❌</div>
            <h1 style="color: #dc3545;">Problema con la Verificación</h1>
            
            <div class="error-help">
                <h4>🚨 Error:</h4>
                <p><?php echo htmlspecialchars($mensaje); ?></p>
            </div>
            
            <h4>🛠️ Opciones para resolver:</h4>
            <div style="margin: 20px 0;">
                <a href="reenviar_verificacion.php" class="btn btn-primary">
                    📧 Solicitar Nuevo Email
                </a>
                <a href="registro.php" class="btn btn-secondary">
                    👤 Registrarse de Nuevo
                </a>
            </div>
        <?php endif; ?>
        
        <!-- Enlaces de navegación -->
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">
            <a href="../../index.php" class="btn btn-secondary">🏠 Página Principal</a>
            <a href="login.php" class="btn btn-secondary">🔑 Ir al Login</a>
        </div>
    </div>
</body>
</html>