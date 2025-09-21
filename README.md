# Sistema de Reservas Multitenant - Panel de Administración

Sistema completo de reservas multitenant con panel de administración, API REST, webhooks y widget integrable.

## 🚀 Acceso al Sistema

### 🔐 Super Administrador (CENTRAL)

| Funcionalidad | URL | Credenciales |
|---------------|-----|--------------|
| **Login Super Admin** | https://book.aimadarek.com/admin/login | `admin@book.aimadarek.com` / `SuperAdmin!2025` |
| **Dashboard Central** | https://book.aimadarek.com/admin/dashboard | Requiere login |
| **Gestión de Empresas** | https://book.aimadarek.com/admin/tenants | Requiere login |
| **Crear Nueva Empresa** | https://book.aimadarek.com/admin/tenants/create | Requiere login |

### URLs de Acceso por Tenant (PRODUCCIÓN)

| Tenant | URL del Panel | Widget Demo | Credenciales |
|--------|---------------|-------------|--------------|
| **Ranch** | https://ranch.book.aimadarek.com/panel | https://ranch.book.aimadarek.com/ | `ranch@demo.com` / `Demo!1234` |
| **Beerta Barbers** | https://beerta-barbers.book.aimadarek.com/panel | https://beerta-barbers.book.aimadarek.com/ | `beerta@demo.com` / `Demo!1234` |
| **Glow Beauty** | https://glow-beauty.book.aimadarek.com/panel | https://glow-beauty.book.aimadarek.com/ | `glow@demo.com` / `Demo!1234` |
| **Smile Dental** | https://smile-dental.book.aimadarek.com/panel | https://smile-dental.book.aimadarek.com/ | `smile@demo.com` / `Demo!1234` |

### Dominio Principal
- **Landing**: https://book.aimadarek.com/landing
- **Central**: https://book.aimadarek.com

### Usuarios Demo

Todos los tenants tienen el mismo usuario demo configurado:

- **Email**: `ranch@demo.com` (para ranch), `beerta@demo.com` (para beerta-barbers), etc.
- **Contraseña**: `Demo!1234`
- **Rol**: Owner (acceso completo a todas las funcionalidades)

## 📋 Funcionalidades del Panel

### ✅ Implementadas y Funcionales

#### 🏠 Dashboard
- **URL**: `/{tenant}/panel`
- **Funcionalidades**:
  - KPIs en tiempo real (reservas de hoy, semana, no-shows, canceladas)
  - Calendario semanal interactivo
  - Filtros por servicio y rango de fechas
  - Lista de reservas recientes
  - Vista rápida de disponibilidad

#### 📅 Gestión de Reservas
- **URL**: `/{tenant}/panel/bookings`
- **Funcionalidades**:
  - ✅ Listado completo con búsqueda y filtros
  - ✅ Crear nuevas reservas con modal intuitivo
  - ✅ Editar reservas existentes
  - ✅ Cancelar reservas con motivo
  - ✅ Marcar como "No Show"
  - ✅ Asignación automática de recursos
  - ✅ Historial completo de cambios
  - ✅ Integración con CapacityService para horarios disponibles

#### 🛠️ Gestión de Servicios
- **URL**: `/{tenant}/panel/services`
- **Funcionalidades**:
  - ✅ CRUD completo de servicios
  - ✅ Nombres traducibles (ES/EN/NL)
  - ✅ Configuración de duración y buffers
  - ✅ Tipos de recursos requeridos
  - ✅ Precios configurables
  - ✅ Activación/desactivación
  - ✅ Validación de integridad (no eliminar servicios con reservas)

#### 🏢 Gestión de Recursos
- **URL**: `/{tenant}/panel/resources`
- **Funcionalidades**:
  - ✅ CRUD completo de recursos
  - ✅ Tipos: TABLE, STAFF, ROOM, EQUIPMENT
  - ✅ Etiquetas traducibles
  - ✅ Capacidad configurable
  - ✅ Combinaciones de recursos
  - ✅ Filtros por tipo y ubicación
  - ✅ Activación/desactivación

#### 👥 Gestión de Clientes
- **URL**: `/{tenant}/panel/customers`
- **Funcionalidades**:
  - ✅ CRUD completo de clientes
  - ✅ Búsqueda por nombre, email, teléfono
  - ✅ Historial completo de reservas por cliente
  - ✅ Gestión de consentimiento GDPR
  - ✅ Notas y información adicional
  - ✅ Vista detallada con estadísticas

### 🔄 En Desarrollo (Vistas Placeholder)

#### ⏰ Disponibilidad
- **URL**: `/{tenant}/panel/availability`
- **Estado**: Vista placeholder implementada
- **Próximas funcionalidades**:
  - Reglas semanales de horarios
  - Excepciones y cierres temporales
  - Capacidad por slot de tiempo
  - Vista compacta tipo "horario semanal"

#### 🔗 Webhooks
- **URL**: `/{tenant}/panel/webhooks`
- **Estado**: Vista placeholder implementada
- **Próximas funcionalidades**:
  - CRUD de endpoints de webhook
  - Eventos soportados (booking.created, booking.cancelled, etc.)
  - Logs de webhook con reintentos
  - Botón de prueba de webhooks
  - Autenticación HMAC

#### 🔑 Tokens API
- **URL**: `/{tenant}/panel/tokens`
- **Estado**: Vista placeholder implementada
- **Próximas funcionalidades**:
  - Gestión de tokens Sanctum
  - Scopes configurables
  - Copia al portapapeles
  - Revocación de tokens
  - Ejemplos de integración cURL

#### ⚙️ Configuración
- **URL**: `/{tenant}/panel/settings`
- **Estado**: Vista parcial implementada
- **Funcionalidades actuales**:
  - ✅ Selector de idioma funcional
  - ✅ Vista de perfil de usuario
  - ✅ Información de localización actual
- **Próximas funcionalidades**:
  - Configuración de tenant (marca, timezone, etc.)
  - Gestión de usuarios y roles
  - Configuración de email y notificaciones

#### 🎯 Onboarding
- **URL**: `/{tenant}/panel/onboarding`
- **Estado**: Vista placeholder implementada
- **Próximas funcionalidades**:
  - Asistente paso a paso para nuevos tenants
  - Creación de servicios principales
  - Configuración de recursos mínimos
  - Definición de horarios base

## 🔐 Sistema de Autenticación y Roles

### Roles Implementados

| Rol | Descripción | Permisos |
|-----|-------------|----------|
| **Owner** | Propietario del tenant | Acceso completo a todas las funcionalidades |
| **Manager** | Gerente | Acceso a gestión operativa (sin configuración crítica) |
| **Staff** | Personal | Acceso limitado (solo ver y gestionar reservas) |

### Políticas de Autorización

- ✅ **TenantPolicy**: Base para todas las políticas
- ✅ **BookingPolicy**: Gestión de reservas por roles
- ✅ **ServicePolicy**: Gestión de servicios por roles
- ✅ **ResourcePolicy**: Gestión de recursos por roles
- ✅ **CustomerPolicy**: Gestión de clientes por roles
- ✅ **WebhookEndpointPolicy**: Gestión de webhooks (solo admin/owner)

## 🌐 Internacionalización

### Idiomas Soportados
- ✅ **Español (ES)** - Idioma por defecto
- ✅ **Inglés (EN)** - Completo
- ✅ **Holandés (NL)** - Completo

### Funcionalidades i18n
- ✅ Selector de idioma en el header del panel
- ✅ Todas las vistas del panel traducidas
- ✅ Mensajes de validación y errores
- ✅ Formularios y etiquetas
- ✅ Estados y estados de reserva

## 🔧 Tecnologías Utilizadas

### Backend
- **Laravel 11.x** - Framework PHP
- **Stancl/Tenancy** - Multitenancy
- **Laravel Sanctum** - Autenticación API
- **Spatie/Laravel-Permission** - Roles y permisos
- **Spatie/Laravel-Translatable** - Traducciones de modelos

### Frontend
- **Blade Templates** - Motor de plantillas
- **Tailwind CSS** - Framework CSS
- **Alpine.js** - JavaScript reactivo
- **Vite** - Build tool

### Base de Datos
- **MySQL** - Base de datos principal
- **Redis** - Cache y sesiones

## 📡 API REST

### Endpoints Principales

#### Disponibilidad
```bash
GET /{tenant}/api/v1/availability
# Parámetros: service_id, date, party_size
```

#### Crear Reserva
```bash
POST /{tenant}/api/v1/bookings
# Headers: Authorization: Bearer {token}
# Body: service_id, customer_email, customer_name, start_at, party_size
```

#### Cancelar Reserva
```bash
POST /{tenant}/api/v1/bookings/{id}/cancel
# Headers: Authorization: Bearer {token}
```

### Autenticación API
- **Sanctum Tokens** - Para aplicaciones externas
- **Scopes** - Permisos granulares por token
- **Rate Limiting** - 60 requests/min por usuario

## 🔗 Webhooks

### Eventos Soportados
- `booking.created` - Nueva reserva creada
- `booking.updated` - Reserva modificada
- `booking.cancelled` - Reserva cancelada
- `booking.no_show` - Cliente no se presentó

### Autenticación
- **HMAC Signature** - Verificación de integridad
- **Retry Logic** - Reintentos automáticos en caso de fallo
- **Logs Detallados** - Historial completo de entregas

## 🎨 Widget Integrable

### Implementación
```html
<div data-tenant="ranch" 
     data-service="haircut" 
     data-locale="es">
</div>
<script src="https://ranch.book.aimadarek.com/widget.js"></script>
```

### Características
- ✅ Responsive design
- ✅ Múltiples idiomas
- ✅ Validación en tiempo real
- ✅ Integración con API
- ✅ Notificaciones de confirmación

## 🚀 Comandos de Deployment

```bash
# Compilar assets frontend
npm run build

# Aplicar migraciones
php artisan migrate --force

# Optimizar para producción
php artisan optimize

# Reiniciar colas
php artisan queue:restart

# Limpiar caches
php artisan route:clear
php artisan config:clear
php artisan view:clear
```

## 📊 Estado del Proyecto

### ✅ Completado (85%)
- Sistema de autenticación y roles
- Panel de administración completo
- CRUD de reservas, servicios, recursos y clientes
- Sistema de multitenancy funcional
- Internacionalización completa
- API REST básica
- Widget integrable
- Webhooks con HMAC

### 🔄 En Progreso (15%)
- CRUD de disponibilidad
- Gestión completa de webhooks
- Gestión de tokens API
- Panel de configuración avanzada
- Flujo de onboarding
- Optimizaciones de rendimiento

## 🎯 Próximos Pasos

1. **Completar CRUD de Disponibilidad** - Reglas semanales y excepciones
2. **Implementar Gestión de Webhooks** - Logs, pruebas, configuración
3. **Gestión de Tokens API** - Sanctum completo con scopes
4. **Panel de Configuración** - Settings avanzados del tenant
5. **Onboarding Flow** - Asistente para nuevos tenants
6. **Optimizaciones** - Rate limiting, caching, métricas

## 📞 Soporte

Para soporte técnico o consultas sobre el sistema:
- **Email**: soporte@aimadarek.com
- **Documentación**: [docs.aimadarek.com](https://docs.aimadarek.com)
- **Issues**: GitHub Issues del repositorio

---

**Última actualización**: Enero 2025  
**Versión**: 1.0.0-beta  
**Estado**: Producción Ready (85% completo)