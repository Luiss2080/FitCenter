<?php
/**
 * Clase base para modelos de FitCenter
 * Proporciona funcionalidades comunes para interactuar con la base de datos
 */

class BaseModel {
    protected $pdo;
    protected $table;
    protected $primaryKey = 'id';
    
    public function __construct($pdo = null) {
        global $pdo;
        $this->pdo = $pdo ?: $GLOBALS['pdo'];
        
        if (!$this->pdo) {
            throw new Exception('No hay conexión a la base de datos disponible');
        }
    }
    
    /**
     * Buscar un registro por ID
     */
    public function find($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Buscar todos los registros
     */
    public function findAll($orderBy = null) {
        $sql = "SELECT * FROM {$this->table}";
        if ($orderBy) {
            $sql .= " ORDER BY $orderBy";
        }
        
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
    
    /**
     * Buscar registros con condiciones
     */
    public function findWhere($conditions, $params = []) {
        $sql = "SELECT * FROM {$this->table} WHERE $conditions";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    /**
     * Buscar un registro con condiciones
     */
    public function findOneWhere($conditions, $params = []) {
        $sql = "SELECT * FROM {$this->table} WHERE $conditions LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }
    
    /**
     * Crear un nuevo registro
     */
    public function create($data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);
        
        if ($stmt->execute($data)) {
            return $this->pdo->lastInsertId();
        }
        
        return false;
    }
    
    /**
     * Actualizar un registro
     */
    public function update($id, $data) {
        $setParts = [];
        foreach (array_keys($data) as $column) {
            $setParts[] = "$column = :$column";
        }
        $setClause = implode(', ', $setParts);
        
        $sql = "UPDATE {$this->table} SET $setClause WHERE {$this->primaryKey} = :id";
        $data['id'] = $id;
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }
    
    /**
     * Eliminar un registro
     */
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id");
        return $stmt->execute(['id' => $id]);
    }
    
    /**
     * Contar registros
     */
    public function count($conditions = null, $params = []) {
        $sql = "SELECT COUNT(*) FROM {$this->table}";
        if ($conditions) {
            $sql .= " WHERE $conditions";
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }
    
    /**
     * Verificar si existe un registro
     */
    public function exists($conditions, $params = []) {
        return $this->count($conditions, $params) > 0;
    }
    
    /**
     * Ejecutar una consulta personalizada
     */
    public function query($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
    
    /**
     * Iniciar transacción
     */
    public function beginTransaction() {
        return $this->pdo->beginTransaction();
    }
    
    /**
     * Confirmar transacción
     */
    public function commit() {
        return $this->pdo->commit();
    }
    
    /**
     * Revertir transacción
     */
    public function rollback() {
        return $this->pdo->rollback();
    }
}
?>