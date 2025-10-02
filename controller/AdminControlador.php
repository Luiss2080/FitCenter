<?php
/**
 * Controlador de Administración - CareCenter
 */

class AdminControlador {
    
    public function __construct() {
        // Verificar autenticación y permisos de admin
        if (!Sesion::estaLogueado()) {
            header('Location: /login');
            exit;
        }
        
        if (Sesion::obtenerUsuario()['rol'] !== ROL_ADMIN) {
            throw new Exception('Acceso denegado. Permisos de administrador requeridos.');
        }
    }
    
    /**
     * Dashboard principal de administración
     */
    public function dashboard() {
        $titulo = 'Panel de Administración - CareCenter';
        
        // Estadísticas generales
        $estadisticas = [
            'usuarios_total' => $this->contarUsuarios(),
            'pacientes_total' => $this->contarPacientes(),
            'ordenes_hoy' => $this->contarOrdenesHoy(),
            'ingresos_mes' => $this->calcularIngresosMes(),
            'entregas_pendientes' => $this->contarEntregasPendientes()
        ];
        
        // Actividad reciente
        $actividad_reciente = $this->obtenerActividadReciente();
        
        // Cargar vista
        $this->cargarVista('admin/dashboard', [
            'titulo' => $titulo,
            'estadisticas' => $estadisticas,
            'actividad_reciente' => $actividad_reciente
        ]);
    }
    
    /**
     * Contar total de usuarios
     */
    private function contarUsuarios() {
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->query("SELECT COUNT(*) FROM usuarios WHERE eliminado = 0");
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            Logger::error('Error al contar usuarios: ' . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Contar total de pacientes
     */
    private function contarPacientes() {
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->query("SELECT COUNT(*) FROM pacientes WHERE eliminado = 0");
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            Logger::error('Error al contar pacientes: ' . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Contar órdenes de hoy
     */
    private function contarOrdenesHoy() {
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->query("SELECT COUNT(*) FROM ordenes WHERE DATE(created_at) = CURDATE()");
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            Logger::error('Error al contar órdenes de hoy: ' . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Calcular ingresos del mes actual
     */
    private function calcularIngresosMes() {
        try {
            $db = Database::getInstance()->getConnection();
            $sql = "SELECT SUM(total) FROM ordenes 
                    WHERE MONTH(created_at) = MONTH(NOW()) 
                    AND YEAR(created_at) = YEAR(NOW())
                    AND estado != 'cancelado'";
            $stmt = $db->query($sql);
            return $stmt->fetchColumn() ?: 0;
        } catch (PDOException $e) {
            Logger::error('Error al calcular ingresos del mes: ' . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Contar entregas pendientes
     */
    private function contarEntregasPendientes() {
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->query("SELECT COUNT(*) FROM entregas WHERE estado IN ('pendiente', 'preparando')");
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            Logger::error('Error al contar entregas pendientes: ' . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Obtener actividad reciente del sistema
     */
    private function obtenerActividadReciente() {
        try {
            $db = Database::getInstance()->getConnection();
            $sql = "SELECT 
                        'usuario' as tipo, 
                        CONCAT('Nuevo usuario: ', nombre) as descripcion,
                        created_at as fecha
                    FROM usuarios 
                    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                    
                    UNION ALL
                    
                    SELECT 
                        'orden' as tipo,
                        CONCAT('Nueva orden #', id) as descripcion,
                        created_at as fecha
                    FROM ordenes 
                    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                    
                    ORDER BY fecha DESC 
                    LIMIT 10";
                    
            $stmt = $db->query($sql);
            return $stmt->fetchAll();
            
        } catch (PDOException $e) {
            Logger::error('Error al obtener actividad reciente: ' . $e->getMessage());
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