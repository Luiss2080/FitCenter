<?php
session_start();

$tokenValido = false;
$tokenData = null;

// Verificar token en URL
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    try {
        require_once '../../config/conexion.php';
        require_once '../../utils/TokenService.php';
        
        $tokenService = new TokenService($pdo);
        
        // Validar token
        $tokenData = $tokenService->validarToken($token, 'reset_password');
        
        if ($tokenData) {
            $tokenValido = true;
        } else {
            $_SESSION['error'] = 'El enlace de recuperación es inválido o ha expirado.';
        }
        
    } catch (Exception $e) {
        $_SESSION['error'] = 'Error del sistema. Inténtalo más tarde.';
        error_log("Error validando token reset: " . $e->getMessage());
    }
} else {
    $_SESSION['error'] = 'Token de recuperación no proporcionado.';
}

// Procesar cambio de contraseña
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $tokenValido) {
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if (empty($password) || empty($confirm_password)) {
        $_SESSION['error'] = 'Todos los campos son requeridos';
    } elseif ($password !== $confirm_password) {
        $_SESSION['error'] = 'Las contraseñas no coinciden';
    } elseif (strlen($password) < 6) {
        $_SESSION['error'] = 'La contraseña debe tener al menos 6 caracteres';
    } else {
        try {
            require_once '../../utils/Logger.php';
            
            $tokenService = new TokenService($pdo);
            
            // Actualizar contraseña
            if ($tokenService->actualizarPassword($tokenData['usuario_id'], $password)) {
                // Marcar token como usado
                $tokenService->marcarTokenUsado($token);
                
                // Obtener datos del usuario para el log
                $usuario = $tokenService->obtenerUsuarioPorEmail($tokenData['email']);
                if ($usuario) {
                    Logger::resetPasswordExitoso(
                        $usuario['id'], 
                        $tokenData['email'], 
                        $usuario['nombre'] . ' ' . $usuario['apellido']
                    );
                }
                
                $_SESSION['success'] = 'Contraseña actualizada exitosamente. Ya puedes iniciar sesión.';
                header('Location: login.php');
                exit;
            } else {
                $_SESSION['error'] = 'Error al actualizar la contraseña. Inténtalo de nuevo.';
                Logger::error("Error actualizando contraseña para: " . $tokenData['email']);
            }
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error del sistema. Inténtalo más tarde.';
            Logger::critical('Error en reset de contraseña: ' . $e->getMessage(), ['email' => $tokenData['email'] ?? 'unknown']);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña - FitCenter</title>
    <link rel="icon" type="image/png" href="../../public/img/LogoFitCenter.png">
    <link rel="stylesheet" href="../../public/css/app.css">
</head>
<body class="auth-body">
    <div class="auth-container">
        <div class="text-center mb-4">
            <h1 class="auth-title">💪 FitCenter</h1>
            <h2>Restablecer Contraseña</h2>
        </div>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($tokenValido): ?>
            <p class="text-center mb-4">
                Ingresa tu nueva contraseña para la cuenta: <strong><?php echo htmlspecialchars($tokenData['email']); ?></strong>
            </p>
            
            <form method="POST">
                <div class="form-group">
                    <label for="password" class="form-label">Nueva Contraseña:</label>
                    <input type="password" id="password" name="password" class="form-control" required minlength="6">
                    <small class="form-text">Mínimo 6 caracteres</small>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password" class="form-label">Confirmar Contraseña:</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">
                    Actualizar Contraseña
                </button>
            </form>
            
            <div class="text-center mt-4">
                <small class="text-muted">
                    Este enlace se desactivará después de usar.
                </small>
            </div>
        <?php else: ?>
            <div class="text-center">
                <p>El enlace de recuperación no es válido o ha expirado.</p>
                <a href="recover_password_new.php" class="btn btn-secondary">
                    Solicitar Nuevo Enlace
                </a>
            </div>
        <?php endif; ?>
        
        <div class="text-center mt-4">
            <a href="login.php" class="link">Volver al Login</a>
        </div>
    </div>
    
    <script src="../../public/js/app.js"></script>
    
    <script>
        // Validación en tiempo real de contraseñas
        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            
            if (confirmPassword && password !== confirmPassword) {
                this.setCustomValidity('Las contraseñas no coinciden');
            } else {
                this.setCustomValidity('');
            }
        });
    </script>
</body>
</html>