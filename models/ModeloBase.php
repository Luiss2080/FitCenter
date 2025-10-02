<?php
/**
 * Modelo base para todos los modelos de CareCenter
 */

require_once __DIR__ . '/../config/database.php';

abstract class ModeloBase {
    protected $db;
    protected $tabla;
    protected $camposPermitidos = [];
    protected $camposRequeridos = [];
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Buscar todos los registros
     */
    public function obtenerTodos($limite = null, $offset = 0, $orden = 'id ASC') {
        try {
            $sql = "SELECT * FROM {$this->tabla} ORDER BY {$orden}";
            
            if ($limite) {
                $sql .= " LIMIT :limite OFFSET :offset";
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
                $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            } else {
                $stmt = $this->db->prepare($sql);
            }
            
            $stmt->execute();
            return $stmt->fetchAll();
            
        } catch (PDOException $e) {
            throw new Exception('Error al obtener registros: ' . $e->getMessage());
        }
    }
    
    /**
     * Buscar registro por ID
     */
    public function buscarPorId($id) {
        try {
            $sql = "SELECT * FROM {$this->tabla} WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch();
            
        } catch (PDOException $e) {
            throw new Exception('Error al buscar registro: ' . $e->getMessage());
        }
    }
    
    /**
     * Crear nuevo registro
     */
    public function crear($datos) {
        try {
            // Validar datos
            $this->validar($datos);
            
            // Filtrar solo campos permitidos
            $datosFiltrados = $this->filtrarCampos($datos);
            
            // Agregar timestamps
            $datosFiltrados['created_at'] = date('Y-m-d H:i:s');
            $datosFiltrados['updated_at'] = date('Y-m-d H:i:s');
            
            // Construir query
            $campos = implode(', ', array_keys($datosFiltrados));
            $placeholders = ':' . implode(', :', array_keys($datosFiltrados));
            
            $sql = "INSERT INTO {$this->tabla} ({$campos}) VALUES ({$placeholders})";
            $stmt = $this->db->prepare($sql);
            
            // Ejecutar
            foreach ($datosFiltrados as $campo => $valor) {
                $stmt->bindValue(":{$campo}", $valor);
            }
            
            $stmt->execute();
            return $this->db->lastInsertId();
            
        } catch (PDOException $e) {
            throw new Exception('Error al crear registro: ' . $e->getMessage());
        }
    }
    
    /**
     * Actualizar registro existente
     */
    public function actualizar($id, $datos) {
        try {
            // Validar que el registro existe
            if (!$this->buscarPorId($id)) {
                throw new Exception('Registro no encontrado');
            }
            
            // Filtrar solo campos permitidos
            $datosFiltrados = $this->filtrarCampos($datos);
            
            // Agregar timestamp de actualización
            $datosFiltrados['updated_at'] = date('Y-m-d H:i:s');
            
            // Construir query
            $sets = [];
            foreach (array_keys($datosFiltrados) as $campo) {
                $sets[] = "{$campo} = :{$campo}";
            }
            $setClause = implode(', ', $sets);
            
            $sql = "UPDATE {$this->tabla} SET {$setClause} WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            
            // Bind valores
            foreach ($datosFiltrados as $campo => $valor) {
                $stmt->bindValue(":{$campo}", $valor);
            }
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute();
            
        } catch (PDOException $e) {
            throw new Exception('Error al actualizar registro: ' . $e->getMessage());
        }
    }
    
    /**
     * Eliminar registro (soft delete si tiene campo 'eliminado')
     */
    public function eliminar($id) {
        try {
            // Verificar si existe
            if (!$this->buscarPorId($id)) {
                throw new Exception('Registro no encontrado');
            }
            
            // Verificar si tiene soft delete
            if ($this->tieneSoftDelete()) {
                $sql = "UPDATE {$this->tabla} SET eliminado = 1, deleted_at = :deleted_at WHERE id = :id";
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(':deleted_at', date('Y-m-d H:i:s'));
                $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            } else {
                $sql = "DELETE FROM {$this->tabla} WHERE id = :id";
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            }
            
            return $stmt->execute();
            
        } catch (PDOException $e) {
            throw new Exception('Error al eliminar registro: ' . $e->getMessage());
        }
    }
    
    /**
     * Contar total de registros
     */
    public function contar($condiciones = []) {
        try {
            $sql = "SELECT COUNT(*) as total FROM {$this->tabla}";
            
            if (!empty($condiciones)) {
                $whereClause = $this->construirWhere($condiciones);
                $sql .= " WHERE " . $whereClause;
            }
            
            $stmt = $this->db->prepare($sql);
            
            // Bind parámetros si existen condiciones
            if (!empty($condiciones)) {
                foreach ($condiciones as $campo => $valor) {
                    $stmt->bindValue(":{$campo}", $valor);
                }
            }
            
            $stmt->execute();
            $resultado = $stmt->fetch();
            
            return (int)$resultado['total'];
            
        } catch (PDOException $e) {
            throw new Exception('Error al contar registros: ' . $e->getMessage());
        }
    }
    
    /**
     * Buscar con condiciones personalizadas
     */
    public function buscarPor($condiciones, $limite = null, $orden = 'id ASC') {
        try {
            $sql = "SELECT * FROM {$this->tabla}";
            
            if (!empty($condiciones)) {
                $whereClause = $this->construirWhere($condiciones);
                $sql .= " WHERE " . $whereClause;
            }
            
            $sql .= " ORDER BY {$orden}";
            
            if ($limite) {
                $sql .= " LIMIT {$limite}";
            }
            
            $stmt = $this->db->prepare($sql);
            
            // Bind parámetros
            foreach ($condiciones as $campo => $valor) {
                $stmt->bindValue(":{$campo}", $valor);
            }
            
            $stmt->execute();
            return $stmt->fetchAll();
            
        } catch (PDOException $e) {
            throw new Exception('Error en búsqueda: ' . $e->getMessage());
        }
    }
    
    /**
     * Validar datos antes de insertar/actualizar
     */
    protected function validar($datos) {
        $errores = [];
        
        // Verificar campos requeridos
        foreach ($this->camposRequeridos as $campo) {
            if (!isset($datos[$campo]) || empty($datos[$campo])) {
                $errores[] = "El campo {$campo} es requerido";
            }
        }
        
        if (!empty($errores)) {
            throw new Exception('Errores de validación: ' . implode(', ', $errores));
        }
    }
    
    /**
     * Filtrar solo campos permitidos
     */
    protected function filtrarCampos($datos) {
        $datosFiltrados = [];
        
        foreach ($this->camposPermitidos as $campo) {
            if (isset($datos[$campo])) {
                $datosFiltrados[$campo] = $datos[$campo];
            }
        }
        
        return $datosFiltrados;
    }
    
    /**
     * Construir cláusula WHERE
     */
    protected function construirWhere($condiciones) {
        $wheres = [];
        
        foreach (array_keys($condiciones) as $campo) {
            $wheres[] = "{$campo} = :{$campo}";
        }
        
        return implode(' AND ', $wheres);
    }
    
    /**
     * Verificar si la tabla tiene soft delete
     */
    protected function tieneSoftDelete() {
        try {
            $sql = "SHOW COLUMNS FROM {$this->tabla} LIKE 'eliminado'";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            
            return $stmt->rowCount() > 0;
            
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Iniciar transacción
     */
    public function iniciarTransaccion() {
        return $this->db->beginTransaction();
    }
    
    /**
     * Confirmar transacción
     */
    public function confirmarTransaccion() {
        return $this->db->commit();
    }
    
    /**
     * Revertir transacción
     */
    public function revertirTransaccion() {
        return $this->db->rollBack();
    }
}