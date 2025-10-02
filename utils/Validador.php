<?php
/**
 * Clase Validador para CareCenter
 * Validaciones comunes de la aplicación
 */

class Validador {
    
    /**
     * Validar email
     */
    public static function email($email) {
        if (empty($email)) {
            return 'El email es requerido';
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 'Formato de email inválido';
        }
        
        return true;
    }
    
    /**
     * Validar contraseña
     */
    public static function password($password, $minLength = 6) {
        if (empty($password)) {
            return 'La contraseña es requerida';
        }
        
        if (strlen($password) < $minLength) {
            return "La contraseña debe tener al menos {$minLength} caracteres";
        }
        
        // Verificar que tenga al menos una letra y un número
        if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)/', $password)) {
            return 'La contraseña debe contener al menos una letra y un número';
        }
        
        return true;
    }
    
    /**
     * Validar teléfono
     */
    public static function telefono($telefono, $requerido = false) {
        if (empty($telefono)) {
            return $requerido ? 'El teléfono es requerido' : true;
        }
        
        // Limpiar el teléfono
        $telefonoLimpio = preg_replace('/[^0-9+]/', '', $telefono);
        
        if (strlen($telefonoLimpio) < 10) {
            return 'El teléfono debe tener al menos 10 dígitos';
        }
        
        if (!preg_match('/^[0-9+\-\s()]+$/', $telefono)) {
            return 'Formato de teléfono inválido';
        }
        
        return true;
    }
    
    /**
     * Validar nombre
     */
    public static function nombre($nombre, $minLength = 2) {
        if (empty($nombre)) {
            return 'El nombre es requerido';
        }
        
        if (strlen(trim($nombre)) < $minLength) {
            return "El nombre debe tener al menos {$minLength} caracteres";
        }
        
        if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/', $nombre)) {
            return 'El nombre solo puede contener letras y espacios';
        }
        
        return true;
    }
    
    /**
     * Validar fecha
     */
    public static function fecha($fecha, $formato = 'Y-m-d') {
        if (empty($fecha)) {
            return 'La fecha es requerida';
        }
        
        $dateTime = DateTime::createFromFormat($formato, $fecha);
        if (!$dateTime || $dateTime->format($formato) !== $fecha) {
            return 'Formato de fecha inválido';
        }
        
        return true;
    }
    
    /**
     * Validar fecha de nacimiento
     */
    public static function fechaNacimiento($fechaNacimiento) {
        $validacionFecha = self::fecha($fechaNacimiento);
        if ($validacionFecha !== true) {
            return $validacionFecha;
        }
        
        $fecha = new DateTime($fechaNacimiento);
        $hoy = new DateTime();
        
        if ($fecha > $hoy) {
            return 'La fecha de nacimiento no puede ser futura';
        }
        
        $edad = $hoy->diff($fecha)->y;
        if ($edad > 120) {
            return 'La fecha de nacimiento no es válida';
        }
        
        return true;
    }
    
    /**
     * Validar edad
     */
    public static function edad($edad) {
        if (!is_numeric($edad)) {
            return 'La edad debe ser un número';
        }
        
        $edad = (int)$edad;
        
        if ($edad < 0 || $edad > 120) {
            return 'La edad debe estar entre 0 y 120 años';
        }
        
        return true;
    }
    
    /**
     * Validar peso
     */
    public static function peso($peso) {
        if (empty($peso)) {
            return 'El peso es requerido';
        }
        
        if (!is_numeric($peso)) {
            return 'El peso debe ser un número';
        }
        
        $peso = (float)$peso;
        
        if ($peso <= 0 || $peso > 500) {
            return 'El peso debe estar entre 0.1 y 500 kg';
        }
        
        return true;
    }
    
    /**
     * Validar altura
     */
    public static function altura($altura) {
        if (empty($altura)) {
            return 'La altura es requerida';
        }
        
        if (!is_numeric($altura)) {
            return 'La altura debe ser un número';
        }
        
        $altura = (float)$altura;
        
        if ($altura <= 0 || $altura > 300) {
            return 'La altura debe estar entre 0.1 y 300 cm';
        }
        
        return true;
    }
    
    /**
     * Validar dirección
     */
    public static function direccion($direccion) {
        if (empty($direccion)) {
            return 'La dirección es requerida';
        }
        
        if (strlen(trim($direccion)) < 5) {
            return 'La dirección debe tener al menos 5 caracteres';
        }
        
        return true;
    }
    
    /**
     * Validar archivo de imagen
     */
    public static function imagenArchivo($archivo) {
        if (empty($archivo) || $archivo['error'] === UPLOAD_ERR_NO_FILE) {
            return 'No se ha seleccionado ningún archivo';
        }
        
        if ($archivo['error'] !== UPLOAD_ERR_OK) {
            return 'Error al subir el archivo';
        }
        
        // Verificar tamaño
        $maxSize = defined('MAX_FILE_SIZE') ? MAX_FILE_SIZE : 5 * 1024 * 1024; // 5MB por defecto
        if ($archivo['size'] > $maxSize) {
            return 'El archivo es demasiado grande. Máximo ' . ($maxSize / 1024 / 1024) . 'MB';
        }
        
        // Verificar tipo de archivo
        $tiposPermitidos = defined('ALLOWED_IMAGE_TYPES') ? ALLOWED_IMAGE_TYPES : ['jpg', 'jpeg', 'png', 'gif'];
        $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
        
        if (!in_array($extension, $tiposPermitidos)) {
            return 'Tipo de archivo no permitido. Solo: ' . implode(', ', $tiposPermitidos);
        }
        
        // Verificar que realmente sea una imagen
        $infoImagen = getimagesize($archivo['tmp_name']);
        if (!$infoImagen) {
            return 'El archivo no es una imagen válida';
        }
        
        return true;
    }
    
    /**
     * Validar múltiples campos
     */
    public static function validarCampos($datos, $reglas) {
        $errores = [];
        
        foreach ($reglas as $campo => $reglascampo) {
            $valor = $datos[$campo] ?? null;
            
            foreach ($reglascampo as $regla) {
                $resultado = null;
                
                switch ($regla) {
                    case 'requerido':
                        if (empty($valor)) {
                            $errores[$campo] = "El campo {$campo} es requerido";
                        }
                        break;
                        
                    case 'email':
                        if (!empty($valor)) {
                            $resultado = self::email($valor);
                        }
                        break;
                        
                    case 'telefono':
                        if (!empty($valor)) {
                            $resultado = self::telefono($valor);
                        }
                        break;
                        
                    case 'nombre':
                        if (!empty($valor)) {
                            $resultado = self::nombre($valor);
                        }
                        break;
                        
                    case 'fecha':
                        if (!empty($valor)) {
                            $resultado = self::fecha($valor);
                        }
                        break;
                        
                    case 'peso':
                        if (!empty($valor)) {
                            $resultado = self::peso($valor);
                        }
                        break;
                        
                    case 'altura':
                        if (!empty($valor)) {
                            $resultado = self::altura($valor);
                        }
                        break;
                }
                
                if ($resultado !== null && $resultado !== true) {
                    $errores[$campo] = $resultado;
                    break; // No continuar con más reglas para este campo
                }
            }
        }
        
        return $errores;
    }
    
    /**
     * Limpiar y sanitizar entrada
     */
    public static function limpiar($valor) {
        if (is_string($valor)) {
            return trim(htmlspecialchars($valor, ENT_QUOTES, 'UTF-8'));
        }
        
        return $valor;
    }
    
    /**
     * Limpiar array de datos
     */
    public static function limpiarArray($datos) {
        $datosLimpios = [];
        
        foreach ($datos as $clave => $valor) {
            if (is_array($valor)) {
                $datosLimpios[$clave] = self::limpiarArray($valor);
            } else {
                $datosLimpios[$clave] = self::limpiar($valor);
            }
        }
        
        return $datosLimpios;
    }
    
    /**
     * Validar CSRF token
     */
    public static function csrfToken($token) {
        if (!isset($_SESSION['csrf_token'])) {
            return 'Token CSRF no encontrado';
        }
        
        if (!hash_equals($_SESSION['csrf_token'], $token)) {
            return 'Token CSRF inválido';
        }
        
        return true;
    }
    
    /**
     * Generar token CSRF
     */
    public static function generarCsrfToken() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;
        
        return $token;
    }
}