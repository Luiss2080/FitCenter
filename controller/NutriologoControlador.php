<?php
/**
 * Controlador de Nutriólogo - CareCenter
 */

class NutriologoControlador {
    
    public function __construct() {
        // Verificar autenticación y permisos
        if (!Sesion::estaLogueado()) {
            header('Location: /login');
            exit;
        }
        
        $rolUsuario = Sesion::obtenerUsuario()['rol'];
        if (!in_array($rolUsuario, [ROL_NUTRIOLOGO, ROL_ADMIN])) {
            throw new Exception('Acceso denegado. Permisos de nutriólogo requeridos.');
        }
    }
    
    /**
     * Dashboard del nutriólogo
     */
    public function dashboard() {
        $titulo = 'Panel Nutriólogo - CareCenter';
        $usuarioId = Sesion::obtenerUsuario()['id'];
        
        // Estadísticas del nutriólogo
        $estadisticas = [
            'pacientes_asignados' => $this->contarPacientesAsignados($usuarioId),
            'consultas_hoy' => $this->contarConsultasHoy($usuarioId),
            'planes_activos' => $this->contarPlanesActivos($usuarioId),
            'consultas_semana' => $this->contarConsultasSemana($usuarioId)
        ];
        
        // Próximas citas
        $proximas_citas = $this->obtenerProximasCitas($usuarioId);
        
        // Pacientes recientes
        $pacientes_recientes = $this->obtenerPacientesRecientes($usuarioId);
        
        // Cargar vista
        $this->cargarVista('nutriologo/dashboard', [
            'titulo' => $titulo,
            'estadisticas' => $estadisticas,
            'proximas_citas' => $proximas_citas,
            'pacientes_recientes' => $pacientes_recientes
        ]);
    }
    
    /**
     * Contar pacientes asignados al nutriólogo
     */
    private function contarPacientesAsignados($nutriologoId) {
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("SELECT COUNT(*) FROM pacientes WHERE nutriologo_id = ? AND eliminado = 0");
            $stmt->execute([$nutriologoId]);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            Logger::error('Error al contar pacientes asignados: ' . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Contar consultas de hoy
     */
    private function contarConsultasHoy($nutriologoId) {
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("SELECT COUNT(*) FROM consultas 
                                 WHERE nutriologo_id = ? 
                                 AND DATE(fecha_consulta) = CURDATE()");
            $stmt->execute([$nutriologoId]);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            Logger::error('Error al contar consultas de hoy: ' . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Contar planes nutricionales activos
     */
    private function contarPlanesActivos($nutriologoId) {
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("SELECT COUNT(*) FROM planes_nutricionales 
                                 WHERE nutriologo_id = ? 
                                 AND estado = 'activo'");
            $stmt->execute([$nutriologoId]);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            Logger::error('Error al contar planes activos: ' . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Contar consultas de esta semana
     */
    private function contarConsultasSemana($nutriologoId) {
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("SELECT COUNT(*) FROM consultas 
                                 WHERE nutriologo_id = ? 
                                 AND WEEK(fecha_consulta) = WEEK(NOW())
                                 AND YEAR(fecha_consulta) = YEAR(NOW())");
            $stmt->execute([$nutriologoId]);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            Logger::error('Error al contar consultas de la semana: ' . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Obtener próximas citas del nutriólogo
     */
    private function obtenerProximasCitas($nutriologoId) {
        try {
            $db = Database::getInstance()->getConnection();
            $sql = "SELECT c.*, p.nombre as paciente_nombre, p.telefono
                    FROM consultas c
                    INNER JOIN pacientes p ON c.paciente_id = p.id
                    WHERE c.nutriologo_id = ?
                    AND c.fecha_consulta >= NOW()
                    AND c.estado = 'programada'
                    ORDER BY c.fecha_consulta ASC
                    LIMIT 5";
            
            $stmt = $db->prepare($sql);
            $stmt->execute([$nutriologoId]);
            return $stmt->fetchAll();
            
        } catch (PDOException $e) {
            Logger::error('Error al obtener próximas citas: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtener pacientes recientes
     */
    private function obtenerPacientesRecientes($nutriologoId) {
        try {
            $db = Database::getInstance()->getConnection();
            $sql = "SELECT * FROM pacientes 
                    WHERE nutriologo_id = ? 
                    AND eliminado = 0
                    ORDER BY created_at DESC 
                    LIMIT 5";
            
            $stmt = $db->prepare($sql);
            $stmt->execute([$nutriologoId]);
            return $stmt->fetchAll();
            
        } catch (PDOException $e) {
            Logger::error('Error al obtener pacientes recientes: ' . $e->getMessage());
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