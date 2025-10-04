<?php
session_start();

// Mostrar mensaje de logout exitoso
if (isset($_GET['logout']) && $_GET['logout'] === 'success') {
    $_SESSION['success'] = 'Has cerrado sesión correctamente.';
    unset($_GET['logout']);
}

// Procesar login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = 'Email y contraseña son requeridos';
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
    
    // Conexión a base de datos
    try {
        require_once '../../config/conexion.php';
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ? AND estado = 'activo'");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($usuario && password_verify($password, $usuario['password'])) {
            // Verificar si el email está verificado
            if ($usuario['email_verificado'] == 0) {
                $_SESSION['error'] = 'Debes verificar tu email antes de iniciar sesión. Revisa tu bandeja de entrada.';
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit;
            }
            
            $_SESSION['usuario_id'] = $usuario['id_usuario'];
            $_SESSION['usuario_email'] = $usuario['email'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['usuario_rol'] = $usuario['rol'];
            
            if ($usuario['rol'] === 'administrador') {
                header('Location: ../admin/dashboard.php');
            } else {
                header('Location: ../home/welcome.php');
            }
            exit;
        } else {
            $_SESSION['error'] = 'Credenciales incorrectas';
        }
    } catch (Exception $e) {
        $_SESSION['error'] = 'Error del sistema';
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
    <title>Iniciar Sesión - CareCenter</title>
    <link rel="stylesheet" href="../../public/css/app.css">
</head>
<body class="auth-body">
    <div class="auth-container">
        <h2 class="auth-title">Iniciar Sesión</h2>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="email" class="form-label">Email:</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Contraseña:</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">
                Iniciar Sesión
            </button>
        </form>
        
        <div class="text-center mt-2">
            <a href="registro.php" class="link">¿No tienes cuenta? Regístrate</a><br>
            <a href="recover_password_new.php" class="link">¿Olvidaste tu contraseña?</a>
        </div>
    </div>
    
    <script src="../../public/js/app.js"></script>
</body>
</html>