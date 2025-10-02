<?php
$titulo = 'Resumen Ejecutivo - CareCenter';
$css_adicional = ['/assets/css/dashboard.css'];
$usuario = Sesion::obtenerUsuario();

// Solo admin y nutriólogos pueden ver el resumen ejecutivo
if (!in_array($usuario['rol'], [ROL_ADMIN, ROL_NUTRIOLOGO])) {
    header('Location: /dashboard');
    exit;
}
?>

<?php include VIEWS_PATH . '/layouts/header.php'; ?>

<div class="dashboard-container">
    <div class="container-fluid">
        
        <!-- Header -->
        <div class="dashboard-header mb-4">
            <div class="row align-items-center">
                <div class="col">
                    <h1 class="dashboard-title">
                        <i class="fas fa-chart-pie text-primary me-2"></i>
                        Resumen Ejecutivo
                    </h1>
                    <p class="dashboard-subtitle text-muted mb-0">
                        Vista general del rendimiento del sistema
                    </p>
                </div>
                <div class="col-auto">
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-primary" id="btn-filtro-fecha">
                            <i class="fas fa-calendar-alt me-2"></i>
                            Últimos 30 días
                        </button>
                        <button type="button" class="btn btn-primary" id="btn-exportar">
                            <i class="fas fa-download me-2"></i>
                            Exportar PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Métricas Principales -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="summary-card">
                    <div class="summary-header">
                        <h6 class="summary-title">Ingresos Totales</h6>
                        <div class="summary-icon bg-success">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                    <div class="summary-content">
                        <h3 class="summary-value" id="ingresos-totales">$0</h3>
                        <div class="summary-trend">
                            <span class="trend-indicator trend-up">
                                <i class="fas fa-arrow-up"></i> 12.5%
                            </span>
                            <small class="text-muted">vs período anterior</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="summary-card">
                    <div class="summary-header">
                        <h6 class="summary-title">Clientes Activos</h6>
                        <div class="summary-icon bg-primary">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="summary-content">
                        <h3 class="summary-value" id="clientes-activos">0</h3>
                        <div class="summary-trend">
                            <span class="trend-indicator trend-up">
                                <i class="fas fa-arrow-up"></i> 8.2%
                            </span>
                            <small class="text-muted">nuevos este mes</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="summary-card">
                    <div class="summary-header">
                        <h6 class="summary-title">Planes Completados</h6>
                        <div class="summary-icon bg-info">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                    <div class="summary-content">
                        <h3 class="summary-value" id="planes-completados">0</h3>
                        <div class="summary-trend">
                            <span class="trend-indicator trend-up">
                                <i class="fas fa-arrow-up"></i> 95.4%
                            </span>
                            <small class="text-muted">tasa de éxito</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="summary-card">
                    <div class="summary-header">
                        <h6 class="summary-title">Satisfacción</h6>
                        <div class="summary-icon bg-warning">
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <div class="summary-content">
                        <h3 class="summary-value" id="satisfaccion-promedio">0</h3>
                        <div class="summary-trend">
                            <span class="trend-indicator trend-up">
                                <i class="fas fa-arrow-up"></i> 4.8/5
                            </span>
                            <small class="text-muted">calificación promedio</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos Principales -->
        <div class="row mb-4">
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-line me-2"></i>
                            Tendencia de Ingresos
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="ingresos-chart" height="300"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-pie-chart me-2"></i>
                            Distribución de Planes
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="planes-chart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tablas de Rendimiento -->
        <div class="row mb-4">
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-trophy me-2"></i>
                            Top Nutriólogos
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Nutriólogo</th>
                                        <th>Pacientes</th>
                                        <th>Calificación</th>
                                        <th>Ingresos</th>
                                    </tr>
                                </thead>
                                <tbody id="top-nutriologos">
                                    <!-- Se carga dinámicamente -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-utensils me-2"></i>
                            Planes Más Populares
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Plan</th>
                                        <th>Clientes</th>
                                        <th>Éxito</th>
                                        <th>Precio</th>
                                    </tr>
                                </thead>
                                <tbody id="planes-populares">
                                    <!-- Se carga dinámicamente -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Métricas Operacionales -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-cogs me-2"></i>
                            Métricas Operacionales
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3 col-md-6 mb-3">
                                <div class="metric-box">
                                    <div class="metric-icon bg-primary">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="metric-info">
                                        <h6>Tiempo Promedio Preparación</h6>
                                        <h4 id="tiempo-prep">0min</h4>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6 mb-3">
                                <div class="metric-box">
                                    <div class="metric-icon bg-success">
                                        <i class="fas fa-truck"></i>
                                    </div>
                                    <div class="metric-info">
                                        <h6>Entregas a Tiempo</h6>
                                        <h4 id="entregas-tiempo">0%</h4>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6 mb-3">
                                <div class="metric-box">
                                    <div class="metric-icon bg-warning">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </div>
                                    <div class="metric-info">
                                        <h6>Quejas/Reclamos</h6>
                                        <h4 id="quejas-total">0</h4>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6 mb-3">
                                <div class="metric-box">
                                    <div class="metric-icon bg-info">
                                        <i class="fas fa-redo"></i>
                                    </div>
                                    <div class="metric-info">
                                        <h6>Tasa de Retención</h6>
                                        <h4 id="tasa-retencion">0%</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alertas y Notificaciones -->
        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-bell me-2"></i>
                            Alertas del Sistema
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="alertas-sistema">
                            <!-- Se cargan dinámicamente -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-tasks me-2"></i>
                            Tareas Pendientes
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="tareas-pendientes">
                            <!-- Se cargan dinámicamente -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal para filtros de fecha -->
<div class="modal fade" id="modalFiltroFecha" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filtro por Fechas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="form-filtro-fecha">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="fecha_fin" class="form-label">Fecha Fin</label>
                            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <h6>Filtros rápidos:</h6>
                            <div class="btn-group w-100" role="group">
                                <button type="button" class="btn btn-outline-secondary filtro-rapido" data-dias="7">7 días</button>
                                <button type="button" class="btn btn-outline-secondary filtro-rapido" data-dias="30">30 días</button>
                                <button type="button" class="btn btn-outline-secondary filtro-rapido" data-dias="90">90 días</button>
                                <button type="button" class="btn btn-outline-secondary filtro-rapido" data-dias="365">1 año</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="aplicar-filtro">Aplicar Filtro</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    let filtroFechas = {
        inicio: new Date(Date.now() - 30 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
        fin: new Date().toISOString().split('T')[0]
    };
    
    // Inicializar
    cargarDatosResumen();
    configurarGraficos();
    configurarEventos();
    
    function cargarDatosResumen() {
        const params = new URLSearchParams({
            fecha_inicio: filtroFechas.inicio,
            fecha_fin: filtroFechas.fin
        });
        
        fetch(`/api/dashboard/resumen?${params}`, {
            headers: {
                'X-CSRF-Token': window.CONFIG.csrf_token
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                actualizarMetricas(data.metricas);
                actualizarGraficos(data.graficos);
                actualizarTablas(data.tablas);
                actualizarAlertas(data.alertas);
                actualizarTareas(data.tareas);
            }
        })
        .catch(error => console.error('Error:', error));
    }
    
    function configurarGraficos() {
        // Gráfico de ingresos
        const ctxIngresos = document.getElementById('ingresos-chart').getContext('2d');
        window.ingresosChart = new Chart(ctxIngresos, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Ingresos',
                    data: [],
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    tension: 0.4,
                    fill: true
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
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
        
        // Gráfico de planes
        const ctxPlanes = document.getElementById('planes-chart').getContext('2d');
        window.planesChart = new Chart(ctxPlanes, {
            type: 'doughnut',
            data: {
                labels: [],
                datasets: [{
                    data: [],
                    backgroundColor: [
                        '#0d6efd',
                        '#20c997',
                        '#fd7e14',
                        '#dc3545',
                        '#6f42c1'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
    
    function configurarEventos() {
        // Filtro de fechas
        document.getElementById('btn-filtro-fecha').addEventListener('click', function() {
            const modal = new bootstrap.Modal(document.getElementById('modalFiltroFecha'));
            document.getElementById('fecha_inicio').value = filtroFechas.inicio;
            document.getElementById('fecha_fin').value = filtroFechas.fin;
            modal.show();
        });
        
        // Filtros rápidos
        document.querySelectorAll('.filtro-rapido').forEach(btn => {
            btn.addEventListener('click', function() {
                const dias = parseInt(this.dataset.dias);
                const fechaFin = new Date();
                const fechaInicio = new Date(fechaFin.getTime() - dias * 24 * 60 * 60 * 1000);
                
                document.getElementById('fecha_inicio').value = fechaInicio.toISOString().split('T')[0];
                document.getElementById('fecha_fin').value = fechaFin.toISOString().split('T')[0];
            });
        });
        
        // Aplicar filtro
        document.getElementById('aplicar-filtro').addEventListener('click', function() {
            filtroFechas.inicio = document.getElementById('fecha_inicio').value;
            filtroFechas.fin = document.getElementById('fecha_fin').value;
            
            cargarDatosResumen();
            bootstrap.Modal.getInstance(document.getElementById('modalFiltroFecha')).hide();
            
            // Actualizar texto del botón
            const diff = Math.ceil((new Date(filtroFechas.fin) - new Date(filtroFechas.inicio)) / (1000 * 60 * 60 * 24));
            document.getElementById('btn-filtro-fecha').innerHTML = `
                <i class="fas fa-calendar-alt me-2"></i>
                Últimos ${diff} días
            `;
        });
        
        // Exportar PDF
        document.getElementById('btn-exportar').addEventListener('click', function() {
            const params = new URLSearchParams({
                fecha_inicio: filtroFechas.inicio,
                fecha_fin: filtroFechas.fin,
                formato: 'pdf'
            });
            
            window.open(`/api/dashboard/exportar?${params}`, '_blank');
        });
    }
    
    function actualizarMetricas(metricas) {
        Object.keys(metricas).forEach(key => {
            const elemento = document.getElementById(key);
            if (elemento) {
                if (key.includes('ingresos') || key.includes('precio')) {
                    elemento.textContent = '$' + parseInt(metricas[key]).toLocaleString();
                } else if (key.includes('porcentaje') || key.includes('tasa') || key.includes('satisfaccion')) {
                    elemento.textContent = parseFloat(metricas[key]).toFixed(1) + (key.includes('satisfaccion') ? '/5' : '%');
                } else {
                    elemento.textContent = parseInt(metricas[key]).toLocaleString();
                }
            }
        });
    }
    
    function actualizarGraficos(graficos) {
        if (graficos.ingresos && window.ingresosChart) {
            window.ingresosChart.data.labels = graficos.ingresos.labels;
            window.ingresosChart.data.datasets[0].data = graficos.ingresos.data;
            window.ingresosChart.update();
        }
        
        if (graficos.planes && window.planesChart) {
            window.planesChart.data.labels = graficos.planes.labels;
            window.planesChart.data.datasets[0].data = graficos.planes.data;
            window.planesChart.update();
        }
    }
    
    function actualizarTablas(tablas) {
        // Top nutriólogos
        if (tablas.nutriologos) {
            const tbody = document.getElementById('top-nutriologos');
            tbody.innerHTML = '';
            tablas.nutriologos.forEach(nutriologo => {
                tbody.innerHTML += `
                    <tr>
                        <td>${nutriologo.nombre}</td>
                        <td>${nutriologo.pacientes}</td>
                        <td>
                            <div class="rating">
                                ${generarEstrellas(nutriologo.calificacion)}
                                <small class="ms-1">${nutriologo.calificacion}</small>
                            </div>
                        </td>
                        <td>$${parseInt(nutriologo.ingresos).toLocaleString()}</td>
                    </tr>
                `;
            });
        }
        
        // Planes populares
        if (tablas.planes) {
            const tbody = document.getElementById('planes-populares');
            tbody.innerHTML = '';
            tablas.planes.forEach(plan => {
                tbody.innerHTML += `
                    <tr>
                        <td>${plan.nombre}</td>
                        <td>${plan.clientes}</td>
                        <td>
                            <span class="badge bg-success">${plan.exito}%</span>
                        </td>
                        <td>$${parseInt(plan.precio).toLocaleString()}</td>
                    </tr>
                `;
            });
        }
    }
    
    function actualizarAlertas(alertas) {
        const container = document.getElementById('alertas-sistema');
        container.innerHTML = '';
        
        if (!alertas || alertas.length === 0) {
            container.innerHTML = '<p class="text-muted text-center">No hay alertas activas</p>';
            return;
        }
        
        alertas.forEach(alerta => {
            const alertElement = document.createElement('div');
            alertElement.className = `alert alert-${alerta.tipo} alert-dismissible fade show`;
            alertElement.innerHTML = `
                <i class="fas ${alerta.icono} me-2"></i>
                <strong>${alerta.titulo}:</strong> ${alerta.mensaje}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            container.appendChild(alertElement);
        });
    }
    
    function actualizarTareas(tareas) {
        const container = document.getElementById('tareas-pendientes');
        container.innerHTML = '';
        
        if (!tareas || tareas.length === 0) {
            container.innerHTML = '<p class="text-muted text-center">No hay tareas pendientes</p>';
            return;
        }
        
        tareas.forEach(tarea => {
            const tareaElement = document.createElement('div');
            tareaElement.className = 'task-item';
            tareaElement.innerHTML = `
                <div class="task-content">
                    <h6 class="task-title">${tarea.titulo}</h6>
                    <p class="task-description text-muted">${tarea.descripcion}</p>
                    <small class="task-date">
                        <i class="fas fa-clock me-1"></i>
                        ${tarea.fecha}
                    </small>
                </div>
                <div class="task-actions">
                    <button class="btn btn-sm btn-outline-primary" onclick="completarTarea(${tarea.id})">
                        <i class="fas fa-check"></i>
                    </button>
                </div>
            `;
            container.appendChild(tareaElement);
        });
    }
    
    function generarEstrellas(calificacion) {
        let estrellas = '';
        for (let i = 1; i <= 5; i++) {
            if (i <= calificacion) {
                estrellas += '<i class="fas fa-star text-warning"></i>';
            } else {
                estrellas += '<i class="far fa-star text-muted"></i>';
            }
        }
        return estrellas;
    }
    
    // Función global para completar tareas
    window.completarTarea = function(tareaId) {
        fetch(`/api/tareas/${tareaId}/completar`, {
            method: 'POST',
            headers: {
                'X-CSRF-Token': window.CONFIG.csrf_token,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                cargarDatosResumen(); // Recargar datos
            }
        })
        .catch(error => console.error('Error:', error));
    };
});
</script>

<?php include VIEWS_PATH . '/layouts/footer.php'; ?>