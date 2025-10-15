<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitCenter - Sistema de Gestión para Gimnasios</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: linear-gradient(135deg, #ff6b6b 0%, #4ecdc4 100%);
            min-height: 100vh;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            color: white;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
        }
        .card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        .auth-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }
        .btn {
            display: inline-block;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            text-align: center;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        .btn-primary {
            background: #ff6b6b;
            color: white;
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        .btn-success {
            background: #28a745;
            color: white;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        .footer {
            text-align: center;
            color: rgba(255, 255, 255, 0.8);
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">💪 FitCenter</div>
            <div class="subtitle">Sistema Integral de Gestión para Gimnasios</div>
        </div>

        <!-- Botones de Acceso -->
        <div class="card">
            <h2 style="margin-bottom: 20px; color: #333; text-align: center;">🚀 Acceso al Sistema</h2>
            <div class="auth-buttons">
                <a href="../auth/login.php" class="btn btn-primary">🔑 Iniciar Sesión</a>
                <a href="../auth/registro.php" class="btn btn-success">👤 Registrarse</a>
                <a href="../auth/recover_password_new.php" class="btn btn-secondary">🔒 Recuperar Contraseña</a>
                <a href="../../scripts/probar_sistema_email.php" class="btn btn-secondary">🧪 Probar Sistema</a>
            </div>
            
            <div style="background: #e7f3ff; padding: 20px; border-radius: 10px; margin-top: 20px;">
                <h4 style="color: #0066cc; margin-bottom: 10px; text-align: center;">📝 Credenciales de Prueba</h4>
                <div style="text-align: center;">
                    <p><strong>Administrador:</strong> admin@fitcenter.com / password123</p>
                    <p><strong>Vendedor:</strong> vendedor@fitcenter.com / password123</p>
                    <p><strong>Cliente:</strong> cliente@fitcenter.com / password123</p>
                </div>
                <div style="text-align: center; margin-top: 15px;">
                    <small style="color: #666;">✅ Todos los usuarios demo tienen el email verificado</small>
                </div>
            </div>
        </div>

        <!-- Módulos del Sistema -->
        <div class="card">
            <h2 style="margin-bottom: 20px; color: #333; text-align: center;">🏋️ Módulos FitCenter</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 20px;">
                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px;">
                    <h4 style="color: #ff6b6b;">👥 Gestión de Miembros</h4>
                    <p>Registro, renovaciones, check-in y control de membresías</p>
                </div>
                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px;">
                    <h4 style="color: #ff6b6b;">🛒 Ventas de Productos</h4>
                    <p>Suplementos, ropa deportiva y accesorios</p>
                </div>
                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px;">
                    <h4 style="color: #ff6b6b;">📅 Clases Grupales</h4>
                    <p>CrossFit, Yoga, Spinning y reservas online</p>
                </div>
                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px;">
                    <h4 style="color: #ff6b6b;">📊 Reportes y Dashboard</h4>
                    <p>Métricas de ingresos, asistencia e inventario</p>
                </div>
            </div>
        </div>

        <div class="footer">
            <p>&copy; 2025 FitCenter - Sistema de Gestión para Gimnasios</p>
            <p>Desarrollado con PHP 8.1 + MySQL ❤️</p>
        </div>
    </div>
</body>
</html>
