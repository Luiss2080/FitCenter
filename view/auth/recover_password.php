<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'reset_request') {
        $email = trim($_POST['email'] ?? '');
        
        if (empty($email)) {
            $_SESSION['error'] = 'Email es requerido';
        } else {
            try {
                require_once '../../config/conexion.php';
                $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ? AND activo = 1");
                $stmt->execute([$email]);
                
                if ($stmt->fetch()) {
                    $_SESSION['success'] = 'Si el email existe, recibirás instrucciones para restablecer tu contraseña';
                } else {
                    $_SESSION['error'] = 'Email no encontrado';
                }
            } catch (Exception $e) {
                $_SESSION['error'] = 'Error del sistema';
            }
        }
    } elseif ($action === 'reset_password') {
        $email = trim($_POST['email'] ?? '');
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        
        if (empty($email) || empty($new_password)) {
            $_SESSION['error'] = 'Todos los campos son requeridos';
        } elseif ($new_password !== $confirm_password) {
            $_SESSION['error'] = 'Las contraseñas no coinciden';
        } elseif (strlen($new_password) < 6) {
            $_SESSION['error'] = 'La contraseña debe tener al menos 6 caracteres';
        } else {
            try {
                require_once '../../config/conexion.php';
                $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE usuarios SET password = ? WHERE email = ? AND activo = 1");
                
                if ($stmt->execute([$password_hash, $email])) {
                    $_SESSION['success'] = 'Contraseña actualizada exitosamente';
                    header('Location: login.php');
                    exit;
                } else {
                    $_SESSION['error'] = 'Error al actualizar la contraseña';
                }
            } catch (Exception $e) {
                $_SESSION['error'] = 'Error del sistema';
            }
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
    <title>Recuperar Contraseña - CareCenter</title>
    <link rel="stylesheet" href="../../public/css/app.css">
</head>
<body class="auth-body">
    <div class="auth-container">
        <h2 class="auth-title">Recuperar Contraseña</h2>
        
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
        
        <div style="margin-bottom: 30px;">
            <button type="button" onclick="showResetRequest()" class="btn btn-secondary" style="margin-right: 10px;">
                Solicitar Reset
            </button>
            <button type="button" onclick="showResetPassword()" class="btn btn-secondary">
                Cambiar Contraseña
            </button>
        </div>
        
        <!-- Formulario para solicitar reset -->
        <form id="resetRequestForm" method="POST" style="display: block;">
            <input type="hidden" name="action" value="reset_request">
            <div class="form-group">
                <label for="email_request" class="form-label">Email:</label>
                <input type="email" id="email_request" name="email" class="form-control" required>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">
                Enviar Solicitud
            </button>
        </form>
        
        <!-- Formulario para cambiar contraseña directamente -->
        <form id="resetPasswordForm" method="POST" style="display: none;">
            <input type="hidden" name="action" value="reset_password">
            <div class="form-group">
                <label for="email_reset" class="form-label">Email:</label>
                <input type="email" id="email_reset" name="email" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="new_password" class="form-label">Nueva contraseña:</label>
                <input type="password" id="new_password" name="new_password" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="confirm_password" class="form-label">Confirmar contraseña:</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">
                Cambiar Contraseña
            </button>
        </form>
        
        <div class="text-center mt-2">
            <a href="login.php" class="link">Volver al login</a>
        </div>
    </div>
    
    <script>
        function showResetRequest() {
            document.getElementById('resetRequestForm').style.display = 'block';
            document.getElementById('resetPasswordForm').style.display = 'none';
        }
        
        function showResetPassword() {
            document.getElementById('resetRequestForm').style.display = 'none';
            document.getElementById('resetPasswordForm').style.display = 'block';
        }
    </script>
    <script src="../../public/js/app.js"></script>
</body>
</html>
