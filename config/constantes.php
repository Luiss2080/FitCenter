<?php
/**
 * Constantes de la aplicación CareCenter
 * Configuraciones que no cambian durante la ejecución
 */

// Cargar variables de entorno si no están cargadas
if (!function_exists('env')) {
    function env($key, $default = null) {
        $value = $_ENV[$key] ?? $_SERVER[$key] ?? $default;
        
        // Convertir strings booleanos
        if (is_string($value)) {
            switch (strtolower($value)) {
                case 'true':
                case 'on':
                case 'yes':
                case '1':
                    return true;
                case 'false':
                case 'off':
                case 'no':
                case '0':
                case '':
                    return false;
            }
        }
        
        return $value;
    }
}

// ====================================================================
// CONFIGURACIÓN DE LA APLICACIÓN
// ====================================================================
define('APP_NAME', env('APP_NAME', 'CareCenter'));
define('APP_VERSION', env('APP_VERSION', '1.0.0'));
define('APP_ENV', env('APP_ENV', 'development'));
define('APP_DEBUG', env('APP_DEBUG', true));
define('APP_TIMEZONE', env('APP_TIMEZONE', 'America/Mexico_City'));

// ====================================================================
// RUTAS DEL SISTEMA
// ====================================================================
define('ROOT_PATH', dirname(__DIR__));
define('CONFIG_PATH', ROOT_PATH . '/config');
define('PUBLIC_PATH', ROOT_PATH . '/publico');
define('UPLOADS_PATH', PUBLIC_PATH . env('UPLOADS_PATH', '/uploads'));
define('STORAGE_PATH', ROOT_PATH . env('STORAGE_PATH', '/storage'));
define('LOGS_PATH', ROOT_PATH . '/logs');
define('VIEWS_PATH', ROOT_PATH . '/vistas');
define('CONTROLLERS_PATH', ROOT_PATH . '/controladores');
define('MODELS_PATH', ROOT_PATH . '/modelos');
define('UTILS_PATH', ROOT_PATH . '/utilidades');

// ====================================================================
// URLs BASE
// ====================================================================
define('BASE_URL', rtrim(env('BASE_URL', 'http://localhost/care_center'), '/'));
define('ASSETS_URL', BASE_URL . '/publico');
define('API_URL', BASE_URL . '/api');

// ====================================================================
// CONFIGURACIÓN DE BASE DE DATOS
// ====================================================================
define('DB_CONNECTION', env('DB_CONNECTION', 'mysql'));
define('DB_HOST', env('DB_HOST', 'localhost'));
define('DB_PORT', env('DB_PORT', 3306));
define('DB_NAME', env('DB_NAME', 'carecenter_db'));
define('DB_USERNAME', env('DB_USERNAME', 'root'));
define('DB_PASSWORD', env('DB_PASSWORD', ''));
define('DB_CHARSET', env('DB_CHARSET', 'utf8mb4'));
define('DB_COLLATION', env('DB_COLLATION', 'utf8mb4_unicode_ci'));

// ====================================================================
// CONFIGURACIÓN DE SEGURIDAD
// ====================================================================
define('JWT_SECRET', env('JWT_SECRET', 'cambiar_clave_secreta'));
define('SESSION_SECRET', env('SESSION_SECRET', 'cambiar_sesion_secreta'));
define('ENCRYPTION_KEY', env('ENCRYPTION_KEY', 'cambiar_encriptacion'));
define('SESSION_LIFETIME', env('SESSION_LIFETIME', 7200));
define('SESSION_COOKIE_NAME', env('SESSION_COOKIE_NAME', 'carecenter_session'));
define('HASH_ALGORITHM', 'bcrypt');
define('FORCE_HTTPS', env('FORCE_HTTPS', false));

// ====================================================================
// CONFIGURACIÓN DE ARCHIVOS
// ====================================================================
define('MAX_FILE_SIZE', env('MAX_FILE_SIZE', 5242880)); // 5MB
define('ALLOWED_IMAGE_TYPES', explode(',', env('ALLOWED_IMAGE_TYPES', 'jpg,jpeg,png,gif,webp')));
define('ALLOWED_DOCUMENT_TYPES', explode(',', env('ALLOWED_DOCUMENT_TYPES', 'pdf,doc,docx,xls,xlsx')));

// ====================================================================
// CONFIGURACIÓN DE CORREO
// ====================================================================
define('MAIL_DRIVER', env('MAIL_DRIVER', 'smtp'));
define('MAIL_HOST', env('MAIL_HOST', 'smtp.gmail.com'));
define('MAIL_PORT', env('MAIL_PORT', 587));
define('MAIL_USERNAME', env('MAIL_USERNAME', ''));
define('MAIL_PASSWORD', env('MAIL_PASSWORD', ''));
define('MAIL_ENCRYPTION', env('MAIL_ENCRYPTION', 'tls'));
define('MAIL_FROM_ADDRESS', env('MAIL_FROM_ADDRESS', 'noreply@carecenter.local'));
define('MAIL_FROM_NAME', env('MAIL_FROM_NAME', 'CareCenter'));

// ====================================================================
// APIs EXTERNAS
// ====================================================================
define('GOOGLE_MAPS_API_KEY', env('GOOGLE_MAPS_API_KEY', ''));
define('NOMINATIM_API_URL', env('NOMINATIM_API_URL', 'https://nominatim.openstreetmap.org'));

// ====================================================================
// CONFIGURACIÓN DE LOGS
// ====================================================================
define('LOG_LEVEL', env('LOG_LEVEL', 'info'));
define('LOG_CHANNEL', env('LOG_CHANNEL', 'daily'));
define('LOG_MAX_FILES', env('LOG_MAX_FILES', 30));
define('LOG_MAX_SIZE', env('LOG_MAX_SIZE', 10485760));

// ====================================================================
// ROLES DE USUARIO
// ====================================================================
define('ROL_ADMIN', 1);
define('ROL_NUTRIOLOGO', 2);
define('ROL_COCINA', 3);
define('ROL_REPARTIDOR', 4);
define('ROL_CLIENTE', 5);

// Mapeo de nombres de roles
define('ROLES_NOMBRES', [
    ROL_ADMIN => 'Administrador',
    ROL_NUTRIOLOGO => 'Nutriólogo',
    ROL_COCINA => 'Personal de Cocina',
    ROL_REPARTIDOR => 'Repartidor',
    ROL_CLIENTE => 'Cliente'
]);

// ====================================================================
// ESTADOS DEL SISTEMA
// ====================================================================
// Estados de órdenes
define('ESTADO_PENDIENTE', 'pendiente');
define('ESTADO_CONFIRMADO', 'confirmado');
define('ESTADO_PREPARACION', 'preparacion');
define('ESTADO_LISTO', 'listo');
define('ESTADO_EN_CAMINO', 'en_camino');
define('ESTADO_ENTREGADO', 'entregado');
define('ESTADO_CANCELADO', 'cancelado');

// Estados de pagos
define('PAGO_PENDIENTE', 'pendiente');
define('PAGO_PROCESANDO', 'procesando');
define('PAGO_COMPLETADO', 'completado');
define('PAGO_FALLIDO', 'fallido');
define('PAGO_REEMBOLSADO', 'reembolsado');

// Estados de consultas
define('CONSULTA_PROGRAMADA', 'programada');
define('CONSULTA_CONFIRMADA', 'confirmada');
define('CONSULTA_COMPLETADA', 'completada');
define('CONSULTA_CANCELADA', 'cancelada');

// ====================================================================
// CONFIGURACIÓN ESPECÍFICA DE CARECENTER
// ====================================================================
define('DEFAULT_DELIVERY_TIME', env('DEFAULT_DELIVERY_TIME', 60));
define('MAX_DELIVERY_DISTANCE', env('MAX_DELIVERY_DISTANCE', 50));
define('DELIVERY_COST_PER_KM', env('DELIVERY_COST_PER_KM', 5.00));
define('KITCHEN_PREP_TIME_BUFFER', env('KITCHEN_PREP_TIME_BUFFER', 15));
define('MAX_ORDERS_PER_SLOT', env('MAX_ORDERS_PER_SLOT', 10));
define('DEFAULT_CALORIE_TARGET', env('DEFAULT_CALORIE_TARGET', 2000));

// ====================================================================
// INFORMACIÓN DE CONTACTO
// ====================================================================
define('TELEFONO_CONTACTO', env('TELEFONO_CONTACTO', '+52 55 1234 5678'));
define('EMAIL_CONTACTO', env('EMAIL_CONTACTO', 'contacto@carecenter.com'));
define('WHATSAPP_URL', env('WHATSAPP_URL', 'https://wa.me/5255123456789'));
define('DIRECCION_EMPRESA', env('DIRECCION_EMPRESA', 'Ciudad de México, México'));
define('HORARIOS_ATENCION', env('HORARIOS_ATENCION', 'Lun - Vie: 8:00 AM - 8:00 PM'));

// ====================================================================
// CONFIGURACIÓN DE LÍMITES
// ====================================================================
define('MAX_LOGIN_ATTEMPTS', env('MAX_LOGIN_ATTEMPTS', 5));
define('MAX_UPLOAD_FILES', env('MAX_UPLOAD_FILES', 10));
define('MACRO_TOLERANCE_PERCENT', env('MACRO_TOLERANCE_PERCENT', 10));