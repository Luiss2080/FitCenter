# 💪 FitCenter - Sistema de Gestión para Gimnasios

<div align="center">

![FitCenter Logo](https://img.shields.io/badge/💪-FitCenter-ff6b6b?style=for-the-badge&labelColor=4ecdc4)

![Version](https://img.shields.io/badge/version-1.0.0-blue.svg?style=flat-square)
![PHP](https://img.shields.io/badge/PHP-8.1+-purple.svg?style=flat-square)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-orange.svg?style=flat-square)
![License](https://img.shields.io/badge/license-MIT-green.svg?style=flat-square)
![Stars](https://img.shields.io/github/stars/Luiss2080/care_center?style=flat-square)
![Forks](https://img.shields.io/github/forks/Luiss2080/care_center?style=flat-square)

**Sistema web integral y moderno para la gestión completa de gimnasios**  
*Unifica administración de miembros, ventas, clases y reportes en una plataforma intuitiva*

[🚀 Demo en Vivo](#-demo) • [📖 Documentación](#-instalación) • [🤝 Contribuir](#-contribuir) • [📞 Soporte](#-soporte)

</div>

---

## 🌟 ¿Por qué FitCenter?

FitCenter transforma la gestión de tu gimnasio con tecnología moderna y una interfaz intuitiva. Diseñado específicamente para propietarios de gimnasios que buscan **eficiencia**, **control total** y **crecimiento sostenible**.

---

## 📋 Índice

- [Descripción General](#-descripción-general)
- [Roles del Sistema](#-roles-del-sistema)
- [Módulos Funcionales](#-módulos-funcionales)
- [Stack Tecnológico](#-stack-tecnológico)
- [Modelo de Datos](#-modelo-de-datos)
- [Instalación](#-instalación)
- [Flujo de Trabajo](#-flujo-de-trabajo)

---

## 🎯 Características Principales

<div align="center">
<table>
<tr>
<td align="center">

### 👥 Gestión de Miembros
Control completo de membresías, renovaciones y seguimiento de clientes

</td>
<td align="center">

### 🛒 Punto de Venta
Sistema integrado para productos, suplementos y accesorios deportivos

</td>
</tr>
<tr>
<td align="center">

### 📅 Clases Grupales  
Programación de horarios, reservas online y control de asistencia

</td>
<td align="center">

### 📊 Analytics & Reportes
Dashboard ejecutivo con métricas en tiempo real y análisis de rendimiento

</td>
</tr>
</table>
</div>

### 🏋️ ¿Para quién es FitCenter?

<div align="center">

| Tipo de Gimnasio | ✅ Perfecto para |
|------------------|------------------|
| 🏋️ **Gimnasios Tradicionales** | Área de pesas, cardio y clases grupales |
| 🔥 **CrossFit / Funcional** | WODs programados y seguimiento de atletas |
| 🧘 **Estudios Yoga/Pilates** | Clases especializadas y membresías flexibles |
| 🥊 **Artes Marciales** | Entrenamientos por disciplina y torneos |
| ✨ **Gimnasios Boutique** | Experiencia premium con venta de productos |

</div>

---

## 👥 Roles del Sistema

### 🔴 **ADMIN** (Propietario/Gerente)

**Acceso Total al Sistema**

```
Panel de Control:
├── 📊 Dashboard con métricas generales
│   ├── Ingresos del mes
│   ├── Nuevos miembros
│   ├── Membresías por vencer
│   ├── Productos con stock bajo
│   └── Clases con mayor asistencia
│
├── 👥 Gestión de Usuarios del Sistema
│   ├── Crear/editar vendedores
│   ├── Crear/editar clientes
│   ├── Ver actividad de vendedores
│   └── Suspender/activar usuarios
│
├── ⚙️ Configuración General
│   ├── Datos del gimnasio
│   ├── Planes de membresía (precios, duración)
│   ├── Tipos de disciplinas/clases
│   ├── Categorías de productos
│   ├── Horarios de atención
│   └── Configuración de emails
│
└── 📈 Reportes Avanzados
    ├── Ventas totales (productos + membresías)
    ├── Comisiones de vendedores
    ├── Retención de miembros
    ├── Productos más vendidos
    ├── Clases más populares
    └── Proyección de ingresos
```

---

### 🟢 **VENDEDOR** (Staff/Recepción)

**Operaciones del Día a Día**

```
Panel de Vendedor:
├── 🏋️ Gestión de Miembros
│   ├── Registrar nuevo miembro
│   ├── Renovar membresía
│   ├── Editar datos del miembro
│   ├── Ver estado de cuenta
│   ├── Congelar membresía temporal
│   └── Check-in manual de miembros
│
├── 🛒 Ventas de Productos
│   ├── Buscar producto
│   ├── Registrar venta
│   ├── Aplicar descuentos
│   ├── Generar ticket/factura
│   └── Ver historial de ventas propias
│
├── 📦 Gestión de Inventario
│   ├── Ver stock de productos
│   ├── Registrar entrada de productos
│   ├── Registrar salida (merma/vencimiento)
│   ├── Alertas de stock bajo
│   └── Solicitar pedido al admin
│
├── 📅 Gestión de Clases
│   ├── Ver calendario de clases
│   ├── Crear/editar clase
│   ├── Asignar instructor
│   ├── Ver lista de inscritos
│   ├── Marcar asistencia
│   └── Cancelar clase (notifica a inscritos)
│
└── 💰 Caja del Día
    ├── Apertura de caja
    ├── Registro de ingresos
    ├── Registro de egresos
    ├── Arqueo de caja
    └── Cierre de caja con reporte
```

---

### 🔵 **CLIENTE** (Miembro del Gym)

**Portal de Autogestión**

```
Portal del Cliente:
├── 🏠 Mi Dashboard
│   ├── Estado de mi membresía
│   ├── Días restantes
│   ├── Mis próximas clases
│   ├── Mi historial de asistencia
│   └── Notificaciones
│
├── 📅 Clases y Horarios
│   ├── Ver calendario de clases disponibles
│   ├── Filtrar por disciplina
│   ├── Reservar clase (si hay cupo)
│   ├── Cancelar mi reserva (hasta 2h antes)
│   ├── Ver mis clases reservadas
│   └── Historial de clases asistidas
│
├── 🛍️ Catálogo de Productos
│   ├── Ver productos disponibles
│   ├── Filtrar por categoría
│   ├── Ver detalles y precios
│   ├── Ver promociones activas
│   └── Consultar disponibilidad
│   (Compra en recepción, no e-commerce)
│
├── 💳 Mi Cuenta
│   ├── Datos personales
│   ├── Historial de pagos
│   ├── Próximo vencimiento
│   ├── Descargar recibos
│   └── Renovar membresía online (si está configurado)
│
└── 📞 Contacto
    ├── Datos del gimnasio
    ├── Horarios de atención
    ├── Enviar mensaje/consulta
    └── Sugerencias
```

---

## 🧩 Módulos Funcionales

### **1. Módulo de Miembros** 👥

**Gestión simplificada pero completa:**

#### Registro de Miembro (Vendedor)

```php
Datos Básicos:
├── Nombre completo
├── Email (único)
├── Teléfono
├── Fecha de nacimiento
├── Género
└── Foto (opcional)

Datos de Contacto:
├── Dirección
└── Contacto de emergencia (nombre + teléfono)

Membresía Inicial:
├── Tipo de plan (mensual, trimestral, anual)
├── Fecha de inicio
├── Forma de pago
└── Monto pagado
```

#### Perfil del Miembro

```php
Información visible:
├── Estado: Activo, Por vencer (7 días), Vencido, Suspendido
├── Días restantes de membresía
├── Historial de pagos (últimos 6 meses)
├── Asistencias del mes actual
├── Clases reservadas próximamente
└── Compras de productos (últimas 10)

Acciones disponibles:
├── Editar datos
├── Renovar membresía
├── Congelar membresía (hasta 30 días)
├── Cambiar plan
├── Registrar pago
└── Ver historial completo
```

---

### **2. Módulo de Planes de Membresía** 💳

**Configuración flexible (ADMIN):**

```php
Planes del Gimnasio:
├── Plan Básico
│   ├── Nombre: "Mensual Básico"
│   ├── Duración: 30 días
│   ├── Precio: $30
│   ├── Descripción: "Acceso a área de pesas y cardio"
│   ├── Clases grupales: 4 por mes
│   └── Acceso: Lun-Vie 6am-10pm
│
├── Plan Premium
│   ├── Nombre: "Mensual Premium"
│   ├── Duración: 30 días
│   ├── Precio: $50
│   ├── Descripción: "Acceso completo + clases ilimitadas"
│   ├── Clases grupales: Ilimitadas
│   └── Acceso: 24/7
│
├── Plan Trimestral
│   ├── 15% descuento
│   └── 90 días
│
└── Plan Anual
    ├── 25% descuento
    └── 365 días
```

**Gestión de Renovaciones:**

```php
Sistema Automático:
├── Notificación 7 días antes de vencer
├── Notificación 3 días antes
├── Notificación el día del vencimiento
├── Período de gracia: 3 días (configurable)
└── Suspensión automática después de gracia

Renovación Manual (Vendedor):
├── Buscar miembro
├── Ver estado actual
├── Seleccionar nuevo plan
├── Registrar pago
└── Activación inmediata
```

---

### **3. Módulo de Productos e Inventario** 🛒

**Gestión de Tienda del Gym:**

#### Catálogo de Productos (ADMIN configura, VENDEDOR vende)

```php
Categorías:
├── 💊 Suplementos
│   ├── Proteínas (whey, vegana, etc.)
│   ├── Creatina
│   ├── Pre-workout
│   ├── Aminoácidos (BCAA, glutamina)
│   ├── Quemadores de grasa
│   └── Vitaminas
│
├── 👕 Ropa Deportiva
│   ├── Camisetas
│   ├── Shorts/Pants
│   ├── Tops deportivos
│   └── Sudaderas
│
├── 🧤 Accesorios
│   ├── Guantes
│   ├── Correas de levantamiento
│   ├── Muñequeras/Rodilleras
│   ├── Shakers
│   ├── Botellas de agua
│   └── Toallas
│
└── 🥤 Bebidas/Snacks
    ├── Bebidas isotónicas
    ├── Barras proteicas
    └── Snacks saludables

Por cada producto:
├── Código/SKU
├── Nombre
├── Categoría
├── Precio de compra
├── Precio de venta
├── Stock actual
├── Stock mínimo (alerta)
├── Imagen del producto
├── Descripción
└── Estado (activo/inactivo)
```

#### Control de Inventario (VENDEDOR)

```php
Movimientos de Stock:
├── Entrada de Productos
│   ├── Fecha
│   ├── Proveedor
│   ├── Producto
│   ├── Cantidad
│   ├── Costo unitario
│   └── Total
│
├── Salida por Venta
│   └── Descuenta automáticamente al vender
│
└── Ajustes de Inventario
    ├── Merma (producto dañado)
    ├── Vencimiento
    ├── Donación
    └── Robo/pérdida

Reportes:
├── Productos con stock bajo
├── Productos más vendidos
├── Valor total del inventario
└── Productos sin movimiento (>60 días)
```

---

### **4. Módulo de Clases y Disciplinas** 📅

**Sistema de Reservas Simplificado:**

#### Configuración de Disciplinas (ADMIN)

```php
Disciplinas del Gimnasio:
├── Nombre: "CrossFit"
│   ├── Descripción
│   ├── Duración típica: 60 min
│   ├── Nivel: Todos los niveles
│   └── Imagen
│
├── Nombre: "Yoga"
│   ├── Descripción
│   ├── Duración típica: 45 min
│   ├── Nivel: Principiante/Intermedio
│   └── Imagen
│
└── Otros ejemplos:
    ├── Spinning
    ├── Funcional
    ├── Pilates
    ├── GAP
    └── Boxeo
```

#### Calendario de Clases (VENDEDOR crea, CLIENTE reserva)

```php
Programación Semanal:
├── Lunes
│   ├── 06:00 AM - CrossFit (Instructor: Juan) [20/25 cupos]
│   ├── 08:00 AM - Yoga (Instructor: María) [15/20 cupos]
│   ├── 06:00 PM - Spinning (Instructor: Carlos) [18/30 cupos]
│   └── 07:30 PM - Funcional (Instructor: Juan) [12/25 cupos]
│
├── Martes...
└── ...

Por cada clase:
├── Disciplina
├── Día de la semana
├── Hora de inicio
├── Duración
├── Instructor
├── Capacidad máxima
├── Cupos disponibles
└── Estado (activa/cancelada)
```

#### Sistema de Reservas

```php
Cliente reserva clase:
├── Ver calendario de la semana
├── Filtrar por disciplina
├── Click en clase disponible
├── Confirmar reserva
└── Recibe confirmación por email

Validaciones automáticas:
├── ✓ Membresía activa
├── ✓ Cupo disponible
├── ✓ No tiene otra clase a la misma hora
├── ✓ No excede límite de reservas según su plan
└── ✓ Puede cancelar hasta 2 horas antes

Vendedor gestiona:
├── Ver lista de inscritos
├── Marcar asistencia (presente/ausente)
├── Agregar cliente manualmente
├── Cancelar clase (notifica a todos)
└── Ver estadísticas de asistencia
```

---

### **5. Módulo de Ventas y Facturación** 💰

**Punto de Venta Integrado (VENDEDOR):**

#### Proceso de Venta

```php
Paso 1: Buscar productos
├── Por código de barras (scanner)
├── Por nombre
└── Por categoría

Paso 2: Agregar al carrito
├── Producto
├── Precio unitario
├── Cantidad
├── Subtotal
└── Aplicar descuento (%)

Paso 3: Finalizar venta
├── Seleccionar cliente (opcional)
├── Total a pagar
├── Método de pago (efectivo, tarjeta, transferencia)
├── Monto recibido
├── Cambio
└── Generar ticket/factura

Ticket incluye:
├── Logo del gimnasio
├── Datos fiscales
├── Número de venta
├── Fecha y hora
├── Vendedor
├── Cliente (si aplica)
├── Detalle de productos
├── Total
└── Código QR (opcional)
```

#### Registro de Pagos de Membresía

```php
Cuando cliente paga membresía:
├── Buscar cliente
├── Ver estado actual
├── Tipo de renovación (mensual, trimestral, anual)
├── Monto a cobrar (con descuentos si aplica)
├── Método de pago
├── Generar recibo
├── Actualizar fecha de vencimiento
└── Enviar confirmación por email

Tipos de pago:
├── Efectivo
├── Tarjeta
├── Transferencia
└── Pago parcial (abonos)
```

---

### **6. Módulo de Check-in/Asistencia** ✅

**Control de Acceso Simplificado:**

```php
Método 1: Check-in Manual (Vendedor)
├── Buscar miembro por nombre, email o código
├── Validación automática:
│   ├── ✓ Membresía activa
│   ├── ✗ Membresía vencida → Notificar renovación
│   └── ✗ Cuenta suspendida → Bloquear acceso
├── Registrar entrada
└── Mostrar: "Bienvenido/a [Nombre], ¡Buen entrenamiento!"

Método 2: Código QR del Cliente
├── Cliente muestra QR desde su perfil web
├── Vendedor escanea QR
├── Sistema valida y registra automáticamente
└── Mensaje de bienvenida

Registro incluye:
├── Fecha y hora
├── Miembro
├── Vendedor que registró (si es manual)
└── Tipo de acceso (normal, clase grupal)
```

**Reportes de Asistencia (ADMIN):**

```php
Métricas disponibles:
├── Asistencias de hoy
├── Horarios con mayor afluencia
├── Promedio de asistencias por día
├── Miembros más activos del mes
├── Miembros inactivos (>15 días sin asistir)
└── Tasa de asistencia por tipo de plan
```

---

### **7. Módulo de Reportes** 📊

**Dashboard Ejecutivo (ADMIN):**

```php
Métricas Principales:
├── 💰 Ingresos del Mes
│   ├── Membresías: $5,230
│   ├── Productos: $1,840
│   ├── Total: $7,070
│   └── vs mes anterior: +12%
│
├── 👥 Miembros
│   ├── Total activos: 87
│   ├── Nuevos este mes: 12
│   ├── Bajas: 3
│   └── Por vencer (próximos 7 días): 8
│
├── 📦 Inventario
│   ├── Valor total: $3,450
│   ├── Productos con stock bajo: 5
│   └── Productos sin stock: 2
│
└── 📅 Clases
    ├── Asistencia promedio: 78%
    ├── Clase más popular: CrossFit (92%)
    └── Total de reservas activas: 154

Gráficas visuales:
├── Ingresos por mes (últimos 6 meses)
├── Nuevos miembros vs bajas
├── Productos más vendidos (top 10)
├── Asistencia por día de la semana
└── Ocupación de clases por horario
```

**Reportes Descargables (PDF/Excel):**

```php
├── Reporte de Ventas (por período)
├── Reporte de Membresías Activas
├── Reporte de Inventario
├── Reporte de Asistencias
├── Estado de Cuenta de Caja
└── Reporte de Comisiones (vendedores)
```

---

### **8. Módulo de Notificaciones** 🔔

**Sistema Automatizado (EmailService integrado):**

```php
Notificaciones a Clientes:
├── Bienvenida al registrarse
├── Confirmación de pago de membresía
├── Recordatorio 7 días antes de vencer
├── Recordatorio 3 días antes de vencer
├── Membresía vencida
├── Clase reservada confirmada
├── Recordatorio de clase (2 horas antes)
├── Clase cancelada por el gimnasio
└── Promociones especiales (manual por ADMIN)

Notificaciones a Vendedores:
├── Nuevo miembro registrado
├── Membresía por vencer hoy
├── Stock bajo de producto
└── Clase con baja asistencia

Notificaciones a Admin:
├── Resumen diario de ventas
├── Alertas de inventario crítico
├── Reporte semanal de ingresos
└── Miembros inactivos (>30 días)
```

---

## 🛠️ Stack Tecnológico

### Backend

```
├── PHP 8.0+ (con tipado estricto)
├── MySQL 8.0+ (base de datos relacional)
├── PDO (prepared statements)
├── Composer (gestión de dependencias)
└── Arquitectura MVC
```

### Frontend

```
├── HTML5 + CSS3
├── Bootstrap 5.3 (UI responsive)
├── JavaScript ES6+
├── Chart.js (gráficas)
├── FullCalendar.js (calendario de clases)
├── SweetAlert2 (alertas elegantes)
└── DataTables (tablas interactivas)
```

### Servicios (ya existentes en tu estructura)

```
├── EmailService.php (notificaciones)
├── Logger.php (auditoría)
├── TokenService.php (tokens de sesión)
└── Validador.php (validaciones)
```

---

## 🗄️ Modelo de Datos

### Diagrama Entidad-Relación

```
┌─────────────┐         ┌─────────────┐         ┌─────────────┐
│  USUARIOS   │────────▶│   MIEMBROS  │◀────────│  MEMBRESIAS │
│             │         │             │         │             │
│ id          │         │ id          │         │ id          │
│ email       │         │ id_usuario  │         │ id_miembro  │
│ password    │         │ telefono    │         │ tipo_plan   │
│ rol         │         │ fecha_nac   │         │ fecha_inicio│
└─────────────┘         │ contacto_em │         │ fecha_fin   │
       │                └─────────────┘         │ estado      │
       │                       │                └─────────────┘
       │                       │
       ▼                       ▼
┌─────────────┐         ┌─────────────┐
│  VENTAS     │         │   RESERVAS  │
│             │         │             │
│ id          │         │ id          │
│ id_vendedor │         │ id_miembro  │
│ id_cliente  │         │ id_clase    │
│ total       │         │ fecha       │
│ metodo_pago │         │ estado      │
└─────────────┘         └─────────────┘
       │                       │
       │                       │
       ▼                       ▼
┌─────────────┐         ┌─────────────┐
│ DETALLE_    │         │   CLASES    │
│ VENTAS      │         │             │
│             │         │ id          │
│ id_venta    │         │ disciplina  │
│ id_producto │         │ instructor  │
│ cantidad    │         │ dia_semana  │
│ precio      │         │ hora        │
└─────────────┘         │ capacidad   │
       │                └─────────────┘
       │                       │
       ▼                       ▼
┌─────────────┐         ┌─────────────┐
│  PRODUCTOS  │         │ DISCIPLINAS │
│             │         │             │
│ id          │         │ id          │
│ nombre      │         │ nombre      │
│ categoria   │         │ descripcion │
│ precio      │         │ duracion    │
│ stock       │         │ imagen      │
└─────────────┘         └─────────────┘
       │
       ▼
┌─────────────┐
│ MOVIMIENTOS │
│ INVENTARIO  │
│             │
│ id          │
│ id_producto │
│ tipo        │
│ cantidad    │
│ fecha       │
└─────────────┘
```

### Tablas Principales (Resumen)

#### usuarios

```sql
- id, email, password, rol (admin/vendedor/cliente)
- nombre, telefono, estado, verificado
- creado_en, actualizado_en
```

#### miembros

```sql
- id, id_usuario, fecha_nacimiento, genero
- direccion, contacto_emergencia
- foto_perfil, estado
```

#### membresias

```sql
- id, id_miembro, tipo_plan
- fecha_inicio, fecha_fin
- monto_pagado, metodo_pago, estado
```

#### productos

```sql
- id, nombre, categoria, descripcion
- precio_compra, precio_venta
- stock_actual, stock_minimo, imagen
```

#### ventas

```sql
- id, id_vendedor, id_cliente (nullable)
- total, metodo_pago, fecha
```

#### detalle_ventas

```sql
- id, id_venta, id_producto
- cantidad, precio_unitario, subtotal
```

#### clases

```sql
- id, id_disciplina, dia_semana, hora
- instructor, capacidad, estado
```

#### reservas_clases

```sql
- id, id_miembro, id_clase, fecha
- estado (confirmada/cancelada/asistio)
```

#### asistencias

```sql
- id, id_miembro, fecha_hora
- tipo_acceso (normal/clase), id_vendedor
```

---

## 🚀 Instalación Rápida

### 📋 Prerrequisitos

<div align="center">

| Requisito | Versión Mínima | Recomendada |
|-----------|----------------|-------------|
| 🐘 **PHP** | 8.1+ | 8.2+ |
| 🗄️ **MySQL** | 8.0+ | 8.0.35+ |
| 🌐 **Servidor Web** | Apache/Nginx | XAMPP (desarrollo) |
| 📦 **Composer** | 2.0+ | Última versión |

</div>

### ⚡ Instalación en 3 Pasos

#### 1️⃣ Clonar el Repositorio

```bash
# Clonar desde GitHub
git clone https://github.com/Luiss2080/care_center.git
cd care_center

# O descargar y extraer ZIP en tu servidor web
# Ejemplo XAMPP: C:\xampp\htdocs\care_center
```

#### 2️⃣ Configurar Base de Datos

```sql
-- Crear la base de datos
CREATE DATABASE fitcenter_gym CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

#### 3️⃣ Ejecutar Instalación Automática

```bash
# Un solo comando instala todo el sistema
php scripts/instalar_autenticacion_completa.php
```

<div align="center">

### 🎉 ¡Listo! Tu FitCenter está funcionando

</div>

### 🔑 Credenciales de Acceso

<div align="center">

| 👤 Rol | 📧 Email | 🔒 Contraseña | 🎯 Acceso |
|---------|----------|---------------|-----------|
| 🔴 **Administrador** | `admin@fitcenter.com` | `password123` | Panel completo de administración |
| 🟢 **Vendedor/Staff** | `vendedor@fitcenter.com` | `password123` | Punto de venta y operaciones |
| 🔵 **Cliente/Miembro** | `cliente@fitcenter.com` | `password123` | Portal personal del miembro |

</div>

### 🌐 URLs de Acceso

```
🏠 Sistema Principal: http://localhost/care_center/
🔐 Login:            http://localhost/care_center/view/auth/login.php
🩺 Diagnóstico:      http://localhost/care_center/diagnostico.php
```

## 🎮 Demo

<div align="center">

### 🖥️ Capturas de Pantalla

| Panel de Administración | Punto de Venta | Portal del Cliente |
|------------------------|----------------|-------------------|
| ![Admin Dashboard](https://via.placeholder.com/250x150/ff6b6b/ffffff?text=Admin+Dashboard) | ![Seller POS](https://via.placeholder.com/250x150/667eea/ffffff?text=Punto+de+Venta) | ![Client Portal](https://via.placeholder.com/250x150/4ecdc4/ffffff?text=Portal+Cliente) |

### ✨ Características en Acción

🎥 **[Ver Demo en Video]** (próximamente)  
🌐 **[Probar Demo Online]** (próximamente)

</div>

---

## 🔧 Configuración Avanzada

### Variables de Entorno

Edita `config/config.php` para personalizar:

```php
// Configuración básica
define('APP_NAME', 'Tu Gimnasio');
define('BASE_URL', '/tu_ruta');

// Debug (false en producción)
define('DEBUG_MODE', false);
```

### Configuración de Email

Para notificaciones automáticas, configura el `EmailService.php`:

```php
// En utils/EmailService.php
private $smtp_host = 'smtp.gmail.com';
private $smtp_user = 'tu-email@gmail.com';
private $smtp_pass = 'tu-app-password';
```

---

## 📱 Próximos Pasos

FitFlow está diseñado para ser expandible. Las siguientes funcionalidades están preparadas:

- ✅ **Sistema base completamente funcional**
- 🔨 **Panel de administración** (en desarrollo)
- 🔨 **Portal del cliente** (en desarrollo)  
- 🔨 **Sistema de reservas** (en desarrollo)
- 🔨 **Punto de venta** (en desarrollo)
- 🔨 **Reportes y analytics** (en desarrollo)

### Desarrollo

Para contribuir al desarrollo:

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/nueva-funcionalidad`)
3. Commit tus cambios (`git commit -am 'Agregar nueva funcionalidad'`)
4. Push a la rama (`git push origin feature/nueva-funcionalidad`)
5. Crear Pull Request

---

## 🤝 Contribuir

<div align="center">

¿Te gusta FitCenter? ¡Ayúdanos a mejorarlo!

[![GitHub Issues](https://img.shields.io/github/issues/Luiss2080/care_center?style=flat-square)](https://github.com/Luiss2080/care_center/issues)
[![GitHub Pull Requests](https://img.shields.io/github/issues-pr/Luiss2080/care_center?style=flat-square)](https://github.com/Luiss2080/care_center/pulls)

</div>

### 🚀 Cómo Contribuir

1. 🍴 **Fork** el proyecto
2. 🌿 Crea una rama: `git checkout -b feature/nueva-funcionalidad`
3. ✏️ Haz tus cambios y commit: `git commit -am 'Agregar nueva funcionalidad'`
4. 📤 Push a la rama: `git push origin feature/nueva-funcionalidad`
5. 🎯 Crea un **Pull Request**

### 💡 Ideas para Contribuir

- 🐛 Reportar bugs o problemas
- ✨ Sugerir nuevas características
- 📝 Mejorar documentación
- 🌍 Traducciones a otros idiomas
- 🎨 Mejoras en el diseño UI/UX

---

## 📞 Soporte y Comunidad

<div align="center">

### 🆘 ¿Necesitas Ayuda?

| Canal | Descripción | Respuesta |
|-------|-------------|-----------|
| 🐛 **[GitHub Issues](https://github.com/Luiss2080/care_center/issues)** | Bugs y problemas técnicos | 24-48h |
| 💬 **[Discussions](https://github.com/Luiss2080/care_center/discussions)** | Ideas y preguntas generales | Comunidad |
| 📧 **Email** | soporte@fitcenter.com | 48-72h |
| 📖 **Wiki** | Documentación detallada | 24/7 |

### ⭐ ¡Dale una estrella si te gusta el proyecto!

</div>

---

## 📄 Licencia

Este proyecto está bajo la licencia **MIT**. Ver el archivo [LICENSE](LICENSE) para más detalles.

---

<div align="center">

### 🏆 **¡Bienvenido a FitCenter!** 

**La revolución digital de tu gimnasio comienza aquí** 💪

[![Made with Love](https://img.shields.io/badge/Made%20with-❤️-red?style=for-the-badge)](https://github.com/Luiss2080/care_center)

*Desarrollado por [Luis Montes](https://github.com/Luiss2080) con mucho ☕ y 💪*

</div>
