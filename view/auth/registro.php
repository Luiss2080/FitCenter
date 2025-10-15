<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if (empty($nombre) || empty($apellido) || empty($email) || empty($password)) {
        $_SESSION['error'] = 'Todos los campos son requeridos';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'El email no tiene un formato válido';
    } elseif ($password !== $confirm_password) {
        $_SESSION['error'] = 'Las contraseñas no coinciden';
    } elseif (strlen($password) < 6) {
        $_SESSION['error'] = 'La contraseña debe tener al menos 6 caracteres';
    } else {
        try {
            require_once '../../config/conexion.php';
            require_once '../../utils/TokenService.php';
            require_once '../../utils/EmailService.php';
            require_once '../../utils/Logger.php';
            
            // Verificar si el email ya existe
            $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->fetch()) {
                $_SESSION['error'] = 'Este email ya está registrado';
                Logger::loginFallido($email, 'Email ya registrado en intento de registro');
            } else {
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                
                // Insertar usuario con rol 'cliente' por defecto
                $stmt = $pdo->prepare("
                    INSERT INTO usuarios (nombre, apellido, email, password, telefono, rol, estado, email_verificado) 
                    VALUES (?, ?, ?, ?, ?, 'cliente', 'activo', 0)
                ");
                
                if ($stmt->execute([$nombre, $apellido, $email, $password_hash, $telefono])) {
                    $usuario_id = $pdo->lastInsertId();
                    
                    // Registrar actividad de registro
                    Logger::registroUsuario($usuario_id, $email, $nombre . ' ' . $apellido, 'cliente');
                    
                    // Crear token de verificación
                    $tokenService = new TokenService($pdo);
                    $token = $tokenService->crearTokenVerificacion($usuario_id, $email);
                    
                    if ($token) {
                        // Enviar email de verificación
                        $emailService = new EmailService();
                        $emailEnviado = $emailService->enviarVerificacionEmail($email, $nombre . ' ' . $apellido, $token);
                        
                        if ($emailEnviado) {
                            Logger::info("Email de verificación enviado a: $email");
                            
                            // Redirigir a página de confirmación con datos
                            $params = http_build_query([
                                'email' => $email,
                                'nombre' => $nombre . ' ' . $apellido,
                                'token' => $token
                            ]);
                            header("Location: registro_exitoso.php?{$params}");
                            exit;
                        } else {
                            $_SESSION['warning'] = 'Usuario creado pero hubo un problema enviando el email de verificación.';
                            Logger::warning("Error enviando email de verificación a: $email");
                        }
                    } else {
                        $_SESSION['warning'] = 'Usuario creado pero no se pudo generar el token de verificación.';
                        Logger::error("Error generando token de verificación para: $email");
                    }
                    
                    header('Location: login.php');
                    exit;
                } else {
                    $_SESSION['error'] = 'Error al crear la cuenta. Intenta nuevamente.';
                    Logger::error("Error al insertar usuario: $email");
                }
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error del sistema: ' . $e->getMessage();
            Logger::critical('Error en registro de usuario: ' . $e->getMessage(), ['email' => $email]);
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
    <title>Registro - FitCenter</title>
    <link rel="stylesheet" href="../../public/css/app.css">
</head>
<body class="auth-body">
    <div class="auth-container">
        <h2 class="auth-title">Crear Cuenta</h2>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['warning'])): ?>
            <div class="alert alert-warning">
                <?php echo $_SESSION['warning']; unset($_SESSION['warning']); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" id="nombre" name="nombre" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="apellido" class="form-label">Apellido:</label>
                <input type="text" id="apellido" name="apellido" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="email" class="form-label">Email:</label>
                <input type="email" id="email" name="email" class="form-control" required>
                <small class="form-text">Te enviaremos un enlace de verificación a esta dirección</small>
            </div>
            
            <div class="form-group">
                <label for="telefono" class="form-label">Teléfono (opcional):</label>
                <input type="tel" id="telefono" name="telefono" class="form-control" placeholder="+52 55 1234 5678">
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Contraseña:</label>
                <input type="password" id="password" name="password" class="form-control" required minlength="6">
                <small class="form-text">Mínimo 6 caracteres</small>
            </div>
            
            <div class="form-group">
                <label for="confirm_password" class="form-label">Confirmar Contraseña:</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">
                Crear Cuenta
            </button>
        </form>
        
        <div class="text-center mt-2">
            <a href="login.php" class="link">¿Ya tienes cuenta? Inicia sesión</a>
        </div>
    </div>
    
    <script src="../../public/js/app.js"></script>
</body>
</html>
