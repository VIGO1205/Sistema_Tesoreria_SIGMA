# Documentación: Módulo de Solicitud de Traslado

## Descripción General

Sistema completo para gestionar solicitudes de traslado de alumnos, verificando su estado de deudas y generando documentos PDF oficiales.

## Características Principales

### 1. Búsqueda de Alumnos

- Búsqueda por código de educando
- Validación automática de existencia del alumno
- Visualización de información básica del alumno

### 2. Verificación de Deudas

- Consulta automática de deudas pendientes
- Muestra detalle de:
    - Concepto de la deuda
    - Período
    - Fecha límite
    - Monto total
    - Monto pendiente
- Cálculo de total de deudas pendientes

### 3. Restricciones

- **IMPORTANTE**: Solo los alumnos SIN deudas pendientes pueden generar solicitud de traslado
- Si el alumno tiene deudas, se muestra la información pero NO se permite continuar con el proceso

### 4. Formulario de Solicitud (Solo para alumnos sin deudas)

Campos requeridos (\*):

- Colegio de Destino \*
- Fecha de Traslado \*
- Motivo del Traslado \*

Campos opcionales:

- Dirección del Nuevo Colegio
- Teléfono del Nuevo Colegio
- Observaciones Adicionales

### 5. Generación de PDF

El PDF incluye:

- Código único de solicitud (formato: ST-YYYY-XXXX)
- Información completa del alumno
- Datos del colegio de destino
- Motivo y observaciones
- Firmas para validación
- Marca de agua de seguridad
- Información importante sobre el proceso

## Estructura de Archivos

### Controlador

```
app/Http/Controllers/SolicitudTrasladoController.php
```

### Modelo

```
app/Models/SolicitudTraslado.php
```

### Rutas

```
routes/alumnos/traslados.php
```

### Vistas

```
resources/views/gestiones/solicitud-traslado/
├── index.blade.php    (Formulario principal)
├── pdf.blade.php      (Plantilla PDF)
└── listar.blade.php   (Listado de solicitudes)
```

### Migración

```
database/migrations/2025_11_06_070727_create_solicitudes_traslado_table.php
```

## Rutas Disponibles

| Método | Ruta                    | Nombre           | Descripción        |
| ------ | ----------------------- | ---------------- | ------------------ |
| GET    | /traslados              | traslado_view    | Vista principal    |
| POST   | /traslados/buscar       | traslado_buscar  | Buscar alumno      |
| POST   | /traslados/guardar      | traslado_guardar | Guardar solicitud  |
| GET    | /traslados/pdf/{codigo} | traslado_pdf     | Generar PDF        |
| GET    | /traslados/listar       | traslado_listar  | Listar solicitudes |

## Base de Datos

### Tabla: solicitudes_traslado

| Campo                   | Tipo         | Descripción                             |
| ----------------------- | ------------ | --------------------------------------- |
| id_solicitud            | bigint       | ID primario                             |
| codigo_solicitud        | varchar(50)  | Código único (ST-YYYY-XXXX)             |
| id_alumno               | int unsigned | FK a tabla alumnos                      |
| colegio_destino         | varchar(255) | Nombre del colegio                      |
| motivo_traslado         | text         | Motivo del traslado                     |
| fecha_traslado          | date         | Fecha prevista                          |
| direccion_nuevo_colegio | varchar(255) | Dirección (opcional)                    |
| telefono_nuevo_colegio  | varchar(20)  | Teléfono (opcional)                     |
| observaciones           | text         | Observaciones (opcional)                |
| estado                  | enum         | pendiente/aprobado/rechazado/completado |
| fecha_solicitud         | datetime     | Fecha y hora de creación                |

## Flujo de Trabajo

1. **Acceso al Módulo**

    - Usuario accede desde el sidebar: Gestión de Alumnos > Generar Solicitud Traslado

2. **Búsqueda del Alumno**

    - Ingresa código de educando
    - Sistema busca en la base de datos
    - Muestra información del alumno

3. **Verificación de Deudas**

    - Sistema consulta tabla de deudas
    - Si tiene deudas: Muestra detalle y bloquea el formulario
    - Si no tiene deudas: Muestra formulario de solicitud

4. **Llenado del Formulario** (Solo sin deudas)

    - Usuario completa campos obligatorios y opcionales
    - Valida formulario en frontend

5. **Envío de Solicitud**

    - Sistema verifica nuevamente que no haya deudas
    - Genera código único de solicitud
    - Guarda en base de datos
    - Muestra opción para descargar PDF

6. **Generación de PDF**
    - Crea documento oficial con todos los datos
    - Incluye información del alumno y del traslado
    - Formato profesional con firmas y validaciones

## Validaciones de Seguridad

- Verificación de deudas en frontend y backend
- Validación de existencia del alumno
- Verificación de campos requeridos
- Control de acceso mediante middleware
- Transacciones de base de datos para integridad

## Tecnologías Utilizadas

- Laravel 10.x
- jQuery/AJAX para búsquedas asíncronas
- SweetAlert2 para alertas
- DomPDF para generación de PDFs
- TailwindCSS para estilos
- Bootstrap para componentes

## Mejoras Futuras Sugeridas

1. Sistema de aprobación por niveles
2. Notificaciones por email
3. Historial de cambios de estado
4. Exportación masiva de solicitudes
5. Dashboard de estadísticas
6. Integración con sistema de matriculación
7. Firma digital de solicitudes
8. Tracking del proceso de traslado

## Notas Importantes

- La validación de deudas es CRÍTICA y se realiza en múltiples puntos
- El código de solicitud es único e irrepetible
- El PDF incluye información de trazabilidad
- El estado por defecto de una solicitud es "pendiente"
- Se mantiene registro de fecha y hora de creación

## Mantenimiento

### Agregar nuevos campos:

1. Actualizar migración
2. Agregar campo en modelo
3. Actualizar validación en controlador
4. Agregar campo en formulario (index.blade.php)
5. Actualizar PDF (pdf.blade.php)

### Modificar estados:

1. Actualizar enum en migración
2. Actualizar colores de badges en listar.blade.php
3. Agregar lógica de transición en controlador

## Contacto y Soporte

Para dudas o sugerencias sobre este módulo, contactar al equipo de desarrollo.

---

**Versión:** 1.0.0  
**Fecha:** Noviembre 2025  
**Estado:** Producción
