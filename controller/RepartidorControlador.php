<?php
/**
 * Controlador de Repartidor - CareCenter
 */

class RepartidorControlador {
    
    public function __construct() {
        // Verificar autenticación y permisos
        if (!Sesion::estaLogueado()) {
            header('Location: /login');
            exit;
        }
        
        $rolUsuario = Sesion::obtenerUsuario()['rol'];
        if (!in_array($rolUsuario, [ROL_REPARTIDOR, ROL_ADMIN])) {
            throw new Exception('Acceso denegado. Permisos de repartidor requeridos.');
        }
    }
    
    /**
     * Dashboard del repartidor
     */
    public function dashboard() {
        $titulo = 'Panel de Entregas - CareCenter';
        $usuarioId = Sesion::obtenerUsuario()['id'];
        
        // Estadísticas del repartidor
        $estadisticas = [
            'entregas_asignadas' => $this->contarEntregasAsignadas($usuarioId),
            'entregas_en_curso' => $this->contarEntregasEnCurso($usuarioId),
            'entregas_completadas_hoy' => $this->contarEntregasCompletadasHoy($usuarioId),
            'entregas_semana' => $this->contarEntregasSemana($usuarioId)
        ];
        
        // Entregas del día
        $entregas_hoy = $this->obtenerEntregasHoy($usuarioId);
        
        // Cargar vista
        $this->cargarVista('repartidor/dashboard', [
            'titulo' => $titulo,
            'estadisticas' => $estadisticas,
            'entregas_hoy' => $entregas_hoy
        ]);
    }
    
    /**
     * Contar entregas asignadas
     */
    private function contarEntregasAsignadas($repartidorId) {
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("SELECT COUNT(*) FROM entregas 
                                 WHERE repartidor_id = ? AND estado = 'asignada'");
            $stmt->execute([$repartidorId]);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            Logger::error('Error al contar entregas asignadas: ' . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Contar entregas en curso
     */
    private function contarEntregasEnCurso($repartidorId) {
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("SELECT COUNT(*) FROM entregas 
                                 WHERE repartidor_id = ? AND estado = 'en_camino'");
            $stmt->execute([$repartidorId]);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            Logger::error('Error al contar entregas en curso: ' . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Contar entregas completadas hoy
     */
    private function contarEntregasCompletadasHoy($repartidorId) {
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("SELECT COUNT(*) FROM entregas 
                                 WHERE repartidor_id = ? 
                                 AND estado = 'entregado'
                                 AND DATE(fecha_entrega) = CURDATE()");
            $stmt->execute([$repartidorId]);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            Logger::error('Error al contar entregas completadas hoy: ' . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Contar entregas de la semana
     */
    private function contarEntregasSemana($repartidorId) {
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("SELECT COUNT(*) FROM entregas 
                                 WHERE repartidor_id = ? 
                                 AND estado = 'entregado'
                                 AND WEEK(fecha_entrega) = WEEK(NOW())
                                 AND YEAR(fecha_entrega) = YEAR(NOW())");
            $stmt->execute([$repartidorId]);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            Logger::error('Error al contar entregas de la semana: ' . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Obtener entregas del día
     */
    private function obtenerEntregasHoy($repartidorId) {
        try {
            $db = Database::getInstance()->getConnection();
            $sql = "SELECT e.*, u.nombre as cliente_nombre, 
                           d.direccion_completa, d.referencias
                    FROM entregas e
                    INNER JOIN usuarios u ON e.cliente_id = u.id
                    INNER JOIN direcciones d ON e.direccion_id = d.id
                    WHERE e.repartidor_id = ?
                    AND DATE(e.fecha_programada) = CURDATE()
                    ORDER BY e.hora_programada ASC";
            
            $stmt = $db->prepare($sql);
            $stmt->execute([$repartidorId]);
            return $stmt->fetchAll();
            
        } catch (PDOException $e) {
            Logger::error('Error al obtener entregas del día: ' . $e->getMessage());
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