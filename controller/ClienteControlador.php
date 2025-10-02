<?php
/**
 * Controlador de Cliente - CareCenter
 */

class ClienteControlador {
    
    public function __construct() {
        // Verificar autenticación y permisos
        if (!Sesion::estaLogueado()) {
            header('Location: /login');
            exit;
        }
        
        $rolUsuario = Sesion::obtenerUsuario()['rol'];
        if (!in_array($rolUsuario, [ROL_CLIENTE, ROL_ADMIN])) {
            throw new Exception('Acceso denegado. Permisos de cliente requeridos.');
        }
    }
    
    /**
     * Dashboard del cliente
     */
    public function dashboard() {
        $titulo = 'Mi Panel - CareCenter';
        $usuarioId = Sesion::obtenerUsuario()['id'];
        
        // Información del cliente
        $plan_activo = $this->obtenerPlanActivo($usuarioId);
        $proxima_entrega = $this->obtenerProximaEntrega($usuarioId);
        $progreso_nutricional = $this->obtenerProgresoNutricional($usuarioId);
        
        // Estadísticas del cliente
        $estadisticas = [
            'entregas_mes' => $this->contarEntregasMes($usuarioId),
            'comidas_completadas' => $this->contarComidasCompletadas($usuarioId),
            'consultas_realizadas' => $this->contarConsultasRealizadas($usuarioId),
            'dias_plan' => $this->calcularDiasPlan($usuarioId)
        ];
        
        // Historial de entregas recientes
        $entregas_recientes = $this->obtenerEntregasRecientes($usuarioId);
        
        // Cargar vista
        $this->cargarVista('cliente/dashboard', [
            'titulo' => $titulo,
            'plan_activo' => $plan_activo,
            'proxima_entrega' => $proxima_entrega,
            'progreso_nutricional' => $progreso_nutricional,
            'estadisticas' => $estadisticas,
            'entregas_recientes' => $entregas_recientes
        ]);
    }
    
    /**
     * Obtener plan nutricional activo
     */
    private function obtenerPlanActivo($clienteId) {
        try {
            $db = Database::getInstance()->getConnection();
            $sql = "SELECT p.*, u.nombre as nutriologo_nombre
                    FROM planes_nutricionales p
                    LEFT JOIN usuarios u ON p.nutriologo_id = u.id
                    WHERE p.cliente_id = ? 
                    AND p.estado = 'activo'
                    ORDER BY p.created_at DESC 
                    LIMIT 1";
            
            $stmt = $db->prepare($sql);
            $stmt->execute([$clienteId]);
            return $stmt->fetch();
            
        } catch (PDOException $e) {
            Logger::error('Error al obtener plan activo: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Obtener próxima entrega
     */
    private function obtenerProximaEntrega($clienteId) {
        try {
            $db = Database::getInstance()->getConnection();
            $sql = "SELECT e.*, d.direccion_completa
                    FROM entregas e
                    LEFT JOIN direcciones d ON e.direccion_id = d.id
                    WHERE e.cliente_id = ?
                    AND e.fecha_programada >= CURDATE()
                    AND e.estado IN ('programada', 'preparando', 'en_camino')
                    ORDER BY e.fecha_programada ASC, e.hora_programada ASC
                    LIMIT 1";
            
            $stmt = $db->prepare($sql);
            $stmt->execute([$clienteId]);
            return $stmt->fetch();
            
        } catch (PDOException $e) {
            Logger::error('Error al obtener próxima entrega: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Obtener progreso nutricional
     */
    private function obtenerProgresoNutricional($clienteId) {
        try {
            $db = Database::getInstance()->getConnection();
            
            // Obtener mediciones más recientes
            $sql = "SELECT * FROM mediciones 
                    WHERE paciente_id = (
                        SELECT id FROM pacientes WHERE usuario_id = ? LIMIT 1
                    )
                    ORDER BY fecha_medicion DESC 
                    LIMIT 2";
            
            $stmt = $db->prepare($sql);
            $stmt->execute([$clienteId]);
            $mediciones = $stmt->fetchAll();
            
            if (count($mediciones) >= 2) {
                $actual = $mediciones[0];
                $anterior = $mediciones[1];
                
                return [
                    'peso_actual' => $actual['peso'],
                    'peso_anterior' => $anterior['peso'],
                    'diferencia_peso' => $actual['peso'] - $anterior['peso'],
                    'imc_actual' => $actual['imc'],
                    'fecha_medicion' => $actual['fecha_medicion']
                ];
            } else if (count($mediciones) == 1) {
                return [
                    'peso_actual' => $mediciones[0]['peso'],
                    'imc_actual' => $mediciones[0]['imc'],
                    'fecha_medicion' => $mediciones[0]['fecha_medicion']
                ];
            }
            
            return null;
            
        } catch (PDOException $e) {
            Logger::error('Error al obtener progreso nutricional: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Contar entregas del mes
     */
    private function contarEntregasMes($clienteId) {
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("SELECT COUNT(*) FROM entregas 
                                 WHERE cliente_id = ? 
                                 AND MONTH(fecha_programada) = MONTH(NOW())
                                 AND YEAR(fecha_programada) = YEAR(NOW())");
            $stmt->execute([$clienteId]);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            Logger::error('Error al contar entregas del mes: ' . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Contar comidas completadas
     */
    private function contarComidasCompletadas($clienteId) {
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("SELECT COUNT(*) FROM entregas 
                                 WHERE cliente_id = ? 
                                 AND estado = 'entregado'");
            $stmt->execute([$clienteId]);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            Logger::error('Error al contar comidas completadas: ' . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Contar consultas realizadas
     */
    private function contarConsultasRealizadas($clienteId) {
        try {
            $db = Database::getInstance()->getConnection();
            $sql = "SELECT COUNT(*) FROM consultas 
                    WHERE paciente_id = (
                        SELECT id FROM pacientes WHERE usuario_id = ? LIMIT 1
                    )
                    AND estado = 'completada'";
            $stmt = $db->prepare($sql);
            $stmt->execute([$clienteId]);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            Logger::error('Error al contar consultas realizadas: ' . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Calcular días en el plan
     */
    private function calcularDiasPlan($clienteId) {
        try {
            $plan = $this->obtenerPlanActivo($clienteId);
            if ($plan) {
                $fecha_inicio = new DateTime($plan['fecha_inicio']);
                $fecha_actual = new DateTime();
                $diferencia = $fecha_actual->diff($fecha_inicio);
                return $diferencia->days;
            }
            return 0;
        } catch (Exception $e) {
            Logger::error('Error al calcular días del plan: ' . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Obtener entregas recientes
     */
    private function obtenerEntregasRecientes($clienteId) {
        try {
            $db = Database::getInstance()->getConnection();
            $sql = "SELECT e.*, d.direccion_completa
                    FROM entregas e
                    LEFT JOIN direcciones d ON e.direccion_id = d.id
                    WHERE e.cliente_id = ?
                    ORDER BY e.fecha_programada DESC, e.hora_programada DESC
                    LIMIT 5";
            
            $stmt = $db->prepare($sql);
            $stmt->execute([$clienteId]);
            return $stmt->fetchAll();
            
        } catch (PDOException $e) {
            Logger::error('Error al obtener entregas recientes: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Cargar vista
     */
    private function cargarVista($vista, $datos = []) {
        extract($datos);
        $archivoVista = VIEWS_PATH . "/{$vista}.php";
        
        if (file_exists($archivoVista)) {
            include $archivoVista;
        } else {
            throw new Exception("Vista no encontrada: {$vista}");
        }
    }
}