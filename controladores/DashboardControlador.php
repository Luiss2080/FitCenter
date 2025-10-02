<?php
/**
 * Controlador principal del Dashboard para CareCenter
 */

require_once __DIR__ . '/../config/bootstrap.php';
require_once __DIR__ . '/../modelos/Usuario.php';
require_once __DIR__ . '/../modelos/Paciente.php';

class DashboardControlador {
    
    public function __construct() {
        // Verificar autenticación
        if (!Sesion::estaLogueado()) {
            header('Location: /login');
            exit;
        }
        
        if (!Sesion::verificarTiempoSesion()) {
            header('Location: /login');
            exit;
        }
    }
    
    public function index() {
        $usuario = Sesion::obtenerUsuario();
        $titulo = 'Dashboard - CareCenter';
        
        // Obtener estadísticas según el rol
        $estadisticas = $this->obtenerEstadisticas($usuario['rol']);
        
        require_once __DIR__ . '/../vistas/dashboard/index.php';
    }
    
    public function resumen() {
        $usuario = Sesion::obtenerUsuario();
        $titulo = 'Resumen - CareCenter';
        
        // Datos para el resumen detallado
        $resumen = $this->obtenerResumenDetallado($usuario['rol']);
        
        require_once __DIR__ . '/../vistas/dashboard/resumen.php';
    }
    
    private function obtenerEstadisticas($rolUsuario) {
        $estadisticas = [];
        
        try {
            switch ($rolUsuario) {
                case ROL_ADMIN:
                    $estadisticas = $this->estadisticasAdmin();
                    break;
                    
                case ROL_NUTRIOLOGO:
                    $estadisticas = $this->estadisticasNutriologo();
                    break;
                    
                case ROL_COCINA:
                    $estadisticas = $this->estadisticasCocina();
                    break;
                    
                case ROL_REPARTIDOR:
                    $estadisticas = $this->estadisticasRepartidor();
                    break;
                    
                case ROL_CLIENTE:
                    $estadisticas = $this->estadisticasCliente();
                    break;
                    
                default:
                    $estadisticas = ['error' => 'Rol no reconocido'];
            }
            
        } catch (Exception $e) {
            Logger::error('Error obteniendo estadísticas: ' . $e->getMessage());
            $estadisticas = ['error' => 'Error al cargar estadísticas'];
        }
        
        return $estadisticas;
    }
    
    private function estadisticasAdmin() {
        return [
            'total_usuarios' => 25,
            'total_pacientes' => 150,
            'ordenes_pendientes' => 12,
            'entregas_hoy' => 8,
            'ingresos_mes' => 45000,
            'planes_activos' => 78
        ];
    }
    
    private function estadisticasNutriologo() {
        $usuarioId = Sesion::obtener('usuario_id');
        
        return [
            'pacientes_asignados' => 35,
            'consultas_hoy' => 5,
            'planes_creados' => 28,
            'proxima_consulta' => '14:30'
        ];
    }
    
    private function estadisticasCocina() {
        return [
            'ordenes_pendientes' => 8,
            'en_preparacion' => 3,
            'completadas_hoy' => 15,
            'tiempo_promedio' => '25 min'
        ];
    }
    
    private function estadisticasRepartidor() {
        $usuarioId = Sesion::obtener('usuario_id');
        
        return [
            'entregas_pendientes' => 6,
            'entregas_completadas' => 12,
            'km_recorridos' => 45,
            'tiempo_promedio' => '18 min'
        ];
    }
    
    private function estadisticasCliente() {
        $usuarioId = Sesion::obtener('usuario_id');
        
        return [
            'plan_activo' => 'Plan Nutricional Premium',
            'proxima_entrega' => '2025-10-02 12:00',
            'comidas_consumidas' => 8,
            'peso_actual' => '72.5 kg'
        ];
    }
    
    private function obtenerResumenDetallado($rolUsuario) {
        $resumen = [];
        
        try {
            // Actividad reciente común
            $resumen['actividad_reciente'] = [
                ['tipo' => 'orden', 'descripcion' => 'Nueva orden creada #ORD-001', 'tiempo' => '5 min'],
                ['tipo' => 'entrega', 'descripcion' => 'Entrega completada', 'tiempo' => '15 min'],
                ['tipo' => 'usuario', 'descripcion' => 'Nuevo usuario registrado', 'tiempo' => '1 hora']
            ];
            
            // Datos específicos por rol
            switch ($rolUsuario) {
                case ROL_ADMIN:
                    $resumen['graficos'] = [
                        'ventas_mensuales' => $this->obtenerVentasMensuales(),
                        'usuarios_activos' => $this->obtenerUsuariosActivos()
                    ];
                    break;
                    
                case ROL_NUTRIOLOGO:
                    $resumen['proximas_consultas'] = $this->obtenerProximasConsultas();
                    $resumen['pacientes_criticos'] = $this->obtenerPacientesCriticos();
                    break;
                    
                case ROL_COCINA:
                    $resumen['menu_hoy'] = $this->obtenerMenuHoy();
                    $resumen['ingredientes_bajo_stock'] = $this->obtenerIngredientesBajoStock();
                    break;
                    
                case ROL_REPARTIDOR:
                    $resumen['ruta_optimizada'] = $this->obtenerRutaOptimizada();
                    $resumen['entregas_programadas'] = $this->obtenerEntregasProgramadas();
                    break;
                    
                case ROL_CLIENTE:
                    $resumen['progreso_peso'] = $this->obtenerProgresoCliente();
                    $resumen['proximas_comidas'] = $this->obtenerProximasComidas();
                    break;
            }
            
        } catch (Exception $e) {
            Logger::error('Error obteniendo resumen: ' . $e->getMessage());
            $resumen['error'] = 'Error al cargar resumen detallado';
        }
        
        return $resumen;
    }
    
    // Métodos auxiliares para obtener datos específicos
    private function obtenerVentasMensuales() {
        return [
            'enero' => 25000, 'febrero' => 28000, 'marzo' => 32000,
            'abril' => 30000, 'mayo' => 35000, 'junio' => 38000
        ];
    }
    
    private function obtenerUsuariosActivos() {
        return 150;
    }
    
    private function obtenerProximasConsultas() {
        return [
            ['paciente' => 'María González', 'hora' => '14:30', 'tipo' => 'Seguimiento'],
            ['paciente' => 'Carlos López', 'hora' => '16:00', 'tipo' => 'Primera consulta']
        ];
    }
    
    private function obtenerPacientesCriticos() {
        return [
            ['nombre' => 'Ana Martín', 'motivo' => 'Peso por debajo del objetivo']
        ];
    }
    
    private function obtenerMenuHoy() {
        return [
            'desayuno' => 'Avena con frutas',
            'almuerzo' => 'Pechuga a la plancha con ensalada',
            'cena' => 'Salmón con verduras'
        ];
    }
    
    private function obtenerIngredientesBajoStock() {
        return ['Quinoa', 'Pechuga de pollo', 'Brócoli'];
    }
    
    private function obtenerRutaOptimizada() {
        return 'Ruta A: 6 entregas, 23 km estimados';
    }
    
    private function obtenerEntregasProgramadas() {
        return [
            ['cliente' => 'Pedro Ruiz', 'direccion' => 'Calle 123', 'hora' => '12:30'],
            ['cliente' => 'Laura Soto', 'direccion' => 'Av. Principal 456', 'hora' => '13:15']
        ];
    }
    
    private function obtenerProgresoCliente() {
        return [
            'peso_inicial' => 75.0,
            'peso_actual' => 72.5,
            'objetivo' => 70.0,
            'progreso_porcentaje' => 50
        ];
    }
    
    private function obtenerProximasComidas() {
        return [
            ['tipo' => 'Almuerzo', 'plato' => 'Ensalada César con pollo', 'hora' => '13:00'],
            ['tipo' => 'Cena', 'plato' => 'Pescado al vapor', 'hora' => '19:30']
        ];
    }
}