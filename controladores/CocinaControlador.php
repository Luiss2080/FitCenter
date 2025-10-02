<?php
/**
 * Controlador de Cocina - CareCenter
 */

class CocinaControlador {
    
    public function __construct() {
        // Verificar autenticación y permisos
        if (!Sesion::estaLogueado()) {
            header('Location: /login');
            exit;
        }
        
        $rolUsuario = Sesion::obtenerUsuario()['rol'];
        if (!in_array($rolUsuario, [ROL_COCINA, ROL_ADMIN])) {
            throw new Exception('Acceso denegado. Permisos de cocina requeridos.');
        }
    }
    
    /**
     * Dashboard de cocina
     */
    public function dashboard() {
        $titulo = 'Panel de Cocina - CareCenter';
        
        // Estadísticas de cocina
        $estadisticas = [
            'ordenes_pendientes' => $this->contarOrdenesPendientes(),
            'ordenes_preparando' => $this->contarOrdenesPreparando(),
            'ordenes_listas' => $this->contarOrdenesListas(),
            'ordenes_completadas_hoy' => $this->contarOrdenesCompletadasHoy()
        ];
        
        // Órdenes por preparar
        $ordenes_pendientes = $this->obtenerOrdenesPendientes();
        
        // Órdenes en preparación
        $ordenes_preparando = $this->obtenerOrdenesPreparando();
        
        // Cargar vista
        $this->cargarVista('cocina/dashboard', [
            'titulo' => $titulo,
            'estadisticas' => $estadisticas,
            'ordenes_pendientes' => $ordenes_pendientes,
            'ordenes_preparando' => $ordenes_preparando
        ]);
    }
    
    /**
     * Contar órdenes pendientes
     */
    private function contarOrdenesPendientes() {
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->query("SELECT COUNT(*) FROM ordenes WHERE estado = 'pendiente'");
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            Logger::error('Error al contar órdenes pendientes: ' . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Contar órdenes preparando
     */
    private function contarOrdenesPreparando() {
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->query("SELECT COUNT(*) FROM ordenes WHERE estado = 'preparando'");
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            Logger::error('Error al contar órdenes preparando: ' . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Contar órdenes listas
     */
    private function contarOrdenesListas() {
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->query("SELECT COUNT(*) FROM ordenes WHERE estado = 'listo'");
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            Logger::error('Error al contar órdenes listas: ' . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Contar órdenes completadas hoy
     */
    private function contarOrdenesCompletadasHoy() {
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->query("SELECT COUNT(*) FROM ordenes 
                               WHERE estado = 'completado' 
                               AND DATE(updated_at) = CURDATE()");
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            Logger::error('Error al contar órdenes completadas hoy: ' . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Obtener órdenes pendientes con detalles
     */
    private function obtenerOrdenesPendientes() {
        try {
            $db = Database::getInstance()->getConnection();
            $sql = "SELECT o.*, p.nombre as paciente_nombre, 
                           u.nombre as cliente_nombre
                    FROM ordenes o
                    LEFT JOIN pacientes p ON o.paciente_id = p.id
                    LEFT JOIN usuarios u ON o.cliente_id = u.id
                    WHERE o.estado = 'pendiente'
                    ORDER BY o.created_at ASC
                    LIMIT 10";
            
            $stmt = $db->query($sql);
            return $stmt->fetchAll();
            
        } catch (PDOException $e) {
            Logger::error('Error al obtener órdenes pendientes: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtener órdenes en preparación
     */
    private function obtenerOrdenesPreparando() {
        try {
            $db = Database::getInstance()->getConnection();
            $sql = "SELECT o.*, p.nombre as paciente_nombre, 
                           u.nombre as cliente_nombre,
                           TIMESTAMPDIFF(MINUTE, o.inicio_preparacion, NOW()) as tiempo_preparacion
                    FROM ordenes o
                    LEFT JOIN pacientes p ON o.paciente_id = p.id
                    LEFT JOIN usuarios u ON o.cliente_id = u.id
                    WHERE o.estado = 'preparando'
                    ORDER BY o.inicio_preparacion ASC";
            
            $stmt = $db->query($sql);
            return $stmt->fetchAll();
            
        } catch (PDOException $e) {
            Logger::error('Error al obtener órdenes preparando: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Iniciar preparación de orden
     */
    public function iniciarOrden() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            throw new Exception('Método no permitido');
        }
        
        $ordenId = $_POST['orden_id'] ?? null;
        if (!$ordenId) {
            throw new Exception('ID de orden requerido');
        }
        
        try {
            $db = Database::getInstance()->getConnection();
            $sql = "UPDATE ordenes 
                    SET estado = 'preparando', 
                        inicio_preparacion = NOW()
                    WHERE id = ? AND estado = 'pendiente'";
            
            $stmt = $db->prepare($sql);
            $resultado = $stmt->execute([$ordenId]);
            
            if ($resultado) {
                Logger::info("Orden {$ordenId} iniciada en cocina");
                
                if ($this->esAjax()) {
                    echo json_encode(['success' => true, 'mensaje' => 'Orden iniciada correctamente']);
                    exit;
                } else {
                    Sesion::flash('success', 'Orden iniciada correctamente');
                    header('Location: /cocina/dashboard');
                    exit;
                }
            } else {
                throw new Exception('No se pudo iniciar la orden');
            }
            
        } catch (PDOException $e) {
            Logger::error('Error al iniciar orden: ' . $e->getMessage());
            throw new Exception('Error al iniciar la orden');
        }
    }
    
    /**
     * Completar orden
     */
    public function completarOrden() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            throw new Exception('Método no permitido');
        }
        
        $ordenId = $_POST['orden_id'] ?? null;
        if (!$ordenId) {
            throw new Exception('ID de orden requerido');
        }
        
        try {
            $db = Database::getInstance()->getConnection();
            $sql = "UPDATE ordenes 
                    SET estado = 'listo', 
                        completado_cocina = NOW()
                    WHERE id = ? AND estado = 'preparando'";
            
            $stmt = $db->prepare($sql);
            $resultado = $stmt->execute([$ordenId]);
            
            if ($resultado) {
                Logger::info("Orden {$ordenId} completada en cocina");
                
                if ($this->esAjax()) {
                    echo json_encode(['success' => true, 'mensaje' => 'Orden completada correctamente']);
                    exit;
                } else {
                    Sesion::flash('success', 'Orden completada correctamente');
                    header('Location: /cocina/dashboard');
                    exit;
                }
            } else {
                throw new Exception('No se pudo completar la orden');
            }
            
        } catch (PDOException $e) {
            Logger::error('Error al completar orden: ' . $e->getMessage());
            throw new Exception('Error al completar la orden');
        }
    }
    
    /**
     * Verificar si es petición AJAX
     */
    private function esAjax() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
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