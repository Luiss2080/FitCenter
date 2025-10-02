# CareCenter - Sistema de Gestión Nutricional y Catering

## Descripción

CareCenter es una aplicación web integral diseñada para gestionar servicios de asesoramiento nutricional y catering personalizado. El sistema permite la gestión completa del proceso desde la consulta nutricional hasta la entrega de alimentos, incluyendo la planificación de dietas, producción en cocina, y logística de entregas.

## Características Principales

### 🏥 Gestión de Pacientes
- Registro completo de pacientes con datos personales y médicos
- Historial de mediciones corporales (peso, IMC, grasa corporal, etc.)
- Seguimiento de condiciones médicas y alergias alimentarias
- Asignación de nutriólogos especializados

### 👨‍⚕️ Módulo de Nutrición
- Creación de planes nutricionales personalizados
- Biblioteca de recetas saludables y balanceadas
- Cálculo automático de valores nutricionales
- Seguimiento del progreso de pacientes

### 🍳 Gestión de Cocina
- Órdenes de producción automatizadas
- Control de inventario de ingredientes
- Seguimiento de preparación de alimentos
- Gestión de menús diarios y semanales

### 🚚 Sistema de Entregas
- Optimización de rutas de entrega
- Seguimiento GPS en tiempo real
- Confirmación de entregas con firma digital
- Gestión de horarios y zonas de cobertura

### 💰 Facturación y Pagos
- Generación automática de facturas
- Integración con múltiples métodos de pago
- Reportes financieros detallados
- Control de cuentas por cobrar

### 📊 Reportes y Analytics
- Dashboard con métricas en tiempo real
- Reportes de producción y entregas
- Análisis de rendimiento por nutriólogo
- Estadísticas de satisfacción del cliente

## Arquitectura Técnica

### Backend
- **Lenguaje:** PHP 8.1+
- **Arquitectura:** MVC (Modelo-Vista-Controlador)
- **Base de datos:** MySQL 8.0+ / MariaDB 10.5+
- **Servidor web:** Apache 2.4+ con mod_rewrite

### Frontend
- **HTML5** semántico y accesible
- **CSS3** con variables personalizadas y diseño responsivo
- **JavaScript ES6+** para interactividad
- **Diseño:** Mobile-first, responsivo

### Seguridad
- Autenticación basada en sesiones
- Sistema de roles y permisos granular
- Protección CSRF en formularios
- Validación y sanitización de datos
- Logs de auditoría completos

### Integrations APIs
- **Google Maps API:** Geolocalización y rutas
- **SMTP:** Envío de notificaciones por email
- **Webhooks:** Integración con sistemas externos

## Roles de Usuario

### 1. Administrador
- Gestión completa del sistema
- Configuración de usuarios y permisos
- Reportes ejecutivos
- Configuración del sistema

### 2. Nutriólogo
- Gestión de pacientes asignados
- Creación de planes nutricionales
- Seguimiento de consultas
- Análisis de progreso

### 3. Personal de Cocina
- Visualización de órdenes de producción
- Control de preparación de alimentos
- Gestión de inventario
- Reportes de producción

### 4. Repartidor
- Lista de entregas asignadas
- Navegación GPS optimizada
- Confirmación de entregas
- Reportes de ruta

### 5. Cliente/Paciente
- Acceso a su plan nutricional
- Historial de consultas
- Seguimiento de entregas
- Perfil personal

## Estructura del Proyecto

```
care_center/
├── config/          # Configuraciones del sistema
├── controladores/   # Lógica de negocio (Controllers)
├── modelos/         # Modelos de datos y acceso a BD
├── vistas/          # Templates y vistas (Views)
├── publico/         # Assets públicos y punto de entrada
├── database/        # Migraciones y seeds de BD
├── utilidades/      # Clases auxiliares y helpers
├── tests/           # Pruebas unitarias e integración
├── docs/            # Documentación del proyecto
├── logs/            # Archivos de log
└── storage/         # Archivos temporales y exportaciones
```

## Requisitos del Sistema

### Servidor
- PHP 8.1 o superior
- MySQL 8.0+ o MariaDB 10.5+
- Apache 2.4+ con mod_rewrite habilitado
- Extensiones PHP requeridas:
  - PDO y PDO_MySQL
  - JSON
  - OpenSSL
  - cURL
  - GD o Imagick (para procesamiento de imágenes)
  - Mbstring
  - Fileinfo

### Cliente
- Navegador web moderno (Chrome 90+, Firefox 88+, Safari 14+, Edge 90+)
- JavaScript habilitado
- Conexión a internet estable

## Instalación

Ver archivo `docs/instalacion_despliegue.md` para instrucciones detalladas de instalación y configuración.

## Metodología de Desarrollo

El proyecto sigue una metodología híbrida basada en:
- **Scrum** para la gestión ágil del proyecto
- **Cascada** para fases críticas que requieren documentación completa
- **DevOps** para integración y despliegue continuo

## Documentación

- [Requisitos del Sistema](docs/requisitos.md)
- [Arquitectura Técnica](docs/arquitectura.md)
- [Manual de Usuario](docs/manual_usuario.md)
- [Guía de Instalación](docs/instalacion_despliegue.md)
- [Diagrama ERD](docs/erd.png)

## Licencia

Este proyecto está licenciado bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

## Contribución

Ver `CONTRIBUTING.md` para las pautas de contribución al proyecto.

## Contacto

**CareCenter Development Team**
- Email: desarrollo@carecenter.com
- Teléfono: +52 (55) 1234-5678

---

© 2025 CareCenter. Todos los derechos reservados.