<?php
/**
 * Controlador de Configuración - CareCenter
 * Maneja la configuración del sistema y preferencias
 */

class ConfiguracionControlador {
    
    public function __construct() {
        // Verificar autenticación y permisos de administrador
        if (!Sesion::estaLogueado()) {
            header('Location: /login');
            exit;
        }
        
        $rolUsuario = Sesion::obtenerUsuario()['rol'];
        if (!in_array($rolUsuario, [ROL_ADMIN, ROL_NUTRIOLOGO])) {
            throw new Exception('Acceso denegado. Solo administradores y nutriólogos pueden modificar la configuración.');
        }
    }
    
    /**
     * Mostrar página principal de configuración
     */
    public function index() {
        $titulo = 'Configuración del Sistema - CareCenter';
        
        $configuraciones = $this->obtenerConfiguraciones();
        $configuracionesPorCategoria = $this->agruparPorCategoria($configuraciones);
        
        $this->cargarVista('admin/configuracion/index', [
            'titulo' => $titulo,
            'configuraciones_por_categoria' => $configuracionesPorCategoria
        ]);
    }
    
    /**
     * Actualizar configuraciones
     */
    public function actualizar() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método no permitido');
            }
            
            if (!Sesion::validarCsrf($_POST['csrf_token'])) {
                throw new Exception('Token CSRF inválido');
            }
            
            $configuraciones = $_POST['configuracion'] ?? [];
            
            if (empty($configuraciones)) {
                throw new Exception('No se recibieron configuraciones para actualizar');
            }
            
            $db = Database::getInstance()->getConnection();
            $db->beginTransaction();
            
            try {
                foreach ($configuraciones as $clave => $valor) {
                    $this->actualizarConfiguracion($clave, $valor);
                }
                
                $db->commit();
                
                // Registrar en auditoría
                LogAuditoriaControlador::registrarEvento(
                    'UPDATE',
                    'configuraciones',
                    null,
                    null,
                    $configuraciones,
                    'INFO'
                );
                
                $_SESSION['mensaje'] = 'Configuración actualizada correctamente';
                $_SESSION['tipo_mensaje'] = 'success';
                
            } catch (Exception $e) {
                $db->rollBack();
                throw $e;
            }
            
        } catch (Exception $e) {
            Logger::error('Error al actualizar configuración: ' . $e->getMessage());
            $_SESSION['mensaje'] = 'Error al actualizar configuración: ' . $e->getMessage();
            $_SESSION['tipo_mensaje'] = 'error';
        }
        
        header('Location: /configuracion');
        exit;
    }
    
    /**
     * Obtener todas las configuraciones
     */
    private function obtenerConfiguraciones() {
        try {
            $db = Database::getInstance()->getConnection();
            
            $sql = "SELECT * FROM configuraciones ORDER BY categoria, orden, nombre";
            $stmt = $db->query($sql);
            
            return $stmt->fetchAll();
            
        } catch (PDOException $e) {
            Logger::error('Error al obtener configuraciones: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Agrupar configuraciones por categoría
     */
    private function agruparPorCategoria($configuraciones) {
        $agrupadas = [];
        
        foreach ($configuraciones as $config) {
            $categoria = $config['categoria'] ?? 'General';
            if (!isset($agrupadas[$categoria])) {
                $agrupadas[$categoria] = [];
            }
            $agrupadas[$categoria][] = $config;
        }
        
        return $agrupadas;
    }
    
    /**
     * Actualizar una configuración específica
     */
    private function actualizarConfiguracion($clave, $valor) {
        try {
            $db = Database::getInstance()->getConnection();
            
            // Validar que la configuración existe
            $sqlCheck = "SELECT id, valor FROM configuraciones WHERE clave = ?";
            $stmtCheck = $db->prepare($sqlCheck);
            $stmtCheck->execute([$clave]);
            $configActual = $stmtCheck->fetch();
            
            if (!$configActual) {
                throw new Exception("Configuración no encontrada: {$clave}");
            }
            
            // Actualizar solo si el valor cambió
            if ($configActual['valor'] !== $valor) {
                $sql = "UPDATE configuraciones SET valor = ?, updated_at = NOW() WHERE clave = ?";
                $stmt = $db->prepare($sql);
                $stmt->execute([$valor, $clave]);
                
                Logger::info("Configuración actualizada: {$clave} = {$valor}");
            }
            
            return true;
            
        } catch (PDOException $e) {
            Logger::error("Error al actualizar configuración {$clave}: " . $e->getMessage());
            throw new Exception("Error al actualizar configuración: {$clave}");
        }
    }
    
    /**
     * Obtener valor de configuración por clave
     */
    public static function obtenerValor($clave, $valorPorDefecto = null) {
        try {
            $db = Database::getInstance()->getConnection();
            
            $sql = "SELECT valor FROM configuraciones WHERE clave = ? AND activa = 1";
            $stmt = $db->prepare($sql);
            $stmt->execute([$clave]);
            
            $resultado = $stmt->fetchColumn();
            
            return $resultado !== false ? $resultado : $valorPorDefecto;
            
        } catch (PDOException $e) {
            Logger::error("Error al obtener configuración {$clave}: " . $e->getMessage());
            return $valorPorDefecto;
        }
    }
    
    /**
     * Establecer valor de configuración
     */
    public static function establecerValor($clave, $valor, $categoria = 'General', $descripcion = '') {
        try {
            $db = Database::getInstance()->getConnection();
            
            // Intentar actualizar primero
            $sqlUpdate = "UPDATE configuraciones SET valor = ?, updated_at = NOW() WHERE clave = ?";
            $stmtUpdate = $db->prepare($sqlUpdate);
            $stmtUpdate->execute([$valor, $clave]);
            
            // Si no se actualizó ninguna fila, insertar nueva configuración
            if ($stmtUpdate->rowCount() === 0) {
                $sqlInsert = "INSERT INTO configuraciones (clave, valor, categoria, descripcion, tipo, activa, created_at) 
                             VALUES (?, ?, ?, ?, 'text', 1, NOW())";
                $stmtInsert = $db->prepare($sqlInsert);
                $stmtInsert->execute([$clave, $valor, $categoria, $descripcion]);
            }
            
            return true;
            
        } catch (PDOException $e) {
            Logger::error("Error al establecer configuración {$clave}: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Inicializar configuraciones por defecto
     */
    public static function inicializarConfiguracionesPorDefecto() {
        $configuracionesDefecto = [
            // Configuraciones Generales
            ['clave' => 'empresa_nombre', 'valor' => 'CareCenter', 'categoria' => 'General', 'descripcion' => 'Nombre de la empresa'],
            ['clave' => 'empresa_direccion', 'valor' => '', 'categoria' => 'General', 'descripcion' => 'Dirección de la empresa'],
            ['clave' => 'empresa_telefono', 'valor' => TELEFONO_CONTACTO, 'categoria' => 'General', 'descripcion' => 'Teléfono de contacto'],
            ['clave' => 'empresa_email', 'valor' => EMAIL_CONTACTO, 'categoria' => 'General', 'descripcion' => 'Email de contacto'],
            
            // Configuraciones de Seguridad
            ['clave' => 'max_intentos_login', 'valor' => MAX_LOGIN_ATTEMPTS, 'categoria' => 'Seguridad', 'descripcion' => 'Máximo número de intentos de login'],
            ['clave' => 'tiempo_bloqueo_login', 'valor' => '15', 'categoria' => 'Seguridad', 'descripcion' => 'Tiempo de bloqueo en minutos'],
            ['clave' => 'duracion_sesion', 'valor' => '480', 'categoria' => 'Seguridad', 'descripcion' => 'Duración de sesión en minutos'],
            ['clave' => 'require_https', 'valor' => 'false', 'categoria' => 'Seguridad', 'descripcion' => 'Requerir HTTPS'],
            
            // Configuraciones de Notificaciones
            ['clave' => 'notif_email_activo', 'valor' => 'true', 'categoria' => 'Notificaciones', 'descripcion' => 'Activar notificaciones por email'],
            ['clave' => 'notif_whatsapp_activo', 'valor' => 'false', 'categoria' => 'Notificaciones', 'descripcion' => 'Activar notificaciones por WhatsApp'],
            ['clave' => 'notif_recordatorio_citas', 'valor' => 'true', 'categoria' => 'Notificaciones', 'descripcion' => 'Enviar recordatorios de citas'],
            
            // Configuraciones de Planes
            ['clave' => 'plan_duracion_minima', 'valor' => '7', 'categoria' => 'Planes', 'descripcion' => 'Duración mínima de planes en días'],
            ['clave' => 'plan_duracion_maxima', 'valor' => '365', 'categoria' => 'Planes', 'descripcion' => 'Duración máxima de planes en días'],
            ['clave' => 'plan_modificaciones_permitidas', 'valor' => '3', 'categoria' => 'Planes', 'descripcion' => 'Número de modificaciones permitidas'],
            
            // Configuraciones de Cocina
            ['clave' => 'cocina_tiempo_preparacion', 'valor' => '120', 'categoria' => 'Cocina', 'descripcion' => 'Tiempo estándar de preparación en minutos'],
            ['clave' => 'cocina_ordenes_simultaneas', 'valor' => '20', 'categoria' => 'Cocina', 'descripcion' => 'Máximo de órdenes simultáneas'],
            
            // Configuraciones de Reparto
            ['clave' => 'reparto_radio_cobertura', 'valor' => '15', 'categoria' => 'Reparto', 'descripcion' => 'Radio de cobertura en kilómetros'],
            ['clave' => 'reparto_tiempo_entrega', 'valor' => '60', 'categoria' => 'Reparto', 'descripcion' => 'Tiempo máximo de entrega en minutos'],
            ['clave' => 'reparto_costo_km', 'valor' => '2.50', 'categoria' => 'Reparto', 'descripcion' => 'Costo por kilómetro de envío']
        ];
        
        try {
            $db = Database::getInstance()->getConnection();
            
            foreach ($configuracionesDefecto as $config) {
                // Verificar si ya existe
                $sqlCheck = "SELECT id FROM configuraciones WHERE clave = ?";
                $stmtCheck = $db->prepare($sqlCheck);
                $stmtCheck->execute([$config['clave']]);
                
                if (!$stmtCheck->fetch()) {
                    // No existe, insertar
                    $sqlInsert = "INSERT INTO configuraciones (clave, valor, categoria, descripcion, tipo, activa, created_at) 
                                 VALUES (?, ?, ?, ?, 'text', 1, NOW())";
                    $stmtInsert = $db->prepare($sqlInsert);
                    $stmtInsert->execute([
                        $config['clave'],
                        $config['valor'],
                        $config['categoria'],
                        $config['descripcion']
                    ]);
                }
            }
            
            Logger::info('Configuraciones por defecto inicializadas');
            return true;
            
        } catch (PDOException $e) {
            Logger::error('Error al inicializar configuraciones por defecto: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Resetear configuración a valores por defecto
     */
    public function resetear() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método no permitido');
            }
            
            if (!Sesion::validarCsrf($_POST['csrf_token'])) {
                throw new Exception('Token CSRF inválido');
            }
            
            $categoria = $_POST['categoria'] ?? '';
            
            if (empty($categoria)) {
                throw new Exception('Debe especificar una categoría');
            }
            
            $db = Database::getInstance()->getConnection();
            
            // Por seguridad, solo resetear categorías específicas
            $categoriasPermitidas = ['Planes', 'Cocina', 'Reparto', 'Notificaciones'];
            
            if (!in_array($categoria, $categoriasPermitidas)) {
                throw new Exception('Categoría no permitida para reseteo');
            }
            
            // Obtener configuraciones de la categoría
            $sql = "SELECT * FROM configuraciones WHERE categoria = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$categoria]);
            $configuraciones = $stmt->fetchAll();
            
            // Registrar en auditoría
            LogAuditoriaControlador::registrarEvento(
                'UPDATE',
                'configuraciones',
                null,
                $configuraciones,
                null,
                'WARNING'
            );
            
            // Resetear a valores por defecto (esto requeriría lógica específica)
            self::inicializarConfiguracionesPorDefecto();
            
            $_SESSION['mensaje'] = "Configuraciones de {$categoria} reseteadas a valores por defecto";
            $_SESSION['tipo_mensaje'] = 'success';
            
        } catch (Exception $e) {
            Logger::error('Error al resetear configuración: ' . $e->getMessage());
            $_SESSION['mensaje'] = 'Error al resetear configuración: ' . $e->getMessage();
            $_SESSION['tipo_mensaje'] = 'error';
        }
        
        header('Location: /configuracion');
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