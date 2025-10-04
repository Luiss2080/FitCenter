<?php
session_start();

// Verificar si el usuario está autenticado y es administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] != 'administrador') {
    header('Location: ../auth/login.php');
    exit;
}

$usuario_nombre = $_SESSION['usuario_nombre'] ?? 'Administrador';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Administrador - CareCenter</title>
    <link rel="stylesheet" href="../../public/css/app.css">
</head>
<body class="dashboard-body">
    <nav class="navbar">
        <h1 class="navbar-brand">🏥 CareCenter Admin</h1>
        <div class="user-info">
            <span>Bienvenido, <?php echo htmlspecialchars($usuario_nombre); ?></span>
            <a href="../../view/auth/login.php?logout=1" class="btn btn-secondary">Cerrar Sesión</a>
        </div>
    </nav>
        
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