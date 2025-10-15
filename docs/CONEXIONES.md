# 📋 Documentación de Conexiones - FitCenter

## ✅ Estado de las Conexiones

### 🗄️ Base de Datos

- **Estado**: ✅ Conectado exitosamente
- **Base de datos**: `fitcenter`
- **Servidor**: `localhost`
- **Usuario**: `root`
- **Charset**: `utf8mb4`

### 📊 Tablas Verificadas

- ✅ `usuarios` (5 registros)
- ✅ `tokens_verificacion` (4 registros)
- ✅ `configuracion_sistema` (23 registros)
- ✅ `log_actividades` (12 registros)

### 👥 Usuarios de Prueba

- ✅ **Admin**: admin@fitcenter.com (Admin)
- ✅ **Vendedor**: vendedor@fitcenter.com (Juan)
- ✅ **Vendedor**: carlos@fitcenter.com (Carlos)
- ✅ **Cliente**: cliente@fitcenter.com (Ana)
- ✅ **Cliente**: maria@fitcenter.com (María)

## 🔧 Archivos de Configuración

### `config/conexion.php`

- Configuración de conexión PDO
- Manejo de errores y logging
- Función para nuevas conexiones
- Configuración de zona horaria MySQL

### `config/config.php`

- Constantes de aplicación
- Configuración de sesiones
- Configuración de email
- Configuración de seguridad
- Configuración de archivos

## 📦 Modelos Disponibles

### `models/BaseModel.php`

Clase base con funcionalidades comunes:

- `find($id)` - Buscar por ID
- `findAll()` - Buscar todos
- `findWhere()` - Buscar con condiciones
- `create($data)` - Crear registro
- `update($id, $data)` - Actualizar registro
- `delete($id)` - Eliminar registro
- `count()` - Contar registros
- Manejo de transacciones

### `models/Usuario.php`

Modelo específico para usuarios:

- `findByEmail($email)` - Buscar por email
- `emailExists($email)` - Verificar email
- `createUser($data)` - Crear usuario con hash
- `verifyPassword()` - Verificar contraseña
- `activateUser()` - Activar usuario
- `findByRole()` - Buscar por rol
- `getStats()` - Estadísticas de usuarios

### `models/LogActividad.php`

Modelo para log de actividades:

- `registrar()` - Registrar nueva actividad
- `getByUser()` - Actividades por usuario
- `getByTipo()` - Actividades por tipo
- `getRecientes()` - Actividades recientes
- `getByDate()` - Actividades por fecha
- `getStats()` - Estadísticas de actividades

### `models/TokenVerificacion.php`

Modelo para tokens de verificación:

- `generarToken()` - Generar nuevo token
- `verificarToken()` - Verificar validez
- `marcarUsado()` - Marcar como usado
- `invalidarTokensUsuario()` - Invalidar tokens de usuario
- `limpiarExpirados()` - Limpiar tokens expirados
- `getStats()` - Estadísticas de tokens

## 🧪 Scripts de Prueba

### `scripts/test_database.php`

- Verificación de conexión a BD
- Verificación de tablas y registros
- Verificación de usuarios de prueba
- Verificación de configuración del sistema

### `scripts/test_models.php`

- Prueba de funcionalidad de modelos
- Prueba de creación de registros
- Verificación de estadísticas

### `diagnostico.php`

- Diagnóstico web completo del sistema
- Verificación de archivos y permisos
- Enlaces de prueba

## 🌐 URLs de Acceso

- **Diagnóstico**: http://localhost/FitCenter/diagnostico.php
- **Página principal**: http://localhost/FitCenter/
- **Login**: http://localhost/FitCenter/view/auth/login.php
- **Bienvenida**: http://localhost/FitCenter/view/home/welcome.php

## ⚙️ Configuración del Sistema

La tabla `configuracion_sistema` contiene 23 configuraciones incluyendo:

- Configuración de email (SMTP)
- Configuración de seguridad
- Configuración de notificaciones
- Información del gimnasio

## 🔒 Seguridad

- Contraseñas hasheadas con `password_hash()`
- Tokens de verificación con expiración
- Manejo de intentos de login
- Bloqueo de cuentas
- Configuración de sesiones seguras

## 📝 Próximos Pasos

1. ✅ Conexión a base de datos configurada
2. ✅ Modelos creados y probados
3. 🔄 Crear controladores para las vistas
4. 🔄 Implementar sistema de autenticación
5. 🔄 Crear interfaces de usuario

---

**Fecha de actualización**: 15 de octubre de 2025  
**Estado**: 🟢 Conexiones funcionando correctamente
