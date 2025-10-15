<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    
    if (empty($email)) {
        $_SESSION['error'] = 'El email es requerido';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'El email no tiene un formato válido';
    } else {
        try {
            require_once '../../config/conexion.php';
            require_once '../../utils/TokenService.php';
            require_once '../../utils/EmailService.php';
            require_once '../../utils/Logger.php';
            
            $tokenService = new TokenService($pdo);
            
            // Buscar usuario por email
            $usuario = $tokenService->obtenerUsuarioPorEmail($email);
            
            if ($usuario) {
                if ($usuario['email_verificado'] == 0) {
                    $_SESSION['error'] = 'Debes verificar tu email antes de poder recuperar tu contraseña.';
                    Logger::registrarActividad(
                        $usuario['id'], 
                        $email, 
                        'Intento reset sin verificar', 
                        'Intento de reset de contraseña con email no verificado', 
                        'password_reset', 
                        'fallido'
                    );
                } else {
                    // Crear token de recuperación
                    $token = $tokenService->crearTokenRecuperacion($usuario['id'], $email);
                    
                    if ($token) {
                        // Registrar solicitud de reset
                        Logger::solicitudResetPassword($usuario['id'], $email, $usuario['nombre'] . ' ' . $usuario['apellido']);
                        
                        // Enviar email de recuperación
                        $emailService = new EmailService();
                        $emailEnviado = $emailService->enviarRecuperacionPassword($email, $usuario['nombre'] . ' ' . $usuario['apellido'], $token);
                        
                        if ($emailEnviado) {
                            Logger::info("Email de recuperación enviado a: $email");
                            
                            // Redirigir a página de confirmación con datos
                            $params = http_build_query([
                                'email' => $email,
                                'nombre' => $usuario['nombre'] . ' ' . $usuario['apellido'],
                                'token' => $token
                            ]);
                            header("Location: recuperacion_enviada.php?{$params}");
                            exit;
                        } else {
                            $_SESSION['error'] = 'Error al enviar el email. Inténtalo más tarde.';
                            Logger::error("Error enviando email de recuperación a: $email");
                        }
                    } else {
                        $_SESSION['error'] = 'Error al generar el token. Inténtalo más tarde.';
                        Logger::error("Error generando token de recuperación para: $email");
                    }
                }
            } else {
                // Por seguridad, no revelar si el email existe o no
                $_SESSION['success'] = 'Si el email está registrado, recibirás un enlace de recuperación.';
                Logger::registrarActividad(
                    null, 
                    $email, 
                    'Intento reset email inexistente', 
                    'Intento de reset con email no registrado', 
                    'password_reset', 
                    'fallido'
                );
            }
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error del sistema. Inténtalo más tarde.';
            Logger::critical('Error en recuperación de contraseña: ' . $e->getMessage(), ['email' => $email]);
        }
    }
    
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - FitCenter</title>
    <link rel="stylesheet" href="../../public/css/app.css">
</head>
<body class="auth-body">
    <div class="auth-container">
        <div class="text-center mb-4">
            <h1 class="auth-title">💪 FitCenter</h1>
            <h2>Recuperar Contraseña</h2>
        </div>
        
        <p class="text-center mb-4">
            Ingresa tu email para recibir un enlace de recuperación.
        </p>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="email" class="form-label">Email:</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">
                Enviar Enlace de Recuperación
            </button>
        </form>
        
        <div class="text-center mt-4">
            <a href="login.php" class="link">Volver al Login</a><br>
            <a href="registro.php" class="link">¿No tienes cuenta? Regístrate</a>
        </div>
        
        <div class="text-center mt-4">
            <small class="text-muted">
                El enlace expira en 1 hora por seguridad.<br>
                Revisa también tu carpeta de spam.
            </small>
        </div>
    </div>
    
    <script src="../../public/js/app.js"></script>
</body>
</html>