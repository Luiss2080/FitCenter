<?php
session_start();

// Verificar si el usuario está autenticado y es cliente
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] != 'cliente') {
    header('Location: ../auth/login.php');
    exit;
}

$usuario_nombre = $_SESSION['usuario_nombre'] ?? 'Cliente';
$usuario_apellido = $_SESSION['usuario_apellido'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal del Cliente - FitCenter</title>
    <link rel="icon" type="image/png" href="../../public/img/LogoFitCenter.png">
    <link rel="stylesheet" href="../../public/css/app.css">
    <style>
        .dashboard-body {
            background: linear-gradient(135deg, #4ecdc4, #44a08d);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar {
            background: rgba(0,0,0,0.8);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            margin: 0;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .welcome-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            text-align: center;
        }
        .modules-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }
        .module-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            text-align: center;
        }
        .module-card:hover {
            transform: translateY(-5px);
        }
        .module-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        .module-title {
            font-size: 1.25rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 0.5rem;
        }
        .module-description {
            color: #666;
            line-height: 1.5;
            margin-bottom: 1rem;
        }
        .btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            background: #4ecdc4;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .btn:hover {
            background: #44a08d;
        }
        .btn-secondary {
            background: #6c757d;
        }
        .btn-secondary:hover {
            background: #545b62;
        }
        .membership-status {
            background: linear-gradient(135deg, #4ecdc4, #44a08d);
            color: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            text-align: center;
        }
        .status-active {
            background: linear-gradient(135deg, #28a745, #20c997);
        }
    </style>
</head>
<body class="dashboard-body">
    <nav class="navbar">
        <h1 class="navbar-brand">💪 FitCenter - Portal del Cliente</h1>
        <div class="user-info">
            <span>Bienvenido, <?php echo htmlspecialchars($usuario_nombre . ' ' . $usuario_apellido); ?></span>
            <a href="../auth/logout.php" class="btn btn-secondary">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="container">
        <div class="membership-status status-active">
            <h2>🔵 Tu Membresía</h2>
            <p><strong>Estado:</strong> Activa ✅</p>
            <p><strong>Plan:</strong> Premium Mensual</p>
            <p><strong>Vence:</strong> 28 días restantes</p>
            <button class="btn" onclick="alert('Función en desarrollo')">Renovar Membresía</button>
        </div>

        <div class="modules-grid">
            <div class="module-card">
                <div class="module-icon">📅</div>
                <div class="module-title">Mis Clases</div>
                <div class="module-description">
                    Reserva clases grupales, consulta horarios y ve tu historial de asistencia.
                </div>
                <button class="btn" onclick="alert('Módulo en desarrollo')">Ver Clases</button>
            </div>

            <div class="module-card">
                <div class="module-icon">🏃‍♂️</div>
                <div class="module-title">Mi Asistencia</div>
                <div class="module-description">
                    Revisa tu historial de entrenamientos y estadísticas de asistencia.
                </div>
                <button class="btn" onclick="alert('Módulo en desarrollo')">Ver Historial</button>
            </div>

            <div class="module-card">
                <div class="module-icon">🛍️</div>
                <div class="module-title">Tienda</div>
                <div class="module-description">
                    Explora nuestros productos: suplementos, ropa deportiva y accesorios.
                </div>
                <button class="btn" onclick="alert('Módulo en desarrollo')">Ver Productos</button>
            </div>

            <div class="module-card">
                <div class="module-icon">💳</div>
                <div class="module-title">Mi Cuenta</div>
                <div class="module-description">
                    Actualiza tus datos personales y consulta tu historial de pagos.
                </div>
                <button class="btn" onclick="alert('Módulo en desarrollo')">Gestionar Cuenta</button>
            </div>

            <div class="module-card">
                <div class="module-icon">📊</div>
                <div class="module-title">Mi Progreso</div>
                <div class="module-description">
                    Seguimiento de tu progreso fitness y objetivos personales.
                </div>
                <button class="btn" onclick="alert('Módulo en desarrollo')">Ver Progreso</button>
            </div>

            <div class="module-card">
                <div class="module-icon">📞</div>
                <div class="module-title">Soporte</div>
                <div class="module-description">
                    Contacta con nuestro equipo de atención al cliente y soporte técnico.
                </div>
                <button class="btn" onclick="alert('Módulo en desarrollo')">Contactar</button>
            </div>
        </div>
    </div>

    <script src="../../public/js/app.js"></script>
</body>
</html>
