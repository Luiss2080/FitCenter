<?php
session_start();
$usuario_autenticado = isset($_SESSION['usuario_id']);
$usuario_nombre = $_SESSION['usuario_nombre'] ?? '';
$usuario_rol = $_SESSION['usuario_rol'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido - CareCenter</title>
    <link rel="stylesheet" href="../../public/css/app.css">
    <style>
        .welcome-dashboard {
            max-width: 800px;
            margin: 50px auto;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .user-info {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            text-align: center;
        }
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }
        .feature-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            border-left: 4px solid #667eea;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="welcome-body">
    <?php if ($usuario_autenticado): ?>
        <!-- Dashboard para usuario autenticado -->
        <div class="welcome-dashboard">
            <div class="user-info">
                <h1>🎉 ¡Bienvenido, <?php echo htmlspecialchars($usuario_nombre); ?>!</h1>
                <p>Has iniciado sesión exitosamente en CareCenter</p>
                <p><strong>Rol:</strong> <?php echo ucfirst($usuario_rol); ?></p>
            </div>

            <div class="feature-grid">
                <?php if ($usuario_rol === 'administrador'): ?>
                    <div class="feature-card">
                        <div style="font-size: 2rem;">👥</div>
                        <h3>Gestionar Usuarios</h3>
                        <p>Administrar cuentas de usuarios</p>
                    </div>
                    <div class="feature-card">
                        <div style="font-size: 2rem;">📊</div>
                        <h3>Reportes</h3>
                        <p>Ver estadísticas del sistema</p>
                    </div>
                <?php endif; ?>

                <div class="feature-card">
                    <div style="font-size: 2rem;">📧</div>
                    <h3>Perfil</h3>
                    <p>Actualizar información personal</p>
                </div>
                
                <div class="feature-card">
                    <div style="font-size: 2rem;">🔒</div>
                    <h3>Seguridad</h3>
                    <p>Cambiar contraseña</p>
                </div>
            </div>

            <!-- Estado del sistema -->
            <div style="background: #e7f3ff; padding: 20px; border-radius: 10px; margin: 30px 0;">
                <h3 style="color: #0066cc;">📊 Estado del Sistema</h3>
                <p>✅ Email verificado correctamente</p>
                <p>✅ Sesión iniciada: <?php echo date('d/m/Y H:i:s'); ?></p>
                <p>🔐 Última verificación: Activa</p>
            </div>

            <!-- Navegación -->
            <div style="text-align: center; margin-top: 30px;">
                <?php if ($usuario_rol === 'administrador'): ?>
                    <a href="../admin/dashboard.php" class="btn btn-primary">
                        🎛️ Panel de Administración
                    </a>
                <?php endif; ?>
                
                <a href="../auth/logout.php" class="btn btn-secondary">
                    🚪 Cerrar Sesión
                </a>
                
                <a href="../../index.php" class="btn btn-secondary">
                    🏠 Página Principal
                </a>
            </div>
        </div>

    <?php else: ?>
        <!-- Página para usuarios no autenticados -->
        <div class="welcome-container">
            <div class="logo">🏥</div>
            <h1 class="welcome-title">¡Bienvenido a CareCenter!</h1>
            <p class="welcome-subtitle">
                Sistema integral de gestión nutricional y catering especializado
            </p>
            
            <a href="../auth/login.php" class="btn btn-primary">
                🔑 Iniciar Sesión
            </a>
            
            <a href="../auth/registro.php" class="btn btn-secondary">
                📝 Registrarse
            </a>
        </div>
    <?php endif; ?>
    
    <script src="../../public/js/app.js"></script>
</body>
</html>