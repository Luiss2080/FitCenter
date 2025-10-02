<?php
/**
 * Configuración de rutas de la aplicación CareCenter
 */

class Router {
    private $routes = [];
    
    public function __construct() {
        $this->loadRoutes();
    }
    
    private function loadRoutes() {
        // Rutas públicas
        $this->addRoute('GET', '/', 'PublicoControlador@home');
        $this->addRoute('GET', '/contacto', 'PublicoControlador@contacto');
        
        // Autenticación
        $this->addRoute('GET', '/login', 'AutenticacionControlador@mostrarLogin');
        $this->addRoute('POST', '/login', 'AutenticacionControlador@login');
        $this->addRoute('GET', '/registro', 'AutenticacionControlador@mostrarRegistro');
        $this->addRoute('POST', '/registro', 'AutenticacionControlador@registro');
        $this->addRoute('POST', '/logout', 'AutenticacionControlador@logout');
        $this->addRoute('GET', '/recuperar-contrasena', 'AutenticacionControlador@mostrarRecuperar');
        $this->addRoute('POST', '/recuperar-contrasena', 'AutenticacionControlador@recuperar');
        
        // Dashboard
        $this->addRoute('GET', '/dashboard', 'DashboardControlador@index');
        $this->addRoute('GET', '/dashboard/resumen', 'DashboardControlador@resumen');
        
        // Usuarios (Admin)
        $this->addRoute('GET', '/usuarios', 'UsuarioControlador@index');
        $this->addRoute('GET', '/usuarios/crear', 'UsuarioControlador@crear');
        $this->addRoute('POST', '/usuarios/crear', 'UsuarioControlador@store');
        $this->addRoute('GET', '/usuarios/{id}/editar', 'UsuarioControlador@editar');
        $this->addRoute('POST', '/usuarios/{id}/actualizar', 'UsuarioControlador@actualizar');
        $this->addRoute('POST', '/usuarios/{id}/eliminar', 'UsuarioControlador@eliminar');
        
        // Pacientes
        $this->addRoute('GET', '/pacientes', 'PacienteControlador@index');
        $this->addRoute('GET', '/pacientes/crear', 'PacienteControlador@crear');
        $this->addRoute('POST', '/pacientes/crear', 'PacienteControlador@store');
        $this->addRoute('GET', '/pacientes/{id}', 'PacienteControlador@ver');
        $this->addRoute('GET', '/pacientes/{id}/editar', 'PacienteControlador@editar');
        $this->addRoute('POST', '/pacientes/{id}/actualizar', 'PacienteControlador@actualizar');
        $this->addRoute('GET', '/pacientes/{id}/historial', 'PacienteControlador@historial');
        
        // Servicios
        $this->addRoute('GET', '/servicios', 'ServicioControlador@index');
        $this->addRoute('GET', '/servicios/crear', 'ServicioControlador@crear');
        $this->addRoute('POST', '/servicios/crear', 'ServicioControlador@store');
        
        // Planes
        $this->addRoute('GET', '/planes', 'PlanControlador@index');
        $this->addRoute('GET', '/planes/crear', 'PlanControlador@crear');
        $this->addRoute('GET', '/planes/{id}', 'PlanControlador@detalle');
        
        // Recetas
        $this->addRoute('GET', '/recetas', 'RecetaControlador@index');
        $this->addRoute('GET', '/recetas/crear', 'RecetaControlador@crear');
        $this->addRoute('GET', '/recetas/{id}', 'RecetaControlador@ver');
        
        // Cocina
        $this->addRoute('GET', '/cocina/ordenes', 'CocinaControlador@ordenes');
        $this->addRoute('GET', '/cocina/preparacion', 'CocinaControlador@preparacion');
        
        // Reparto
        $this->addRoute('GET', '/reparto/entregas', 'RepartidorControlador@entregas');
        $this->addRoute('GET', '/reparto/mapa', 'RepartidorControlador@mapa');
        $this->addRoute('POST', '/reparto/confirmar/{id}', 'RepartidorControlador@confirmar');
        
        // Facturación
        $this->addRoute('GET', '/facturas', 'FacturacionControlador@index');
        $this->addRoute('GET', '/facturas/crear', 'FacturacionControlador@crear');
        $this->addRoute('GET', '/facturas/{id}', 'FacturacionControlador@ver');
        
        // Reportes
        $this->addRoute('GET', '/reportes', 'ReportesControlador@index');
        $this->addRoute('GET', '/reportes/produccion', 'ReportesControlador@produccion');
        $this->addRoute('GET', '/reportes/entregas', 'ReportesControlador@entregas');
        
        // API
        $this->addRoute('POST', '/api/auth/login', 'Api\\ApiAuthControlador@login');
        $this->addRoute('GET', '/api/entregas', 'Api\\ApiEntregaControlador@index');
        $this->addRoute('POST', '/api/geocoding', 'Api\\ApiGeocodingControlador@geocode');
    }
    
    public function addRoute($method, $path, $handler) {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'handler' => $handler
        ];
    }
    
    public function resolve($method, $path) {
        foreach ($this->routes as $route) {
            if ($route['method'] === strtoupper($method) && $this->matchPath($route['path'], $path)) {
                return $this->executeHandler($route['handler'], $path, $route['path']);
            }
        }
        
        throw new Exception('Ruta no encontrada: ' . $method . ' ' . $path);
    }
    
    private function matchPath($routePath, $requestPath) {
        $routePattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $routePath);
        $routePattern = '#^' . $routePattern . '$#';
        return preg_match($routePattern, $requestPath);
    }
    
    private function executeHandler($handler, $requestPath, $routePath) {
        list($controllerName, $methodName) = explode('@', $handler);
        
        $controllerFile = ROOT_PATH . '/controladores/' . $controllerName . '.php';
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $controller = new $controllerName();
            
            // Extraer parámetros de la URL
            $params = $this->extractParams($routePath, $requestPath);
            
            return call_user_func_array([$controller, $methodName], $params);
        }
        
        throw new Exception('Controlador no encontrado: ' . $controllerName);
    }
    
    private function extractParams($routePath, $requestPath) {
        $routeParts = explode('/', trim($routePath, '/'));
        $requestParts = explode('/', trim($requestPath, '/'));
        $params = [];
        
        for ($i = 0; $i < count($routeParts); $i++) {
            if (preg_match('/\{([^}]+)\}/', $routeParts[$i], $matches)) {
                $params[$matches[1]] = $requestParts[$i] ?? null;
            }
        }
        
        return array_values($params);
    }
}