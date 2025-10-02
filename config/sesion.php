<?php
/**
 * Gestión de sesiones para CareCenter
 */

class Sesion {
    
    public static function iniciar() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public static function establecer($clave, $valor) {
        self::iniciar();
        $_SESSION[$clave] = $valor;
    }
    
    public static function obtener($clave, $valorPorDefecto = null) {
        self::iniciar();
        return $_SESSION[$clave] ?? $valorPorDefecto;
    }
    
    public static function eliminar($clave) {
        self::iniciar();
        if (isset($_SESSION[$clave])) {
            unset($_SESSION[$clave]);
        }
    }
    
    public static function destruir() {
        self::iniciar();
        session_destroy();
        session_unset();
    }
    
    public static function estaLogueado() {
        return self::obtener('usuario_id') !== null;
    }
    
    public static function obtenerUsuario() {
        return [
            'id' => self::obtener('usuario_id'),
            'nombre' => self::obtener('usuario_nombre'),
            'email' => self::obtener('usuario_email'),
            'rol' => self::obtener('usuario_rol'),
            'rol_nombre' => self::obtener('usuario_rol_nombre')
        ];
    }
    
    public static function establecerUsuario($usuario) {
        self::establecer('usuario_id', $usuario['id']);
        self::establecer('usuario_nombre', $usuario['nombre']);
        self::establecer('usuario_email', $usuario['email']);
        self::establecer('usuario_rol', $usuario['rol_id']);
        self::establecer('usuario_rol_nombre', $usuario['rol_nombre']);
        self::establecer('ultimo_acceso', time());
    }
    
    public static function cerrarSesion() {
        self::destruir();
    }
    
    public static function verificarTiempoSesion() {
        $ultimoAcceso = self::obtener('ultimo_acceso');
        if ($ultimoAcceso && (time() - $ultimoAcceso) > SESSION_LIFETIME) {
            self::destruir();
            return false;
        }
        
        self::establecer('ultimo_acceso', time());
        return true;
    }
    
    public static function tienePermiso($permisoRequerido) {
        $rolUsuario = self::obtener('usuario_rol');
        return Permisos::verificar($rolUsuario, $permisoRequerido);
    }
    
    public static function flash($clave, $valor = null) {
        if ($valor === null) {
            $valor = self::obtener('flash_' . $clave);
            self::eliminar('flash_' . $clave);
            return $valor;
        }
        
        self::establecer('flash_' . $clave, $valor);
    }
    
    public static function regenerarId() {
        session_regenerate_id(true);
    }
    
    /**
     * Generar token CSRF
     */
    public static function generarCsrf() {
        if (!self::obtener('csrf_token')) {
            $token = bin2hex(random_bytes(32));
            self::establecer('csrf_token', $token);
        }
        return self::obtener('csrf_token');
    }
    
    /**
     * Validar token CSRF
     */
    public static function validarCsrf($token) {
        $sessionToken = self::obtener('csrf_token');
        return $sessionToken && hash_equals($sessionToken, $token);
    }
    
    /**
     * Verificar si existe una clave en sesión
     */
    public static function existe($clave) {
        self::iniciar();
        return isset($_SESSION[$clave]);
    }
}