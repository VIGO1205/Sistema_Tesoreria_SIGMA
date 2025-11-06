// ==========================================
// BÚSQUEDA UNIFICADA PARA PAGOS
// Busca por código de estudiante o código de orden
// ==========================================

document.addEventListener('DOMContentLoaded', function() {
    const btnBuscarUnificado = document.getElementById('btnBuscarUnificado');
    const codigoBusquedaInput = document.getElementById('codigo_busqueda_unificado');
    const helperBusqueda = document.getElementById('helper_busqueda');
    const infoEstudianteDiv = document.getElementById('info_estudiante_encontrado');
    const tipoPagoContainer = document.getElementById('tipo_pago_container');
    
    // Variables para almacenar los datos encontrados
    let datosEncontrados = null;
    let tipoBusqueda = null; // 'estudiante' o 'orden'
    
    // Búsqueda al hacer clic
    if (btnBuscarUnificado) {
        btnBuscarUnificado.addEventListener('click', realizarBusqueda);
    }
    
    // Búsqueda al presionar Enter
    if (codigoBusquedaInput) {
        codigoBusquedaInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                realizarBusqueda();
            }
        });
    }
    
    async function realizarBusqueda() {
        const codigo = codigoBusquedaInput?.value.trim();
        
        if (!codigo) {
            mostrarError('Por favor, ingrese un código para buscar');
            return;
        }
        
        // Determinar tipo de búsqueda por el formato del código
        // Si empieza con "OP-" es orden, si es numérico de 6 dígitos es estudiante
        const esOrden = codigo.toUpperCase().startsWith('OP-');
        const esEstudiante = /^\d{6}$/.test(codigo); // Solo 6 dígitos
        
        if (!esOrden && !esEstudiante) {
            mostrarError('Formato de código no válido. Use 6 dígitos para estudiante o formato OP-YYYY-#### para orden');
            return;
        }
        
        tipoBusqueda = esOrden ? 'orden' : 'estudiante';
        
        // Mostrar loading
        btnBuscarUnificado.disabled = true;
        btnBuscarUnificado.innerHTML = `
            <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Buscando...
        `;
        
        try {
            if (tipoBusqueda === 'orden') {
                await buscarPorOrden(codigo);
            } else {
                await buscarPorEstudiante(codigo);
            }
        } catch (error) {
            console.error('Error en búsqueda:', error);
            mostrarError(error.message);
        } finally {
            restaurarBoton();
        }
    }
    
    async function buscarPorOrden(codigoOrden) {
        const baseUrl = window.Laravel?.routes?.buscarOrden || '/financiera/pagos/buscar-orden/';
        const url = baseUrl + encodeURIComponent(codigoOrden);
        
        const response = await fetch(url);
        
        if (!response.ok) {
            const errorData = await response.json().catch(() => ({}));
            throw new Error(errorData.message || 'Orden no encontrada');
        }
        
        const data = await response.json();
        
        if (data.success) {
            datosEncontrados = {
                tipo: 'orden',
                datos: data.orden
            };
            mostrarInformacionOrden(data.orden);
        } else {
            throw new Error(data.message || 'No se pudo encontrar la orden');
        }
    }
    
    async function buscarPorEstudiante(codigoEstudiante) {
        // Usar la ruta existente de buscar alumno
        const url = `/pagos/buscarAlumno/${encodeURIComponent(codigoEstudiante)}`;
        
        console.log('Buscando estudiante:', codigoEstudiante, 'en URL:', url);
        
        const response = await fetch(url);
        const data = await response.json(); // Siempre parsear el JSON primero
        
        console.log('Respuesta completa:', response.ok, data);
        
        if (!response.ok) {
            console.error('Error en respuesta:', data);
            
            // Si tiene sin_orden, mostrar mensaje especial
            if (data.sin_orden && data.alumno) {
                datosEncontrados = {
                    tipo: 'estudiante',
                    datos: data.alumno,
                    sin_orden: true
                };
                mostrarInformacionEstudiante(data.alumno, true);
                return;
            }
            
            throw new Error(data.message || 'Estudiante no encontrado');
        }
        
        console.log('Respuesta exitosa:', data);
        
        if (data && data.success && data.alumno) {
            datosEncontrados = {
                tipo: 'estudiante',
                datos: data.alumno
            };
            mostrarInformacionEstudiante(data.alumno);
        } else {
            throw new Error('No se pudo obtener la información del estudiante');
        }
    }
    
    function mostrarInformacionOrden(orden) {
        // Ya no mostramos el contenedor de información encontrada
        // La información se muestra directamente en cada sección
        mostrarOpcionesTipoPago(true); // SIEMPRE mostrar ambas opciones
        
        helperBusqueda.innerHTML = '✅ Orden encontrada. Seleccione el tipo de pago a continuación.';
        helperBusqueda.className = 'text-xs mt-1 text-green-600 dark:text-green-400 font-medium';
    }
    
    function mostrarInformacionEstudiante(alumno, sinOrden = false) {
        // Ya no mostramos el contenedor de información encontrada
        // La información se muestra directamente en cada sección
        
        if (sinOrden) {
            // No mostrar opciones de pago si no hay orden
            tipoPagoContainer.classList.add('hidden');
            helperBusqueda.innerHTML = '⚠️ Alumno encontrado pero sin orden de pago pendiente. Genere una orden primero.';
            helperBusqueda.className = 'text-xs mt-1 text-yellow-600 dark:text-yellow-400 font-medium';
        } else {
            // SIEMPRE mostrar AMBAS opciones, sin importar qué se buscó
            mostrarOpcionesTipoPago(true);
            helperBusqueda.innerHTML = '✅ Estudiante encontrado. Seleccione el tipo de pago a continuación.';
            helperBusqueda.className = 'text-xs mt-1 text-green-600 dark:text-green-400 font-medium';
        }
    }
    
    function mostrarOpcionesTipoPago(mostrarAmbas) {
        if (!tipoPagoContainer) return;
        
        // SIEMPRE mostrar el contenedor
        tipoPagoContainer.classList.remove('hidden');
        
        const labelOrdenCompleta = document.getElementById('label_orden_completa');
        
        // SIEMPRE mostrar ambas opciones (parámetro ignorado ahora)
        if (labelOrdenCompleta) {
            labelOrdenCompleta.classList.remove('hidden');
        }
        
        // NO auto-seleccionar nada, el usuario debe elegir manualmente
    }
    
    function mostrarError(mensaje) {
        if (helperBusqueda) {
            helperBusqueda.innerHTML = `❌ ${mensaje}`;
            helperBusqueda.className = 'text-xs mt-1 text-red-600 dark:text-red-400 font-medium';
        }
        
        // Ocultar tipo de pago si estaba visible
        if (tipoPagoContainer) {
            tipoPagoContainer.classList.add('hidden');
        }
    }
    
    function restaurarBoton() {
        if (btnBuscarUnificado) {
            btnBuscarUnificado.disabled = false;
            btnBuscarUnificado.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                Buscar
            `;
        }
    }
    
    // Exponer datos para que otros scripts los usen
    window.datosBusquedaUnificada = {
        getDatos: () => datosEncontrados,
        getTipo: () => tipoBusqueda
    };
});
