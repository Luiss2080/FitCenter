<?php
/**
 * Controlador de Log de Auditoría - CareCenter
 * Maneja el registro y consulta de eventos del sistema
 */

class LogAuditoriaControlador {
    
    public function __construct() {
        // Verificar autenticación y permisos de administrador
        if (!Sesion::estaLogueado()) {
            header('Location: /login');
            exit;
        }
        
        $rolUsuario = Sesion::obtenerUsuario()['rol'];
        if ($rolUsuario !== ROL_ADMIN) {
            throw new Exception('Acceso denegado. Solo administradores pueden acceder al log de auditoría.');
        }
    }
    
    /**
     * Mostrar lista de logs de auditoría
     */
    public function index() {
        $titulo = 'Log de Auditoría - CareCenter';
        
        // Parámetros de filtrado
        $filtros = [
            'fecha_inicio' => $_GET['fecha_inicio'] ?? date('Y-m-d', strtotime('-7 days')),
            'fecha_fin' => $_GET['fecha_fin'] ?? date('Y-m-d'),
            'usuario_id' => $_GET['usuario_id'] ?? null,
            'accion' => $_GET['accion'] ?? null,
            'tabla' => $_GET['tabla'] ?? null,
            'nivel' => $_GET['nivel'] ?? null
        ];
        
        // Paginación
        $pagina = intval($_GET['pagina'] ?? 1);
        $limite = 50;
        $offset = ($pagina - 1) * $limite;
        
        // Obtener logs
        $logs = $this->obtenerLogs($filtros, $limite, $offset);
        $totalLogs = $this->contarLogs($filtros);
        $totalPaginas = ceil($totalLogs / $limite);
        
        // Obtener datos para filtros
        $usuarios = $this->obtenerUsuariosParaFiltro();
        $acciones = $this->obtenerAccionesDisponibles();
        $tablas = $this->obtenerTablasDisponibles();
        
        $this->cargarVista('admin/auditoria/index', [
            'titulo' => $titulo,
            'logs' => $logs,
            'filtros' => $filtros,
            'usuarios' => $usuarios,
            'acciones' => $acciones,
            'tablas' => $tablas,
            'pagina_actual' => $pagina,
            'total_paginas' => $totalPaginas,
            'total_registros' => $totalLogs
        ]);
    }
    
    /**
     * Registrar evento de auditoría
     */
    public static function registrarEvento($accion, $tabla, $registro_id = null, $datos_anteriores = null, $datos_nuevos = null, $nivel = 'INFO') {
        try {
            $db = Database::getInstance()->getConnection();
            
            $usuario_id = Sesion::estaLogueado() ? Sesion::obtenerUsuario()['id'] : null;
            $ip_usuario = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
            $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'desconocido';
            
            $sql = "INSERT INTO log_auditoria (
                        usuario_id, accion, tabla, registro_id, 
                        datos_anteriores, datos_nuevos, nivel,
                        ip_usuario, user_agent, created_at
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
            
            $stmt = $db->prepare($sql);
            $stmt->execute([
                $usuario_id,
                $accion,
                $tabla,
                $registro_id,
                $datos_anteriores ? json_encode($datos_anteriores) : null,
                $datos_nuevos ? json_encode($datos_nuevos) : null,
                $nivel,
                $ip_usuario,
                $user_agent
            ]);
            
            return true;
            
        } catch (PDOException $e) {
            // Solo log en archivo si falla la BD
            error_log("Error al registrar auditoría: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtener logs según filtros
     */
    private function obtenerLogs($filtros, $limite, $offset) {
        try {
            $db = Database::getInstance()->getConnection();
            
            $where = "WHERE 1=1";
            $params = [];
            
            if ($filtros['fecha_inicio']) {
                $where .= " AND DATE(l.created_at) >= ?";
                $params[] = $filtros['fecha_inicio'];
            }
            
            if ($filtros['fecha_fin']) {
                $where .= " AND DATE(l.created_at) <= ?";
                $params[] = $filtros['fecha_fin'];
            }
            
            if ($filtros['usuario_id']) {
                $where .= " AND l.usuario_id = ?";
                $params[] = $filtros['usuario_id'];
            }
            
            if ($filtros['accion']) {
                $where .= " AND l.accion = ?";
                $params[] = $filtros['accion'];
            }
            
            if ($filtros['tabla']) {
                $where .= " AND l.tabla = ?";
                $params[] = $filtros['tabla'];
            }
            
            if ($filtros['nivel']) {
                $where .= " AND l.nivel = ?";
                $params[] = $filtros['nivel'];
            }
            
            $sql = "SELECT l.*, u.nombre as usuario_nombre, u.email as usuario_email
                    FROM log_auditoria l
                    LEFT JOIN usuarios u ON l.usuario_id = u.id
                    {$where}
                    ORDER BY l.created_at DESC
                    LIMIT ? OFFSET ?";
            
            $params[] = $limite;
            $params[] = $offset;
            
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetchAll();
            
        } catch (PDOException $e) {
            Logger::error('Error al obtener logs de auditoría: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Contar logs según filtros
     */
    private function contarLogs($filtros) {
        try {
            $db = Database::getInstance()->getConnection();
            
            $where = "WHERE 1=1";
            $params = [];
            
            if ($filtros['fecha_inicio']) {
                $where .= " AND DATE(created_at) >= ?";
                $params[] = $filtros['fecha_inicio'];
            }
            
            if ($filtros['fecha_fin']) {
                $where .= " AND DATE(created_at) <= ?";
                $params[] = $filtros['fecha_fin'];
            }
            
            if ($filtros['usuario_id']) {
                $where .= " AND usuario_id = ?";
                $params[] = $filtros['usuario_id'];
            }
            
            if ($filtros['accion']) {
                $where .= " AND accion = ?";
                $params[] = $filtros['accion'];
            }
            
            if ($filtros['tabla']) {
                $where .= " AND tabla = ?";
                $params[] = $filtros['tabla'];
            }
            
            if ($filtros['nivel']) {
                $where .= " AND nivel = ?";
                $params[] = $filtros['nivel'];
            }
            
            $sql = "SELECT COUNT(*) FROM log_auditoria {$where}";
            
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetchColumn();
            
        } catch (PDOException $e) {
            Logger::error('Error al contar logs de auditoría: ' . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Obtener usuarios para filtro
     */
    private function obtenerUsuariosParaFiltro() {
        try {
            $db = Database::getInstance()->getConnection();
            $sql = "SELECT DISTINCT u.id, u.nombre, u.email
                    FROM usuarios u
                    INNER JOIN log_auditoria l ON u.id = l.usuario_id
                    ORDER BY u.nombre";
            
            $stmt = $db->query($sql);
            return $stmt->fetchAll();
            
        } catch (PDOException $e) {
            Logger::error('Error al obtener usuarios para filtro: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtener acciones disponibles
     */
    private function obtenerAccionesDisponibles() {
        return [
            'CREATE' => 'Crear',
            'UPDATE' => 'Actualizar', 
            'DELETE' => 'Eliminar',
            'LOGIN' => 'Inicio de Sesión',
            'LOGOUT' => 'Cierre de Sesión',
            'VIEW' => 'Consultar',
            'EXPORT' => 'Exportar',
            'IMPORT' => 'Importar'
        ];
    }
    
    /**
     * Obtener tablas disponibles
     */
    private function obtenerTablasDisponibles() {
        try {
            $db = Database::getInstance()->getConnection();
            $sql = "SELECT DISTINCT tabla FROM log_auditoria ORDER BY tabla";
            
            $stmt = $db->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            return array_combine($result, $result);
            
        } catch (PDOException $e) {
            Logger::error('Error al obtener tablas disponibles: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Exportar logs a CSV
     */
    public function exportar() {
        $filtros = [
            'fecha_inicio' => $_GET['fecha_inicio'] ?? date('Y-m-d', strtotime('-30 days')),
            'fecha_fin' => $_GET['fecha_fin'] ?? date('Y-m-d'),
            'usuario_id' => $_GET['usuario_id'] ?? null,
            'accion' => $_GET['accion'] ?? null,
            'tabla' => $_GET['tabla'] ?? null,
            'nivel' => $_GET['nivel'] ?? null
        ];
        
        $logs = $this->obtenerLogs($filtros, 10000, 0); // Máximo 10,000 registros
        
        $filename = 'auditoria_' . date('Y-m-d_H-i-s') . '.csv';
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Encabezados CSV
        fputcsv($output, [
            'ID', 'Fecha/Hora', 'Usuario', 'Acción', 'Tabla', 
            'Registro ID', 'Nivel', 'IP', 'User Agent'
        ]);
        
        // Datos
        foreach ($logs as $log) {
            fputcsv($output, [
                $log['id'],
                $log['created_at'],
                $log['usuario_nombre'] ?? 'Sistema',
                $log['accion'],
                $log['tabla'],
                $log['registro_id'],
                $log['nivel'],
                $log['ip_usuario'],
                substr($log['user_agent'], 0, 100)
            ]);
        }
        
        fclose($output);
        exit;
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