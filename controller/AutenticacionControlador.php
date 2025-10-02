<?php
/**
 * Controlador de Autenticación
 * Maneja login, logout, registro y recuperación de contraseña
 */

class AutenticacionControlador {
    
    private $userModel;
    
    public function __construct() {
        $this->userModel = new Usuario();
    }
    
    /**
     * Mostrar página de login
     */
    public function mostrarLogin() {
        // Si ya está autenticado, redirigir al dashboard
        if (method_exists('Sesion', 'estaLogueado') && Sesion::estaLogueado()) {
            $this->redirigirSegunRol();
            return;
        }
        
        $titulo = 'Iniciar Sesión - CareCenter';
        $mensaje = null;
        $error = null;
        
        // Obtener mensajes flash si existen
        if (method_exists('Sesion', 'flash')) {
            $mensaje = Sesion::flash('mensaje') ?? null;
            $error = Sesion::flash('error') ?? null;
        }
        
        // Cargar vista de login
        $this->cargarVista('autenticacion/login', [
            'titulo' => $titulo,
            'mensaje' => $mensaje,
            'error' => $error
        ]);
    }
    
    /**
     * Procesar login
     */
    public function login() {
        try {
            // Verificar método POST
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método no permitido');
            }
            
            // Obtener datos del formulario
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $recordar = isset($_POST['recordar']);
            
            // Validaciones básicas
            if (empty($email) || empty($password)) {
                throw new Exception('Email y contraseña son requeridos');
            }
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Formato de email inválido');
            }
            
            // Log del intento de login
            Logger::info('Intento de login', ['email' => $email, 'ip' => $_SERVER['REMOTE_ADDR']]);
            
            // Verificar límite de intentos
            if ($this->verificarBloqueo($email)) {
                throw new Exception('Demasiados intentos fallidos. Intenta de nuevo en 15 minutos.');
            }
            
            // Buscar usuario por email
            $usuario = $this->userModel->buscarPorEmail($email);
            
            if (!$usuario) {
                $this->registrarIntentoFallido($email);
                throw new Exception('Credenciales incorrectas');
            }
            
            // Verificar contraseña
            if (!password_verify($password, $usuario['password'])) {
                $this->registrarIntentoFallido($email);
                throw new Exception('Credenciales incorrectas');
            }
            
            // Verificar estado del usuario
            if (!$usuario['activo']) {
                throw new Exception('Usuario inactivo. Contacta al administrador.');
            }
            
            // Login exitoso
            $this->procesarLoginExitoso($usuario, $recordar);
            
            // Limpiar intentos fallidos
            $this->limpiarIntentosFallidos($email);
            
            // Log del login exitoso
            Logger::info('Login exitoso', [
                'usuario_id' => $usuario['id'],
                'email' => $email,
                'rol' => $usuario['rol'],
                'ip' => $_SERVER['REMOTE_ADDR']
            ]);
            
            // Responder según tipo de petición
            if ($this->esAjax()) {
                $this->responderJson([
                    'success' => true,
                    'mensaje' => 'Login exitoso',
                    'redirect' => $this->obtenerUrlDashboard($usuario['rol'])
                ]);
            } else {
                Sesion::flash('mensaje', 'Bienvenido de vuelta!');
                $this->redirigirSegunRol($usuario['rol']);
            }
            
        } catch (Exception $e) {
            Logger::warning('Login fallido: ' . $e->getMessage(), [
                'email' => $email ?? 'unknown',
                'ip' => $_SERVER['REMOTE_ADDR']
            ]);
            
            if ($this->esAjax()) {
                $this->responderJson([
                    'success' => false,
                    'error' => $e->getMessage()
                ], 400);
            } else {
                Sesion::flash('error', $e->getMessage());
                header('Location: /login');
                exit;
            }
        }
    }
    
    /**
     * Procesar logout
     */
    public function logout() {
        $usuarioId = Sesion::obtener('usuario_id');
        
        // Log del logout
        if ($usuarioId) {
            Logger::info('Logout de usuario', [
                'usuario_id' => $usuarioId,
                'ip' => $_SERVER['REMOTE_ADDR']
            ]);
            
            // Actualizar último acceso
            $this->userModel->actualizarUltimoAcceso($usuarioId);
        }
        
        // Cerrar sesión
        Sesion::cerrarSesion();
        
        // Redirigir al login
        Sesion::flash('mensaje', 'Sesión cerrada correctamente');
        header('Location: /login');
        exit;
    }
    
    /**
     * Mostrar página de registro (solo admin puede crear usuarios)
     */
    public function mostrarRegistro() {
        $titulo = 'Registrar Usuario - CareCenter';
        $roles = $this->obtenerRolesDisponibles();
        
        $this->cargarVista('autenticacion/registro', [
            'titulo' => $titulo,
            'roles' => $roles
        ]);
    }
    
    /**
     * Mostrar formulario de recuperación de contraseña
     */
    public function mostrarRecuperacion() {
        $titulo = 'Recuperar Contraseña - CareCenter';
        
        $this->cargarVista('autenticacion/recuperar_contrasena', [
            'titulo' => $titulo
        ]);
    }
    
    /**
     * Dashboard principal (redirige según rol)
     */
    public function dashboard() {
        if (!Sesion::estaLogueado()) {
            header('Location: /login');
            exit;
        }
        
        $usuario = Sesion::obtenerUsuario();
        $this->redirigirSegunRol($usuario['rol']);
    }
    
    // ====================================================================
    // MÉTODOS PRIVADOS
    // ====================================================================
    
    /**
     * Procesar login exitoso
     */
    private function procesarLoginExitoso($usuario, $recordar = false) {
        // Establecer datos de sesión
        Sesion::establecerUsuario([
            'id' => $usuario['id'],
            'nombre' => $usuario['nombre'],
            'email' => $usuario['email'],
            'rol' => $usuario['rol'],
            'avatar' => $usuario['avatar'] ?? null,
            'ultimo_acceso' => $usuario['ultimo_acceso']
        ]);
        
        // Actualizar último acceso
        $this->userModel->actualizarUltimoAcceso($usuario['id']);
        
        // Configurar cookie "recordar" si se solicita
        if ($recordar) {
            $token = $this->generarTokenRecordar();
            $this->userModel->guardarTokenRecordar($usuario['id'], $token);
            
            setcookie(
                'recordar_token',
                $token,
                time() + (30 * 24 * 60 * 60), // 30 días
                '/',
                '',
                isset($_SERVER['HTTPS']), // Secure - solo HTTPS
                true  // HttpOnly
            );
        }
        
        // Regenerar ID de sesión por seguridad
        session_regenerate_id(true);
    }
    
    /**
     * Redirigir según rol del usuario
     */
    private function redirigirSegunRol($rol = null) {
        if (!$rol) {
            $usuario = Sesion::obtenerUsuario();
            $rol = $usuario['rol'] ?? 'cliente';
        }
        
        $url = $this->obtenerUrlDashboard($rol);
        header("Location: {$url}");
        exit;
    }
    
    /**
     * Obtener URL del dashboard según rol
     */
    private function obtenerUrlDashboard($rol) {
        $urls = [
            'admin' => '/admin/dashboard',
            'nutriologo' => '/nutriologo/dashboard', 
            'cocina' => '/cocina/dashboard',
            'repartidor' => '/repartidor/dashboard',
            'cliente' => '/cliente/dashboard'
        ];
        
        return $urls[$rol] ?? '/cliente/dashboard';
    }
    
    /**
     * Verificar si hay bloqueo por intentos fallidos
     */
    private function verificarBloqueo($email) {
        $intentos = Sesion::obtener("login_attempts_{$email}", 0);
        return $intentos >= MAX_LOGIN_ATTEMPTS;
    }
    
    /**
     * Registrar intento fallido
     */
    private function registrarIntentoFallido($email) {
        $intentos = Sesion::obtener("login_attempts_{$email}", 0) + 1;
        Sesion::establecer("login_attempts_{$email}", $intentos);
    }
    
    /**
     * Limpiar intentos fallidos
     */
    private function limpiarIntentosFallidos($email) {
        Sesion::eliminar("login_attempts_{$email}");
    }
    
    /**
     * Verificar si es petición AJAX
     */
    private function esAjax() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    /**
     * Responder con JSON
     */
    private function responderJson($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
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
    
    /**
     * Generar token para función "recordar"
     */
    private function generarTokenRecordar() {
        return bin2hex(random_bytes(32));
    }
    
    /**
     * Obtener roles disponibles para registro
     */
    private function obtenerRolesDisponibles() {
        return [
            'nutriologo' => 'Nutriólogo',
            'cocina' => 'Personal de Cocina',
            'repartidor' => 'Repartidor',
            'cliente' => 'Cliente'
        ];
    }
}