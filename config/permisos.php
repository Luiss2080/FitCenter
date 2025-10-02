<?php
/**
 * Sistema de permisos para CareCenter
 */

class Permisos {
    
    private static $permisos = [
        // Administrador - Acceso total
        ROL_ADMIN => [
            'usuarios.ver', 'usuarios.crear', 'usuarios.editar', 'usuarios.eliminar',
            'pacientes.ver', 'pacientes.crear', 'pacientes.editar', 'pacientes.eliminar',
            'servicios.ver', 'servicios.crear', 'servicios.editar', 'servicios.eliminar',
            'planes.ver', 'planes.crear', 'planes.editar', 'planes.eliminar',
            'recetas.ver', 'recetas.crear', 'recetas.editar', 'recetas.eliminar',
            'consultas.ver', 'consultas.crear', 'consultas.editar',
            'mediciones.ver', 'mediciones.crear', 'mediciones.editar',
            'ordenes.ver', 'ordenes.crear', 'ordenes.editar', 'ordenes.eliminar',
            'entregas.ver', 'entregas.crear', 'entregas.editar',
            'facturas.ver', 'facturas.crear', 'facturas.editar',
            'reportes.ver', 'reportes.crear',
            'configuracion.ver', 'configuracion.editar'
        ],
        
        // Nutriólogo
        ROL_NUTRIOLOGO => [
            'pacientes.ver', 'pacientes.crear', 'pacientes.editar',
            'planes.ver', 'planes.crear', 'planes.editar',
            'recetas.ver', 'recetas.crear', 'recetas.editar',
            'consultas.ver', 'consultas.crear', 'consultas.editar',
            'mediciones.ver', 'mediciones.crear', 'mediciones.editar',
            'reportes.ver'
        ],
        
        // Personal de Cocina
        ROL_COCINA => [
            'recetas.ver',
            'ordenes.ver', 'ordenes.editar',
            'produccion.ver', 'produccion.crear', 'produccion.editar',
            'reportes.produccion'
        ],
        
        // Repartidor
        ROL_REPARTIDOR => [
            'entregas.ver', 'entregas.editar',
            'rutas.ver',
            'reportes.entregas'
        ],
        
        // Cliente
        ROL_CLIENTE => [
            'perfil.ver', 'perfil.editar',
            'planes.ver',
            'consultas.ver',
            'mediciones.ver',
            'facturas.ver'
        ]
    ];
    
    public static function verificar($rolUsuario, $permisoRequerido) {
        if (!isset(self::$permisos[$rolUsuario])) {
            return false;
        }
        
        return in_array($permisoRequerido, self::$permisos[$rolUsuario]);
    }
    
    public static function obtenerPermisos($rolUsuario) {
        return self::$permisos[$rolUsuario] ?? [];
    }
    
    public static function tieneAlgunPermiso($rolUsuario, $permisos) {
        $permisosUsuario = self::obtenerPermisos($rolUsuario);
        
        foreach ($permisos as $permiso) {
            if (in_array($permiso, $permisosUsuario)) {
                return true;
            }
        }
        
        return false;
    }
    
    public static function tieneTodosPermisos($rolUsuario, $permisos) {
        $permisosUsuario = self::obtenerPermisos($rolUsuario);
        
        foreach ($permisos as $permiso) {
            if (!in_array($permiso, $permisosUsuario)) {
                return false;
            }
        }
        
        return true;
    }
    
    public static function esAdmin($rolUsuario) {
        return $rolUsuario === ROL_ADMIN;
    }
    
    public static function middleware($permisoRequerido) {
        if (!Sesion::estaLogueado()) {
            header('Location: /login');
            exit;
        }
        
        if (!Sesion::verificarTiempoSesion()) {
            header('Location: /login');
            exit;
        }
        
        if (!self::verificar(Sesion::obtener('usuario_rol'), $permisoRequerido)) {
            http_response_code(403);
            die('Acceso denegado: No tienes permisos para acceder a esta sección.');
        }
    }
}