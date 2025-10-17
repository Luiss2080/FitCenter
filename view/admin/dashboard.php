<?php
session_start();

// Verificar si el usuario está autenticado y es admin
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] != 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

$usuario_nombre = $_SESSION['usuario_nombre'] ?? 'Admin';
$usuario_apellido = $_SESSION['usuario_apellido'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - FitCenter</title>
    <link rel="icon" type="image/png" href="../../public/img/LogoFitCenter.png">
    <link rel="stylesheet" href="../../public/css/app.css">
    <style>
        .dashboard-body {
            background: linear-gradient(135deg, #ff6b6b, #4ecdc4);
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
        }
        .btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            background: #ff6b6b;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .btn:hover {
            background: #ff5252;
        }
        .btn-secondary {
            background: #6c757d;
        }
        .btn-secondary:hover {
            background: #545b62;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin: 2rem 0;
        }
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 1rem;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #ff6b6b;
        }
        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }
    </style>
</head>
<body class="dashboard-body">
    <nav class="navbar">
        <h1 class="navbar-brand">💪 FitCenter - Administración</h1>
        <div class="user-info">
            <span>Bienvenido, <?php echo htmlspecialchars($usuario_nombre . ' ' . $usuario_apellido); ?></span>
            <a href="../auth/logout.php" class="btn btn-secondary">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="container">
        <div class="welcome-card">
            <h2>🔴 Panel de Administración</h2>
            <p>Controla todos los aspectos de tu gimnasio desde aquí. Gestiona usuarios, supervisa operaciones y analiza el rendimiento del negocio.</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">5</div>
                <div class="stat-label">Usuarios Totales</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">1</div>
                <div class="stat-label">Administradores</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">2</div>
                <div class="stat-label">Vendedores</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">2</div>
                <div class="stat-label">Clientes</div>
            </div>
        </div>

        <div class="modules-grid">
            <div class="module-card">
                <div class="module-icon">👥</div>
                <div class="module-title">Gestión de Usuarios</div>
                <div class="module-description">
                    Administra cuentas de usuarios, permisos y roles del sistema.
                </div>
                <button class="btn" onclick="alert('Módulo en desarrollo')">Gestionar</button>
            </div>

            <div class="module-card">
                <div class="module-icon">🏋️</div>
                <div class="module-title">Gestión de Miembros</div>
                <div class="module-description">
                    Control de membresías, planes y renovaciones de clientes.
                </div>
                <button class="btn" onclick="alert('Módulo en desarrollo')">Ver Miembros</button>
            </div>

            <div class="module-card">
                <div class="module-icon">🛒</div>
                <div class="module-title">Punto de Venta</div>
                <div class="module-description">
                    Configura productos, precios e inventario del gimnasio.
                </div>
                <button class="btn" onclick="alert('Módulo en desarrollo')">Configurar</button>
            </div>

            <div class="module-card">
                <div class="module-icon">📅</div>
                <div class="module-title">Clases y Horarios</div>
                <div class="module-description">
                    Programa clases grupales, disciplinas e instructores.
                </div>
                <button class="btn" onclick="alert('Módulo en desarrollo')">Programar</button>
            </div>

            <div class="module-card">
                <div class="module-icon">📊</div>
                <div class="module-title">Reportes y Analytics</div>
                <div class="module-description">
                    Analiza ingresos, asistencia y rendimiento del gimnasio.
                </div>
                <button class="btn" onclick="alert('Módulo en desarrollo')">Ver Reportes</button>
            </div>

            <div class="module-card">
                <div class="module-icon">⚙️</div>
                <div class="module-title">Configuración</div>
                <div class="module-description">
                    Ajustes generales del sistema y parámetros del gimnasio.
                </div>
                <button class="btn" onclick="alert('Módulo en desarrollo')">Configurar</button>
            </div>
        </div>
    </div>

    <script src="../../public/js/app.js"></script>
</body>
</html>
        
        .logout-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s ease;
        }
        
        .logout-btn:hover {
            background: rgba(255,255,255,0.3);
            text-decoration: none;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
    
    <div class="main-content">
        <h1>Dashboard Administrativo</h1>
        
        <div class="dashboard-cards">
            <div class="card">
                <h3 class="card-title">👥 Usuarios Totales</h3>
                <p class="card-text">Gestiona todos los usuarios del sistema</p>
                <a href="#" class="btn btn-primary">Ver Usuarios</a>
            </div>
            
            <div class="card">
                <h3 class="card-title">🍽️ Planes Alimentarios</h3>
                <p class="card-text">Administra los planes nutricionales</p>
                <a href="#" class="btn btn-primary">Ver Planes</a>
            </div>
            
            <div class="card">
                <h3 class="card-title">🚚 Entregas</h3>
                <p class="card-text">Controla las entregas programadas</p>
                <a href="#" class="btn btn-primary">Ver Entregas</a>
            </div>
            
            <div class="card">
                <h3 class="card-title">📊 Reportes</h3>
                <p class="card-text">Analiza estadísticas del negocio</p>
                <a href="#" class="btn btn-primary">Ver Reportes</a>
            </div>
        </div>
        
        <div class="card">
            <h3 class="card-title">Actividad Reciente</h3>
            <p class="card-text">
                • Nuevo usuario registrado: cliente@example.com<br>
                • Plan alimentario actualizado: Plan Detox<br>
                • Entrega programada para: 15:30<br>
            </p>
        </div>
    </div>
    
    <script src="../../public/js/app.js"></script>
</body>
</html>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-users fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Pacientes Total
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?php echo $estadisticas['pacientes_total'] ?? 0; ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-user-injured fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Órdenes Hoy
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?php echo $estadisticas['ordenes_hoy'] ?? 0; ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Ingresos Mes
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            $<?php echo number_format($estadisticas['ingresos_mes'] ?? 0, 2); ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actividad Reciente -->
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-clock"></i> Actividad Reciente
                                </h6>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($actividad_reciente)): ?>
                                    <div class="list-group list-group-flush">
                                        <?php foreach ($actividad_reciente as $actividad): ?>
                                            <div class="list-group-item d-flex justify-content-between align-items-start">
                                                <div class="ms-2 me-auto">
                                                    <div class="fw-bold"><?php echo htmlspecialchars($actividad['descripcion']); ?></div>
                                                    <small class="text-muted">
                                                        <?php echo date('d/m/Y H:i', strtotime($actividad['fecha'])); ?>
                                                    </small>
                                                </div>
                                                <span class="badge bg-primary rounded-pill">
                                                    <?php echo ucfirst($actividad['tipo']); ?>
                                                </span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted text-center py-4">
                                        <i class="fas fa-info-circle"></i> No hay actividad reciente
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-chart-bar"></i> Resumen Rápido
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-6 border-right">
                                        <div class="h5 text-primary"><?php echo $estadisticas['entregas_pendientes'] ?? 0; ?></div>
                                        <div class="small text-muted">Entregas Pendientes</div>
                                    </div>
                                    <div class="col-6">
                                        <div class="h5 text-success"><?php echo $estadisticas['ordenes_hoy'] ?? 0; ?></div>
                                        <div class="small text-muted">Órdenes Nuevas</div>
                                    </div>
                                </div>
                                
                                <hr class="my-3">
                                
                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="text-primary mb-2">Acciones Rápidas</h6>
                                        <div class="d-grid gap-2">
                                            <a href="/admin/usuarios" class="btn btn-primary btn-sm">
                                                <i class="fas fa-user-plus"></i> Gestionar Usuarios
                                            </a>
                                            <a href="/reportes" class="btn btn-secondary btn-sm">
                                                <i class="fas fa-chart-line"></i> Ver Reportes
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    
    <style>
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 0;
        }
        
        .sidebar-sticky {
            position: relative;
            top: 0;
            height: calc(100vh - 48px);
            padding-top: .5rem;
            overflow-x: hidden;
            overflow-y: auto;
        }
        
        .border-left-primary {
            border-left: 0.25rem solid #4e73df !important;
        }
        
        .border-left-success {
            border-left: 0.25rem solid #1cc88a !important;
        }
        
        .border-left-info {
            border-left: 0.25rem solid #36b9cc !important;
        }
        
        .border-left-warning {
            border-left: 0.25rem solid #f6c23e !important;
        }
    </style>
</body>
</html>