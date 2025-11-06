# 📊 Sistema de Exportación - Guía de Uso

## 🎯 Descripción General

El sistema de exportación permite descargar los datos de las tablas en formatos Excel (.xlsx) y PDF, manteniendo todos los filtros y búsquedas aplicadas por el usuario.

## 🔧 Características Principales

### ✅ Formatos Disponibles
- **Excel (.xlsx)**: Archivo nativo de Excel con formato profesional
- **PDF**: Documento PDF con diseño corporativo

### ✅ Funcionalidades
- **Preservación de filtros**: Mantiene todos los filtros aplicados
- **Búsqueda incluida**: Respeta los términos de búsqueda activos
- **Sin límite de paginación**: Exporta todos los registros que coinciden
- **Nombres únicos**: Archivos con timestamp para evitar conflictos

## 🎨 Interfaz de Usuario

### Botón de Descarga
- **Ubicación**: Esquina superior derecha de las tablas
- **Activación**: Click para mostrar dropdown con opciones
- **Compatibilidad**: Dark mode y tema del sitio
- **Animaciones**: Transiciones suaves con Alpine.js

### Opciones del Dropdown
1. **Descargar Excel**: Genera archivo .xlsx
2. **Descargar PDF**: Genera archivo PDF

## 🔐 Permisos

El sistema respeta los permisos configurados:
```php
@can('manage-resource', [$resource, 'download'])
    // Botón de descarga visible
@endcan
```

## 📋 Formato de Archivos

### Excel (.xlsx)
- **Encabezados**: Fondo azul (#4F46E5), texto blanco, centrado
- **Datos**: Autoajuste de columnas, bordes en toda la tabla
- **Metadatos**: Información del sistema en propiedades del archivo
- **Estilos**: Filas alternadas para mejor legibilidad

### PDF
- **Encabezado**: Logo del sistema SIGMA
- **Información**: Fecha de generación y total de registros
- **Tabla**: Estilos CSS con colores alternos
- **Footer**: Información del sistema
- **Tamaño**: A4 vertical

## 🚀 Uso Técnico

### Para Desarrolladores

#### Estructura del Controlador
```php
// Método principal
public function export(Request $request)

// Método para Excel
private function exportExcel($datos)

// Método para PDF  
private function exportPdf($datos)

// Generador de HTML para PDF
private function generatePdfHtml($datos)
```

#### Ruta de Exportación
```php
Route::get('/export', [Controller::class, 'export'])
    ->name('export')
    ->middleware(['can:manage-resource,"modulo","download"']);
```

#### JavaScript
```javascript
// Función de descarga
function downloadExport(format) {
    // Construye URL con filtros actuales
    // Inicia descarga automática
}
```

## 🛠️ Reutilización del Sistema

Para implementar en otras tablas:

### 1. Copiar Botón de Descarga
```blade
@can('manage-resource', [$resource, 'download'])
    <!-- Copiar estructura del dropdown -->
@endcan
```

### 2. Agregar Métodos al Controlador
```php
// Copiar métodos export, exportExcel, exportPdf
// Adaptar nombres de columnas y mapeos
```

### 3. Configurar Rutas
```php
Route::get('/export', [Controller::class, 'export'])
    ->name('export');
```

### 4. Incluir JavaScript
```javascript
// Asegurar que tables.js esté incluido
// La función downloadExport() es reutilizable
```

## 📊 Ejemplo de Implementación

Para el módulo "Niveles Educativos":

1. **Filtros aplicados**: Nivel = "Primaria"
2. **Búsqueda**: "educación"
3. **Resultado**: Excel/PDF solo con registros que contengan "educación" en nivel "Primaria"

## 🔍 Troubleshooting

### Problemas Comunes

#### Error 403 - Sin Permisos
- **Causa**: Usuario sin permisos de descarga
- **Solución**: Verificar rol y permisos en el sistema

#### Archivo Vacío
- **Causa**: Filtros muy restrictivos
- **Solución**: Revisar filtros aplicados y criterios de búsqueda

#### Error de Memoria
- **Causa**: Demasiados registros para procesar
- **Solución**: Aplicar filtros para reducir el dataset

### Logs y Debugging
- **Ubicación**: `storage/logs/laravel.log`
- **Buscar**: Errores relacionados con "export" o "download"

## 💡 Consejos de Uso

1. **Aplica filtros antes**: Reduce el tiempo de generación
2. **Usa búsquedas específicas**: Mejora la relevancia de los datos
3. **Verifica permisos**: Asegúrate de tener acceso de descarga
4. **Espera la descarga**: Los archivos grandes pueden tomar tiempo

## 🎨 Personalización

### Estilos Excel
- Modificar colores en `exportExcel()` método
- Cambiar fuentes y tamaños de texto
- Ajustar anchos de columnas

### Diseño PDF
- Editar CSS en `generatePdfHtml()` método
- Cambiar colores corporativos
- Modificar estructura del layout

El sistema está diseñado para ser **fácil de usar**, **reutilizable** y **altamente personalizable**.
