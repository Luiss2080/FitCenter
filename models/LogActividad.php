<?php
/**
 * Modelo LogActividad para FitCenter
 */

require_once 'BaseModel.php';

class LogActividad extends BaseModel {
    protected $table = 'log_actividades';
    
    /**
     * Registrar nueva actividad
     */
    public function registrar($usuarioId, $email, $accion, $descripcion = '', $ipAddress = null, $tipoEvento = 'general', $resultado = 'exitoso') {
        $data = [
            'usuario_id' => $usuarioId,
            'email' => $email,
            'accion' => $accion,
            'descripcion' => $descripcion,
            'ip_address' => $ipAddress ?: $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'tipo_evento' => $tipoEvento,
            'resultado' => $resultado,
            'creado_en' => date('Y-m-d H:i:s')
        ];
        
        return $this->create($data);
    }
    
    /**
     * Obtener actividades por usuario
     */
    public function getByUser($usuarioId, $limit = 20) {
        return $this->findWhere(
            'usuario_id = :usuario_id ORDER BY creado_en DESC LIMIT ' . (int)$limit,
            ['usuario_id' => $usuarioId]
        );
    }
    
    /**
     * Obtener actividades por tipo de evento
     */
    public function getByTipo($tipoEvento, $limit = 50) {
        return $this->findWhere(
            'tipo_evento = :tipo ORDER BY creado_en DESC LIMIT ' . (int)$limit,
            ['tipo' => $tipoEvento]
        );
    }
    
    /**
     * Obtener actividades recientes
     */
    public function getRecientes($limit = 50) {
        $stmt = $this->query("
            SELECT l.*, u.nombre, u.rol as tipo_usuario
            FROM log_actividades l
            LEFT JOIN usuarios u ON l.usuario_id = u.id
            ORDER BY l.creado_en DESC
            LIMIT " . (int)$limit
        );
        
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener actividades por fecha
     */
    public function getByDate($fecha) {
        return $this->findWhere(
            'DATE(creado_en) = :fecha ORDER BY creado_en DESC',
            ['fecha' => $fecha]
        );
    }
    
    /**
     * Obtener actividades en rango de fechas
     */
    public function getByDateRange($fechaInicio, $fechaFin) {
        return $this->findWhere(
            'DATE(creado_en) BETWEEN :inicio AND :fin ORDER BY creado_en DESC',
            ['inicio' => $fechaInicio, 'fin' => $fechaFin]
        );
    }
    
    /**
     * Buscar actividades por IP
     */
    public function getByIp($ipAddress) {
        return $this->findWhere(
            'ip_address = :ip ORDER BY creado_en DESC',
            ['ip' => $ipAddress]
        );
    }
    
    /**
     * Obtener estadísticas de actividades
     */
    public function getStats($dias = 30) {
        $stats = [];
        
        // Total de actividades
        $stats['total'] = $this->count();
        
        // Actividades por tipo
        $stmt = $this->query("
            SELECT tipo_evento, COUNT(*) as cantidad
            FROM log_actividades 
            WHERE creado_en >= DATE_SUB(NOW(), INTERVAL ? DAY)
            GROUP BY tipo_evento
            ORDER BY cantidad DESC
        ", [$dias]);
        $stats['por_tipo'] = $stmt->fetchAll();
        
        // Actividades por resultado
        $stmt = $this->query("
            SELECT resultado, COUNT(*) as cantidad
            FROM log_actividades 
            WHERE creado_en >= DATE_SUB(NOW(), INTERVAL ? DAY)
            GROUP BY resultado
        ", [$dias]);
        $stats['por_resultado'] = $stmt->fetchAll();
        
        // Actividades por día (últimos 7 días)
        $stmt = $this->query("
            SELECT DATE(creado_en) as fecha, COUNT(*) as cantidad
            FROM log_actividades 
            WHERE creado_en >= DATE_SUB(NOW(), INTERVAL 7 DAY)
            GROUP BY DATE(creado_en)
            ORDER BY fecha DESC
        ");
        $stats['por_dia'] = $stmt->fetchAll();
        
        // IPs más activas
        $stmt = $this->query("
            SELECT ip_address, COUNT(*) as cantidad
            FROM log_actividades 
            WHERE creado_en >= DATE_SUB(NOW(), INTERVAL ? DAY)
            GROUP BY ip_address
            ORDER BY cantidad DESC
            LIMIT 10
        ", [$dias]);
        $stats['ips_activas'] = $stmt->fetchAll();
        
        return $stats;
    }
    
    /**
     * Limpiar logs antiguos
     */
    public function limpiarAntiguos($dias = 90) {
        $stmt = $this->query("
            DELETE FROM log_actividades 
            WHERE creado_en < DATE_SUB(NOW(), INTERVAL ? DAY)
        ", [$dias]);
        
        return $stmt->rowCount();
    }
}
?>