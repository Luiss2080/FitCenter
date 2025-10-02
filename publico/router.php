<?php
/**
 * Router mejorado para CareCenter
 * Manejo avanzado de rutas con middlewares y parámetros
 */

class Router {
    private $routes = [];
    private $middlewares = [];
    private $currentMiddleware = [];
    
    public function __construct() {
        $this->loadRoutes();
    }
    
    /**
     * Cargar todas las rutas del sistema
     */
    private function loadRoutes() {
        // ====================================================================
        // RUTAS PÚBLICAS (sin autenticación)
        // ====================================================================
        
        // Página principal
        $this->get('/', 'AutenticacionControlador@mostrarLogin');
        $this->get('/home', 'PublicoControlador@home');
        $this->get('/contacto', 'PublicoControlador@contacto');
        
        // Autenticación
        $this->get('/login', 'AutenticacionControlador@mostrarLogin');
        $this->post('/login', 'AutenticacionControlador@login');
        $this->get('/registro', 'AutenticacionControlador@mostrarRegistro');
        $this->post('/registro', 'AutenticacionControlador@registro');
        $this->get('/recuperar-password', 'AutenticacionControlador@mostrarRecuperar');
        $this->post('/recuperar-password', 'AutenticacionControlador@recuperar');
        $this->post('/logout', 'AutenticacionControlador@logout');
        
        // ====================================================================
        // RUTAS PROTEGIDAS (requieren autenticación)
        // ====================================================================
        
        $this->middleware(['auth'])->group(function() {
            // Dashboard
            $this->get('/dashboard', 'DashboardControlador@index');
            $this->get('/dashboard/resumen', 'DashboardControlador@resumen');
            
            // ====================================================================
            // RUTAS DE ADMINISTRACIÓN (solo admin)
            // ====================================================================
            $this->middleware(['role:' . ROL_ADMIN])->group(function() {
                // Gestión de usuarios
                $this->get('/admin/usuarios', 'UsuarioControlador@index');
                $this->get('/admin/usuarios/crear', 'UsuarioControlador@crear');
                $this->post('/admin/usuarios/crear', 'UsuarioControlador@store');
                $this->get('/admin/usuarios/{id}/editar', 'UsuarioControlador@editar');
                $this->post('/admin/usuarios/{id}/actualizar', 'UsuarioControlador@actualizar');
                $this->delete('/admin/usuarios/{id}', 'UsuarioControlador@eliminar');
                
                // Configuración del sistema
                $this->get('/admin/configuracion', 'ConfiguracionControlador@index');
                $this->post('/admin/configuracion', 'ConfiguracionControlador@actualizar');
            });
            
            // ====================================================================
            // RUTAS DE NUTRIÓLOGOS
            // ====================================================================
            $this->middleware(['role:' . ROL_NUTRIOLOGO . ',' . ROL_ADMIN])->group(function() {
                // Gestión de pacientes
                $this->get('/pacientes', 'PacienteControlador@index');
                $this->get('/pacientes/crear', 'PacienteControlador@crear');
                $this->post('/pacientes/crear', 'PacienteControlador@store');
                $this->get('/pacientes/{id}', 'PacienteControlador@ver');
                $this->get('/pacientes/{id}/editar', 'PacienteControlador@editar');
                $this->post('/pacientes/{id}/actualizar', 'PacienteControlador@actualizar');
                $this->get('/pacientes/{id}/historial', 'PacienteControlador@historial');
                
                // Planes nutricionales
                $this->get('/planes', 'PlanControlador@index');
                $this->get('/planes/crear', 'PlanControlador@crear');
                $this->post('/planes/crear', 'PlanControlador@store');
                $this->get('/planes/{id}', 'PlanControlador@detalle');
                $this->get('/planes/{id}/editar', 'PlanControlador@editar');
                $this->post('/planes/{id}/actualizar', 'PlanControlador@actualizar');
                
                // Recetas
                $this->get('/recetas', 'RecetaControlador@index');
                $this->get('/recetas/crear', 'RecetaControlador@crear');
                $this->post('/recetas/crear', 'RecetaControlador@store');
                $this->get('/recetas/{id}', 'RecetaControlador@ver');
                
                // Consultas
                $this->get('/consultas', 'ConsultaControlador@index');
                $this->get('/consultas/crear', 'ConsultaControlador@crear');
                $this->post('/consultas/crear', 'ConsultaControlador@store');
                
                // Mediciones
                $this->get('/mediciones/{paciente_id}', 'MedicionControlador@index');
                $this->post('/mediciones/{paciente_id}', 'MedicionControlador@store');
            });
            
            // ====================================================================
            // RUTAS DE COCINA
            // ====================================================================
            $this->middleware(['role:' . ROL_COCINA . ',' . ROL_ADMIN])->group(function() {
                $this->get('/cocina/ordenes', 'CocinaControlador@ordenes');
                $this->get('/cocina/preparacion', 'CocinaControlador@preparacion');
                $this->post('/cocina/ordenes/{id}/iniciar', 'CocinaControlador@iniciarOrden');
                $this->post('/cocina/ordenes/{id}/completar', 'CocinaControlador@completarOrden');
            });
            
            // ====================================================================
            // RUTAS DE REPARTIDORES
            // ====================================================================
            $this->middleware(['role:' . ROL_REPARTIDOR . ',' . ROL_ADMIN])->group(function() {
                $this->get('/reparto/entregas', 'RepartidorControlador@entregas');
                $this->get('/reparto/mapa', 'RepartidorControlador@mapa');
                $this->post('/reparto/entregas/{id}/confirmar', 'RepartidorControlador@confirmar');
            });
            
            // ====================================================================
            // RUTAS DE CLIENTES
            // ====================================================================
            $this->middleware(['role:' . ROL_CLIENTE . ',' . ROL_ADMIN])->group(function() {
                $this->get('/mi-plan', 'ClienteControlador@miPlan');
                $this->get('/mis-entregas', 'ClienteControlador@misEntregas');
                $this->get('/mi-progreso', 'ClienteControlador@miProgreso');
            });
            
            // ====================================================================
            // RUTAS COMPARTIDAS (múltiples roles)
            // ====================================================================
            
            // Servicios
            $this->get('/servicios', 'ServicioControlador@index');
            
            // Reportes (admin y nutriólogos)
            $this->middleware(['role:' . ROL_ADMIN . ',' . ROL_NUTRIOLOGO])->group(function() {
                $this->get('/reportes', 'ReportesControlador@index');
                $this->get('/reportes/produccion', 'ReportesControlador@produccion');
                $this->get('/reportes/entregas', 'ReportesControlador@entregas');
            });
            
            // Facturación (admin)
            $this->middleware(['role:' . ROL_ADMIN])->group(function() {
                $this->get('/facturas', 'FacturacionControlador@index');
                $this->get('/facturas/crear', 'FacturacionControlador@crear');
                $this->get('/facturas/{id}', 'FacturacionControlador@ver');
            });
        });
        
        // ====================================================================
        // API ROUTES
        // ====================================================================
        $this->middleware(['api'])->group(function() {
            // API de autenticación
            $this->post('/api/auth/login', 'Api\\ApiAuthControlador@login');
            $this->post('/api/auth/refresh', 'Api\\ApiAuthControlador@refresh');
            
            // API protegidas
            $this->middleware(['api.auth'])->group(function() {
                $this->get('/api/entregas', 'Api\\ApiEntregaControlador@index');
                $this->post('/api/entregas/{id}/estado', 'Api\\ApiEntregaControlador@actualizarEstado');
                $this->post('/api/geocoding', 'Api\\ApiGeocodingControlador@geocode');
            });
        });
        
        // Webhooks
        $this->post('/webhooks/{provider}', 'WebhookControlador@handle');
    }
    
    /**
     * Registrar middleware
     */
    public function middleware($middlewares) {
        $this->currentMiddleware = is_array($middlewares) ? $middlewares : [$middlewares];
        return $this;
    }
    
    /**
     * Agrupar rutas con middleware común
     */
    public function group($callback) {
        $originalMiddleware = $this->currentMiddleware;
        call_user_func($callback);
        $this->currentMiddleware = $originalMiddleware;
        return $this;
    }
    
    /**
     * Registrar ruta GET
     */
    public function get($path, $handler) {
        return $this->addRoute('GET', $path, $handler);
    }
    
    /**
     * Registrar ruta POST
     */
    public function post($path, $handler) {
        return $this->addRoute('POST', $path, $handler);
    }
    
    /**
     * Registrar ruta PUT
     */
    public function put($path, $handler) {
        return $this->addRoute('PUT', $path, $handler);
    }
    
    /**
     * Registrar ruta DELETE
     */
    public function delete($path, $handler) {
        return $this->addRoute('DELETE', $path, $handler);
    }
    
    /**
     * Añadir ruta al sistema
     */
    public function addRoute($method, $path, $handler) {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'handler' => $handler,
            'middleware' => $this->currentMiddleware
        ];
        return $this;
    }
    
    /**
     * Resolver ruta actual
     */
    public function resolve($method, $path) {
        // Limpiar la ruta
        $path = $this->cleanPath($path);
        $method = strtoupper($method);
        
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $this->matchPath($route['path'], $path)) {
                // Ejecutar middlewares
                if (!$this->executeMiddlewares($route['middleware'])) {
                    return false;
                }
                
                // Ejecutar controlador
                return $this->executeHandler($route['handler'], $path, $route['path']);
            }
        }
        
        // Ruta no encontrada
        Logger::warning('Ruta no encontrada', [
            'method' => $method,
            'path' => $path,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
        ]);
        
        abort(404, 'Página no encontrada');
        return false;
    }
    
    /**
     * Limpiar ruta
     */
    private function cleanPath($path) {
        // Remover parámetros de query
        $path = parse_url($path, PHP_URL_PATH) ?? $path;
        
        // Remover directorio base si existe
        $baseDir = '/care_center';
        if (strpos($path, $baseDir) === 0) {
            $path = substr($path, strlen($baseDir));
        }
        
        // Asegurar que empiece con /
        if ($path === '' || $path[0] !== '/') {
            $path = '/' . $path;
        }
        
        // Remover / final excepto en raíz
        if (strlen($path) > 1) {
            $path = rtrim($path, '/');
        }
        
        return $path;
    }
    
    /**
     * Verificar si la ruta coincide
     */
    private function matchPath($routePath, $requestPath) {
        $routePattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $routePath);
        $routePattern = '#^' . $routePattern . '$#';
        return preg_match($routePattern, $requestPath);
    }
    
    /**
     * Ejecutar middlewares
     */
    private function executeMiddlewares($middlewares) {
        foreach ($middlewares as $middleware) {
            if (!$this->executeMiddleware($middleware)) {
                return false;
            }
        }
        return true;
    }
    
    /**
     * Ejecutar middleware individual
     */
    private function executeMiddleware($middleware) {
        switch ($middleware) {
            case 'auth':
                return $this->authMiddleware();
                
            case 'api':
                return $this->apiMiddleware();
                
            case 'api.auth':
                return $this->apiAuthMiddleware();
                
            default:
                // Middleware de roles
                if (strpos($middleware, 'role:') === 0) {
                    $roles = explode(',', substr($middleware, 5));
                    return $this->roleMiddleware($roles);
                }
                break;
        }
        
        return true;
    }
    
    /**
     * Middleware de autenticación
     */
    private function authMiddleware() {
        if (!Sesion::estaLogueado()) {
            if ($this->isAjax()) {
                http_response_code(401);
                echo json_encode(['error' => 'No autenticado']);
                exit;
            } else {
                redirect('/login');
                return false;
            }
        }
        
        if (!Sesion::verificarTiempoSesion()) {
            redirect('/login');
            return false;
        }
        
        return true;
    }
    
    /**
     * Middleware de roles
     */
    private function roleMiddleware($allowedRoles) {
        $userRole = Sesion::obtener('usuario_rol');
        
        if (!in_array($userRole, $allowedRoles)) {
            if ($this->isAjax()) {
                http_response_code(403);
                echo json_encode(['error' => 'Sin permisos']);
                exit;
            } else {
                abort(403, 'No tienes permisos para acceder a esta sección');
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Middleware de API
     */
    private function apiMiddleware() {
        header('Content-Type: application/json; charset=utf-8');
        return true;
    }
    
    /**
     * Middleware de autenticación API
     */
    private function apiAuthMiddleware() {
        // Implementar autenticación JWT para API
        $token = $this->getBearerToken();
        
        if (!$token) {
            http_response_code(401);
            echo json_encode(['error' => 'Token requerido']);
            exit;
        }
        
        // Validar token JWT (implementar según necesidades)
        // Por ahora usar autenticación por sesión
        return $this->authMiddleware();
    }
    
    /**
     * Obtener Bearer token
     */
    private function getBearerToken() {
        $headers = getallheaders();
        
        if (isset($headers['Authorization'])) {
            if (preg_match('/Bearer\s+(.*)$/i', $headers['Authorization'], $matches)) {
                return $matches[1];
            }
        }
        
        return null;
    }
    
    /**
     * Verificar si es petición AJAX
     */
    private function isAjax() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    /**
     * Ejecutar controlador
     */
    private function executeHandler($handler, $requestPath, $routePath) {
        list($controllerName, $methodName) = explode('@', $handler);
        
        // Buscar el controlador
        $controllerFile = $this->findControllerFile($controllerName);
        
        if (!file_exists($controllerFile)) {
            Logger::error('Controlador no encontrado', [
                'controller' => $controllerName,
                'file' => $controllerFile
            ]);
            abort(500, 'Error interno del servidor');
            return false;
        }
        
        require_once $controllerFile;
        
        if (!class_exists($controllerName)) {
            Logger::error('Clase de controlador no encontrada', [
                'controller' => $controllerName
            ]);
            abort(500, 'Error interno del servidor');
            return false;
        }
        
        $controller = new $controllerName();
        
        if (!method_exists($controller, $methodName)) {
            Logger::error('Método de controlador no encontrado', [
                'controller' => $controllerName,
                'method' => $methodName
            ]);
            abort(500, 'Error interno del servidor');
            return false;
        }
        
        // Extraer parámetros de la URL
        $params = $this->extractParams($routePath, $requestPath);
        
        try {
            return call_user_func_array([$controller, $methodName], $params);
        } catch (Exception $e) {
            Logger::error('Error ejecutando controlador', [
                'controller' => $controllerName,
                'method' => $methodName,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if (APP_DEBUG) {
                throw $e;
            } else {
                abort(500, 'Error interno del servidor');
            }
            
            return false;
        }
    }
    
    /**
     * Encontrar archivo del controlador
     */
    private function findControllerFile($controllerName) {
        // Si contiene namespace (Api\), buscar en subdirectorio
        if (strpos($controllerName, '\\') !== false) {
            $parts = explode('\\', $controllerName);
            $fileName = array_pop($parts) . '.php';
            $subDir = implode('/', $parts);
            return CONTROLLERS_PATH . '/' . $subDir . '/' . $fileName;
        } else {
            return CONTROLLERS_PATH . '/' . $controllerName . '.php';
        }
    }
    
    /**
     * Extraer parámetros de la URL
     */
    private function extractParams($routePath, $requestPath) {
        $routeParts = explode('/', trim($routePath, '/'));
        $requestParts = explode('/', trim($requestPath, '/'));
        $params = [];
        
        for ($i = 0; $i < count($routeParts); $i++) {
            if (isset($routeParts[$i]) && preg_match('/\{([^}]+)\}/', $routeParts[$i], $matches)) {
                $params[$matches[1]] = $requestParts[$i] ?? null;
            }
        }
        
        return array_values($params);
    }
}