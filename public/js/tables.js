// Función para obtener todos los parámetros actuales de la URL
function getCurrentUrlParams() {
    const urlParams = new URLSearchParams(window.location.search);
    const params = {};
    for (const [key, value] of urlParams) {
        params[key] = value;
    }
    return params;
}

// Función para construir URL con parámetros preservados
function buildUrlWithParams(newParams = {}) {
    const currentParams = getCurrentUrlParams();
    const mergedParams = { ...currentParams, ...newParams };
    
    // Limpiar parámetros vacíos
    Object.keys(mergedParams).forEach(key => {
        if (mergedParams[key] === '' || mergedParams[key] === null || mergedParams[key] === undefined) {
            delete mergedParams[key];
        }
    });
    
    const queryString = new URLSearchParams(mergedParams).toString();
    return window.location.pathname + (queryString ? '?' + queryString : '');
}

const maxEntriesSelect = document.querySelector('.select-entries');

// Solo agregar el listener si el elemento existe y no tiene un formulario padre
if (maxEntriesSelect && !maxEntriesSelect.closest('form')) {
    maxEntriesSelect.addEventListener('change', function(){
        const selected = this.value;
        // Mantener todos los parámetros actuales, solo cambiar 'showing' y resetear 'page'
        window.location.href = buildUrlWithParams({ 
            showing: selected, 
            page: 1 // Resetear a página 1 cuando cambia el número de entradas
        });
    });
}

const anteriorPag = document.querySelector('.anterior-pag');
const siguientePag = document.querySelector('.siguiente-pag');

if (anteriorPag){
    anteriorPag.addEventListener('click', function(){
        const pageNumber = this.id;
        
        // Mantener todos los parámetros actuales, solo cambiar 'page'
        window.location.href = buildUrlWithParams({ 
            page: pageNumber 
        });
    });
}

if (siguientePag){
    siguientePag.addEventListener('click', function(){
        const pageNumber = this.id;
        
        // Mantener todos los parámetros actuales, solo cambiar 'page'
        window.location.href = buildUrlWithParams({ 
            page: pageNumber 
        });
    });
}

// Función para descargar con filtros aplicados
function downloadExport(format) {
    const currentParams = getCurrentUrlParams();
    
    // Crear parámetros para la descarga
    const downloadParams = { ...currentParams };
    downloadParams.export = format;
    
    // Construir URL de descarga
    const queryString = new URLSearchParams(downloadParams).toString();
    const downloadUrl = window.location.pathname + '/export?' + queryString;
    
    // Crear elemento temporal para descarga
    const link = document.createElement('a');
    link.href = downloadUrl;
    link.download = '';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Cerrar dropdown de descarga al hacer click fuera (fallback por si Alpine.js no funciona)
document.addEventListener('click', function(event) {
    const downloadButton = document.getElementById('download-button');
    const downloadMenu = document.getElementById('download-menu');
    
    if (downloadButton && downloadMenu) {
        if (!downloadButton.contains(event.target) && !downloadMenu.contains(event.target)) {
            downloadMenu.style.display = 'none';
        }
    }
});

