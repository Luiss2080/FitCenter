<?php
session_start();

// Verificar si el usuario está autenticado y es vendedor
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] != 'vendedor') {
    header('Location: ../auth/login.php');
    exit;
}

$usuario_nombre = $_SESSION['usuario_nombre'] ?? 'Vendedor';
$usuario_apellido = $_SESSION['usuario_apellido'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Vendedor - FitCenter</title>
    <link rel="stylesheet" href="../../public/css/app.css">
    <style>
        .dashboard-body {
            background: linear-gradient(135deg, #667eea, #764ba2);
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
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .btn:hover {
            background: #764ba2;
        }
        .btn-secondary {
            background: #6c757d;
        }
        .btn-secondary:hover {
            background: #545b62;
        }
        .sales-summary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            text-align: center;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        .stat-item {
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
            padding: 1rem;
            text-align: center;
        }
        .stat-number {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }
    </style>
</head>
<body class="dashboard-body">
    <nav class="navbar">
        <h1 class="navbar-brand">💪 FitCenter - Punto de Venta</h1>
        <div class="user-info">
            <span>Bienvenido, <?php echo htmlspecialchars($usuario_nombre . ' ' . $usuario_apellido); ?></span>
            <a href="../auth/logout.php" class="btn btn-secondary">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="container">
        <div class="sales-summary">
            <h2>🟢 Resumen de Ventas</h2>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">$2,450</div>
                    <div class="stat-label">Ventas Hoy</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">12</div>
                    <div class="stat-label">Transacciones</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">8</div>
                    <div class="stat-label">Membresías Vendidas</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">$15,230</div>
                    <div class="stat-label">Ventas del Mes</div>
                </div>
            </div>
        </div>

        <div class="modules-grid">
            <div class="module-card">
                <div class="module-icon">🛒</div>
                <div class="module-title">Punto de Venta</div>
                <div class="module-description">
                    Registra ventas de productos, aplica descuentos y genera facturas.
                </div>
                <button class="btn" onclick="alert('Módulo en desarrollo')">Abrir POS</button>
            </div>

            <div class="module-card">
                <div class="module-icon">👥</div>
                <div class="module-title">Gestión de Miembros</div>
                <div class="module-description">
                    Registra nuevos miembros, renueva membresías y actualiza datos.
                </div>
                <button class="btn" onclick="alert('Módulo en desarrollo')">Gestionar</button>
            </div>

            <div class="module-card">
                <div class="module-icon">📦</div>
                <div class="module-title">Inventario</div>
                <div class="module-description">
                    Consulta stock, registra entradas y gestiona productos del gimnasio.
                </div>
                <button class="btn" onclick="alert('Módulo en desarrollo')">Ver Inventario</button>
            </div>

            <div class="module-card">
                <div class="module-icon">✅</div>
                <div class="module-title">Check-in Miembros</div>
                <div class="module-description">
                    Registra la asistencia de miembros y verifica sus membresías.
                </div>
                <button class="btn" onclick="alert('Módulo en desarrollo')">Check-in</button>
            </div>

            <div class="module-card">
                <div class="module-icon">📅</div>
                <div class="module-title">Gestión de Clases</div>
                <div class="module-description">
                    Programa clases, gestiona reservas y controla la asistencia.
                </div>
                <button class="btn" onclick="alert('Módulo en desarrollo')">Ver Clases</button>
            </div>

            <div class="module-card">
                <div class="module-icon">💰</div>
                <div class="module-title">Caja del Día</div>
                <div class="module-description">
                    Apertura, arqueo y cierre de caja con reportes de ingresos.
                </div>
                <button class="btn" onclick="alert('Módulo en desarrollo')">Gestionar Caja</button>
            </div>
        </div>
    </div>

    <script src="../../public/js/app.js"></script>
</body>
</html>
