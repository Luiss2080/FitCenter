<?php
$titulo = 'Dashboard - CareCenter';
$css_adicional = ['/assets/css/dashboard.css'];
$usuario = Sesion::obtenerUsuario();
?>

<?php include VIEWS_PATH . '/layouts/header.php'; ?>

<div class="dashboard-container">
    <div class="container-fluid">
        
        <!-- Header del Dashboard -->
        <div class="dashboard-header mb-4">
            <div class="row align-items-center">
                <div class="col">
                    <h1 class="dashboard-title">
                        <i class="fas fa-tachometer-alt text-primary me-2"></i>
                        Dashboard
                    </h1>
                    <p class="dashboard-subtitle text-muted mb-0">
                        Bienvenido de vuelta, <strong><?php echo htmlspecialchars($usuario['nombre']); ?></strong>
                    </p>
                </div>
                <div class="col-auto">
                    <div class="dashboard-date">
                        <i class="fas fa-calendar-alt me-2"></i>
                        <?php echo date('d \d\e F, Y'); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Métricas según el rol -->
        <?php if ($usuario['rol'] === ROL_ADMIN): ?>
            <!-- Métricas de Administrador -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="metric-card metric-primary">
                        <div class="metric-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="metric-content">
                            <h3 class="metric-number" id="total-usuarios">0</h3>
                            <p class="metric-label">Usuarios Activos</p>
                            <small class="metric-change text-success">
                                <i class="fas fa-arrow-up"></i> +5 este mes
                            </small>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="metric-card metric-success">
                        <div class="metric-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="metric-content">
                            <h3 class="metric-number" id="planes-activos">0</h3>
                            <p class="metric-label">Planes Activos</p>
                            <small class="metric-change text-success">
                                <i class="fas fa-arrow-up"></i> +12% vs mes anterior
                            </small>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="metric-card metric-warning">
                        <div class="metric-icon">
                            <i class="fas fa-truck"></i>
                        </div>
                        <div class="metric-content">
                            <h3 class="metric-number" id="entregas-hoy">0</h3>
                            <p class="metric-label">Entregas Hoy</p>
                            <small class="metric-change text-info">
                                <i class="fas fa-clock"></i> 5 pendientes
                            </small>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="metric-card metric-info">
                        <div class="metric-icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="metric-content">
                            <h3 class="metric-number" id="ingresos-mes">$0</h3>
                            <p class="metric-label">Ingresos del Mes</p>
                            <small class="metric-change text-success">
                                <i class="fas fa-arrow-up"></i> +8.5%
                            </small>
                        </div>
                    </div>
                </div>
            </div>

        <?php elseif ($usuario['rol'] === ROL_NUTRIOLOGO): ?>
            <!-- Métricas de Nutriólogo -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="metric-card metric-primary">
                        <div class="metric-icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div class="metric-content">
                            <h3 class="metric-number" id="mis-pacientes">0</h3>
                            <p class="metric-label">Mis Pacientes</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="metric-card metric-success">
                        <div class="metric-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="metric-content">
                            <h3 class="metric-number" id="citas-hoy">0</h3>
                            <p class="metric-label">Citas Hoy</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="metric-card metric-warning">
                        <div class="metric-icon">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <div class="metric-content">
                            <h3 class="metric-number" id="planes-revision">0</h3>
                            <p class="metric-label">Planes en Revisión</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="metric-card metric-info">
                        <div class="metric-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="metric-content">
                            <h3 class="metric-number" id="calificacion">4.8</h3>
                            <p class="metric-label">Calificación Promedio</p>
                        </div>
                    </div>
                </div>
            </div>

        <?php elseif ($usuario['rol'] === ROL_COCINA): ?>
            <!-- Métricas de Cocina -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="metric-card metric-warning">
                        <div class="metric-icon">
                            <i class="fas fa-fire"></i>
                        </div>
                        <div class="metric-content">
                            <h3 class="metric-number" id="ordenes-pendientes">0</h3>
                            <p class="metric-label">Órdenes Pendientes</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="metric-card metric-success">
                        <div class="metric-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="metric-content">
                            <h3 class="metric-number" id="ordenes-completadas">0</h3>
                            <p class="metric-label">Completadas Hoy</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="metric-card metric-info">
                        <div class="metric-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="metric-content">
                            <h3 class="metric-number" id="tiempo-promedio">0min</h3>
                            <p class="metric-label">Tiempo Promedio</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="metric-card metric-primary">
                        <div class="metric-icon">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <div class="metric-content">
                            <h3 class="metric-number" id="recetas-activas">0</h3>
                            <p class="metric-label">Recetas Activas</p>
                        </div>
                    </div>
                </div>
            </div>

        <?php elseif ($usuario['rol'] === ROL_REPARTIDOR): ?>
            <!-- Métricas de Repartidor -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="metric-card metric-warning">
                        <div class="metric-icon">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                        <div class="metric-content">
                            <h3 class="metric-number" id="entregas-pendientes">0</h3>
                            <p class="metric-label">Entregas Pendientes</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="metric-card metric-success">
                        <div class="metric-icon">
                            <i class="fas fa-check-double"></i>
                        </div>
                        <div class="metric-content">
                            <h3 class="metric-number" id="entregas-completadas">0</h3>
                            <p class="metric-label">Completadas Hoy</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="metric-card metric-info">
                        <div class="metric-icon">
                            <i class="fas fa-route"></i>
                        </div>
                        <div class="metric-content">
                            <h3 class="metric-number" id="km-recorridos">0 km</h3>
                            <p class="metric-label">Km Recorridos</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="metric-card metric-primary">
                        <div class="metric-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="metric-content">
                            <h3 class="metric-number" id="calificacion-repartidor">4.9</h3>
                            <p class="metric-label">Calificación</p>
                        </div>
                    </div>
                </div>
            </div>

        <?php else: // ROL_CLIENTE ?>
            <!-- Métricas de Cliente -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="metric-card metric-primary">
                        <div class="metric-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="metric-content">
                            <h3 class="metric-number" id="dias-plan">0</h3>
                            <p class="metric-label">Días de Plan</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="metric-card metric-success">
                        <div class="metric-icon">
                            <i class="fas fa-weight"></i>
                        </div>
                        <div class="metric-content">
                            <h3 class="metric-number" id="progreso-peso">0kg</h3>
                            <p class="metric-label">Progreso de Peso</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="metric-card metric-warning">
                        <div class="metric-icon">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <div class="metric-content">
                            <h3 class="metric-number" id="proxima-comida">0</h3>
                            <p class="metric-label">Próxima Comida</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="metric-card metric-info">
                        <div class="metric-icon">
                            <i class="fas fa-award"></i>
                        </div>
                        <div class="metric-content">
                            <h3 class="metric-number" id="objetivos-cumplidos">0%</h3>
                            <p class="metric-label">Objetivos Cumplidos</p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Contenido principal según rol -->
        <div class="row">
            <div class="col-lg-8 mb-4">
                <!-- Gráficos y estadísticas -->
                <div class="card dashboard-card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-area me-2"></i>
                            Estadísticas
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Aquí iría el gráfico -->
                        <canvas id="dashboard-chart" height="300"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <!-- Actividad reciente -->
                <div class="card dashboard-card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-clock me-2"></i>
                            Actividad Reciente
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="activity-feed" id="activity-feed">
                            <!-- Se carga dinámicamente -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones rápidas -->
        <div class="row">
            <div class="col-12">
                <div class="card dashboard-card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-bolt me-2"></i>
                            Acciones Rápidas
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row" id="quick-actions">
                            <!-- Se cargan dinámicamente según el rol -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Script específico del dashboard -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Cargar datos del dashboard según el rol
    cargarDatosDashboard();
    
    // Configurar gráfico
    configurarGrafico();
    
    // Cargar actividad reciente
    cargarActividadReciente();
    
    // Cargar acciones rápidas
    cargarAccionesRapidas();
    
    // Actualizar cada 30 segundos
    setInterval(cargarDatosDashboard, 30000);
    
    function cargarDatosDashboard() {
        fetch('/api/dashboard/datos', {
            headers: {
                'X-CSRF-Token': window.CONFIG.csrf_token
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                actualizarMetricas(data.metricas);
                actualizarGrafico(data.grafico);
            }
        })
        .catch(error => console.error('Error cargando datos:', error));
    }
    
    function actualizarMetricas(metricas) {
        // Actualizar números con animación
        Object.keys(metricas).forEach(key => {
            const elemento = document.getElementById(key);
            if (elemento) {
                animarNumero(elemento, metricas[key]);
            }
        });
    }
    
    function animarNumero(elemento, valorFinal) {
        const valorInicial = parseInt(elemento.textContent) || 0;
        const diferencia = valorFinal - valorInicial;
        const duracion = 1000; // 1 segundo
        const pasos = 60;
        const incremento = diferencia / pasos;
        
        let paso = 0;
        const intervalo = setInterval(() => {
            paso++;
            const valorActual = Math.round(valorInicial + (incremento * paso));
            elemento.textContent = valorActual;
            
            if (paso >= pasos) {
                clearInterval(intervalo);
                elemento.textContent = valorFinal;
            }
        }, duracion / pasos);
    }
    
    function configurarGrafico() {
        const ctx = document.getElementById('dashboard-chart').getContext('2d');
        
        window.dashboardChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Actividad',
                    data: [],
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
    
    function actualizarGrafico(datos) {
        if (window.dashboardChart && datos) {
            window.dashboardChart.data.labels = datos.labels || [];
            window.dashboardChart.data.datasets[0].data = datos.values || [];
            window.dashboardChart.update();
        }
    }
    
    function cargarActividadReciente() {
        fetch('/api/dashboard/actividad', {
            headers: {
                'X-CSRF-Token': window.CONFIG.csrf_token
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarActividad(data.actividad);
            }
        })
        .catch(error => console.error('Error cargando actividad:', error));
    }
    
    function mostrarActividad(actividades) {
        const feed = document.getElementById('activity-feed');
        feed.innerHTML = '';
        
        if (!actividades || actividades.length === 0) {
            feed.innerHTML = '<p class="text-muted text-center">No hay actividad reciente</p>';
            return;
        }
        
        actividades.forEach(actividad => {
            const item = document.createElement('div');
            item.className = 'activity-item';
            item.innerHTML = `
                <div class="activity-icon">
                    <i class="fas ${actividad.icono} text-${actividad.color}"></i>
                </div>
                <div class="activity-content">
                    <p class="activity-text">${actividad.descripcion}</p>
                    <small class="activity-time text-muted">${actividad.tiempo}</small>
                </div>
            `;
            feed.appendChild(item);
        });
    }
    
    function cargarAccionesRapidas() {
        const userRole = window.CONFIG.user_role;
        const container = document.getElementById('quick-actions');
        
        const acciones = obtenerAccionesPorRol(userRole);
        
        container.innerHTML = '';
        acciones.forEach(accion => {
            const col = document.createElement('div');
            col.className = 'col-lg-3 col-md-6 mb-3';
            col.innerHTML = `
                <div class="quick-action-card" onclick="window.location.href='${accion.url}'">
                    <div class="quick-action-icon">
                        <i class="fas ${accion.icono}"></i>
                    </div>
                    <div class="quick-action-content">
                        <h6>${accion.titulo}</h6>
                        <small>${accion.descripcion}</small>
                    </div>
                </div>
            `;
            container.appendChild(col);
        });
    }
    
    function obtenerAccionesPorRol(rol) {
        const acciones = {
            'admin': [
                { titulo: 'Nuevo Usuario', descripcion: 'Crear usuario', icono: 'fa-user-plus', url: '/admin/usuarios/crear' },
                { titulo: 'Ver Reportes', descripcion: 'Consultar reportes', icono: 'fa-chart-bar', url: '/reportes' },
                { titulo: 'Configuración', descripcion: 'Ajustar sistema', icono: 'fa-cog', url: '/configuracion' },
                { titulo: 'Log Auditoría', descripcion: 'Revisar eventos', icono: 'fa-list-alt', url: '/auditoria' }
            ],
            'nutriologo': [
                { titulo: 'Nuevo Paciente', descripcion: 'Registrar paciente', icono: 'fa-user-plus', url: '/pacientes/crear' },
                { titulo: 'Crear Plan', descripcion: 'Plan nutricional', icono: 'fa-clipboard-list', url: '/planes/crear' },
                { titulo: 'Mis Pacientes', descripcion: 'Ver pacientes', icono: 'fa-users', url: '/pacientes' },
                { titulo: 'Recetas', descripcion: 'Gestionar recetas', icono: 'fa-book', url: '/recetas' }
            ],
            'cocina': [
                { titulo: 'Órdenes Pendientes', descripcion: 'Ver órdenes', icono: 'fa-list-ul', url: '/cocina/ordenes' },
                { titulo: 'Marcar Completada', descripcion: 'Completar orden', icono: 'fa-check', url: '/cocina/preparacion' },
                { titulo: 'Recetas', descripcion: 'Ver recetas', icono: 'fa-book', url: '/recetas' },
                { titulo: 'Inventario', descripcion: 'Gestionar inventario', icono: 'fa-boxes', url: '/inventario' }
            ],
            'repartidor': [
                { titulo: 'Mis Entregas', descripción: 'Ver entregas', icono: 'fa-truck', url: '/reparto/entregas' },
                { titulo: 'Mapa de Rutas', descripcion: 'Ver rutas', icono: 'fa-map', url: '/reparto/mapa' },
                { titulo: 'Confirmar Entrega', descripcion: 'Confirmar entrega', icono: 'fa-check-circle', url: '/reparto/confirmar' },
                { titulo: 'Mi Rendimiento', descripcion: 'Ver estadísticas', icono: 'fa-chart-line', url: '/reparto/stats' }
            ],
            'cliente': [
                { titulo: 'Mi Plan', descripcion: 'Ver mi plan', icono: 'fa-clipboard-list', url: '/mi-plan' },
                { titulo: 'Mis Pedidos', descripcion: 'Historial pedidos', icono: 'fa-shopping-cart', url: '/mis-pedidos' },
                { titulo: 'Progreso', descripcion: 'Ver progreso', icono: 'fa-chart-line', url: '/mi-progreso' },
                { titulo: 'Contactar Nutriólogo', descripcion: 'Enviar mensaje', icono: 'fa-comments', url: '/contactar-nutriologo' }
            ]
        };
        
        return acciones[rol] || [];
    }
});
</script>

<?php include VIEWS_PATH . '/layouts/footer.php'; ?>