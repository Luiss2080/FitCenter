<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if (empty($nombre) || empty($email) || empty($password)) {
        $_SESSION['error'] = 'Todos los campos son requeridos';
    } elseif ($password !== $confirm_password) {
        $_SESSION['error'] = 'Las contraseñas no coinciden';
    } elseif (strlen($password) < 6) {
        $_SESSION['error'] = 'La contraseña debe tener al menos 6 caracteres';
    } else {
        try {
            require_once '../../config/conexion.php';
            $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->fetch()) {
                $_SESSION['error'] = 'Este email ya está registrado';
            } else {
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password, rol, activo, created_at) VALUES (?, ?, ?, 'cliente', 1, NOW())");
                
                if ($stmt->execute([$nombre, $email, $password_hash])) {
                    $_SESSION['success'] = 'Registro exitoso';
                    header('Location: login.php');
                    exit;
                }
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error del sistema';
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
    <title>Registro - CareCenter</title>
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
        
        <form method="POST">
            <div class="form-group">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" id="nombre" name="nombre" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="email" class="form-label">Email:</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Contraseña:</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="confirm_password" class="form-label">Confirmar:</label>
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
