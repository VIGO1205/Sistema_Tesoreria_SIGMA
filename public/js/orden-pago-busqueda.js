
let searchTimeout = null;
let alumnoSeleccionado = null;
let ultimosResultados = []; // Almacenar los últimos resultados de búsqueda

document.addEventListener('DOMContentLoaded', function() {
    const inputBuscar = document.getElementById('buscar_alumno');
    const resultadosContainer = document.getElementById('resultados_busqueda');
    const loadingIndicator = document.getElementById('loading_busqueda');
    const errorBuscar = document.getElementById('error_buscar_alumno');
    const btnLimpiarBusqueda = document.getElementById('btnLimpiarBusqueda');
    
    const filtroNivel = document.getElementById('filtro_nivel');
    const filtroGrado = document.getElementById('filtro_grado');
    const filtroSeccion = document.getElementById('filtro_seccion');

    // Botón principal de filtros
    const btnActivarFiltros = document.getElementById('btnActivarFiltros');
    const contenedorFiltros = document.getElementById('contenedorFiltros');
    
    // Botones individuales de filtros
    const btnActivarNivel = document.getElementById('btnActivarNivel');
    const btnActivarGrado = document.getElementById('btnActivarGrado');
    const btnActivarSeccion = document.getElementById('btnActivarSeccion');
    const btnBuscarConFiltros = document.getElementById('btnBuscarConFiltros');
    
    const contenedorFiltroNivel = document.getElementById('contenedorFiltroNivel');
    const contenedorFiltroGrado = document.getElementById('contenedorFiltroGrado');
    const contenedorFiltroSeccion = document.getElementById('contenedorFiltroSeccion');

    // ===== NUEVA LÓGICA: Filtrar opciones de grado según nivel seleccionado =====
    filtroNivel.addEventListener('change', function() {
        const nivelSeleccionado = this.value;
        const todasLasOpciones = filtroGrado.querySelectorAll('option');
        const valorActualGrado = filtroGrado.value;
        let opcionActualEsValida = false;

        todasLasOpciones.forEach(option => {
            if (option.value === '') {
                // "Todos los grados" siempre visible
                option.style.display = '';
                return;
            }

            const nivelesPermitidos = option.getAttribute('data-nivel');
            
            if (!nivelSeleccionado || nivelSeleccionado === '') {
                // Si nivel es "Todos", mostrar todos los grados
                option.style.display = '';
            } else {
                // Mostrar solo grados compatibles con el nivel seleccionado
                if (nivelesPermitidos && nivelesPermitidos.includes(nivelSeleccionado)) {
                    option.style.display = '';
                    if (option.value === valorActualGrado) {
                        opcionActualEsValida = true;
                    }
                } else {
                    option.style.display = 'none';
                }
            }
        });

        // Si la opción actual de grado ya no es válida, resetear a "Todos"
        if (!opcionActualEsValida && valorActualGrado !== '') {
            filtroGrado.value = '';
        }
    });

    // Activar filtro de Nivel Educativo
    btnActivarNivel.addEventListener('click', function() {
        const estaActivo = !contenedorFiltroNivel.classList.contains('hidden');
        if (estaActivo) {
            contenedorFiltroNivel.classList.add('hidden');
            btnActivarNivel.classList.remove('border-green-500', 'bg-green-100', 'dark:bg-green-900/30');
            filtroNivel.value = '';
            // Resetear grados cuando se desactiva nivel
            filtroNivel.dispatchEvent(new Event('change'));
        } else {
            contenedorFiltroNivel.classList.remove('hidden');
            contenedorFiltroNivel.classList.add('flex');
            btnActivarNivel.classList.add('border-green-500', 'bg-green-100', 'dark:bg-green-900/30');
        }
    });
    
    // Botón principal: Mostrar/Ocultar panel de filtros
    btnActivarFiltros.addEventListener('click', function() {
        contenedorFiltros.classList.toggle('hidden');
    });

    // Activar filtro de Grado
    btnActivarGrado.addEventListener('click', function() {
        const estaActivo = !contenedorFiltroGrado.classList.contains('hidden');
        if (estaActivo) {
            contenedorFiltroGrado.classList.add('hidden');
            btnActivarGrado.classList.remove('border-indigo-500', 'bg-indigo-100', 'dark:bg-indigo-900/30');
            filtroGrado.value = '';
        } else {
            contenedorFiltroGrado.classList.remove('hidden');
            contenedorFiltroGrado.classList.add('flex');
            btnActivarGrado.classList.add('border-indigo-500', 'bg-indigo-100', 'dark:bg-indigo-900/30');
        }
    });

    // Activar filtro de Sección
    btnActivarSeccion.addEventListener('click', function() {
        const estaActivo = !contenedorFiltroSeccion.classList.contains('hidden');
        if (estaActivo) {
            contenedorFiltroSeccion.classList.add('hidden');
            btnActivarSeccion.classList.remove('border-purple-500', 'bg-purple-100', 'dark:bg-purple-900/30');
            filtroSeccion.value = '';
        } else {
            contenedorFiltroSeccion.classList.remove('hidden');
            contenedorFiltroSeccion.classList.add('flex');
            btnActivarSeccion.classList.add('border-purple-500', 'bg-purple-100', 'dark:bg-purple-900/30');
        }
    });

    // Botón para buscar con filtros (sin necesidad de escribir)
    btnBuscarConFiltros.addEventListener('click', function() {
        buscarAlumnos(''); // Búsqueda vacía con solo filtros
    });

    // Búsqueda en tiempo real (solo cuando se escribe)
    inputBuscar.addEventListener('input', function() {
        const termino = this.value.trim();
        
        // Limpiar timeout anterior
        if (searchTimeout) {
            clearTimeout(searchTimeout);
        }

        // Ocultar resultados si no hay texto Y no hay filtros activos
        const hayFiltros = filtroNivel.value || filtroGrado.value || filtroSeccion.value;
        if (termino.length < 2 && !hayFiltros) {
            resultadosContainer.classList.add('hidden');
            errorBuscar.classList.add('hidden');
            return;
        }

        // Mostrar indicador de carga
        loadingIndicator.classList.remove('hidden');
        errorBuscar.classList.add('hidden');

        // Esperar 500ms antes de buscar (solo si hay texto)
        if (termino.length >= 2) {
            searchTimeout = setTimeout(() => {
                buscarAlumnos(termino);
            }, 500);
        }
    });

    // Mostrar resultados previos al hacer focus en el input de búsqueda
    inputBuscar.addEventListener('focus', function() {
        const termino = this.value.trim();
        const hayFiltros = filtroNivel.value || filtroGrado.value || filtroSeccion.value;
        
        // Si hay texto o filtros activos, y hay resultados guardados, mostrarlos
        if ((termino.length >= 2 || hayFiltros) && ultimosResultados.length > 0) {
            mostrarResultados(ultimosResultados);
        }
    });

    // Cerrar dropdown al hacer clic fuera
    document.addEventListener('click', function(e) {
        if (!inputBuscar.contains(e.target) && !resultadosContainer.contains(e.target)) {
            resultadosContainer.classList.add('hidden');
        }
    });

    // Limpiar búsqueda
    btnLimpiarBusqueda.addEventListener('click', function() {
        inputBuscar.value = '';
        resultadosContainer.classList.add('hidden');
        errorBuscar.classList.add('hidden');
        alumnoSeleccionado = null;
        ultimosResultados = []; // Limpiar los resultados guardados
        
        // Llamar a la función de limpiar del otro script si existe
        if (typeof limpiarFormulario === 'function') {
            limpiarFormulario();
        }
    });

    /**
     * Buscar alumnos en el servidor
     */
    function buscarAlumnos(termino) {
        const nivel_educativo = filtroNivel.value || '';
        const grado_nombre = filtroGrado.value || ''; // Ahora envía el nombre del grado
        const seccion_nombre = filtroSeccion.value || ''; // Envía el nombre de la sección

        // Validar que haya al menos un criterio de búsqueda
        if (!termino && !nivel_educativo && !grado_nombre && !seccion_nombre) {
            errorBuscar.textContent = 'Debe ingresar un nombre o seleccionar al menos un filtro';
            errorBuscar.classList.remove('hidden');
            return;
        }

        fetch(`/orden-pago/buscar-alumnos-nombre?termino=${encodeURIComponent(termino)}&nivel_educativo=${encodeURIComponent(nivel_educativo)}&grado_id=${encodeURIComponent(grado_nombre)}&seccion_id=${encodeURIComponent(seccion_nombre)}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => {

                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                return response.json();
            })
            .then(data => {

                loadingIndicator.classList.add('hidden');

                if (data.success && data.alumnos && data.alumnos.length > 0) {
                    errorBuscar.classList.add('hidden');
                    mostrarResultados(data.alumnos);
                } else if (data.success && data.alumnos && data.alumnos.length === 0) {
                    mostrarMensajeVacio(termino);
                } else {
                    throw new Error(data.message || 'No se pudieron obtener los resultados');
                }
            })
            .catch(error => {

                loadingIndicator.classList.add('hidden');
                errorBuscar.textContent = `Error: ${error.message}. Verifique su conexión e intente nuevamente.`;
                errorBuscar.classList.remove('hidden');
                resultadosContainer.classList.add('hidden');
            });
    }

    /**
     * Mostrar resultados en el dropdown
     */
    function mostrarResultados(alumnos) {
        resultadosContainer.innerHTML = '';
        ultimosResultados = alumnos; // Guardar los resultados para mostrarlos después
        
        alumnos.forEach(alumno => {
            const item = document.createElement('div');
            item.className = 'px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer border-b border-gray-200 dark:border-gray-600 last:border-b-0 transition-colors';
            item.innerHTML = `
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <div class="font-semibold text-sm text-gray-800 dark:text-white truncate">
                            ${alumno.nombre_completo}
                        </div>
                        <div class="flex items-center gap-3 mt-1 text-xs text-gray-600 dark:text-gray-400">
                            <span class="flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                ${alumno.codigo_educando}
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                </svg>
                                DNI: ${alumno.dni || 'N/A'}
                            </span>
                        </div>
                    </div>
                    <div class="flex-shrink-0 text-right">
                        <div class="inline-flex items-center gap-1 px-2 py-1 bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 rounded text-xs font-medium">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            ${alumno.grado}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Sección: ${alumno.seccion}
                        </div>
                    </div>
                </div>
            `;

            item.addEventListener('click', () => seleccionarAlumno(alumno));
            resultadosContainer.appendChild(item);
        });

        resultadosContainer.classList.remove('hidden');
    }

    /**
     * Mostrar mensaje cuando no hay resultados
     */
    function mostrarMensajeVacio(termino) {
        resultadosContainer.innerHTML = `
            <div class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <p class="font-medium">No se encontraron estudiantes</p>
                <p class="text-sm mt-1">No hay resultados para "${termino}"</p>
            </div>
        `;
        resultadosContainer.classList.remove('hidden');
    }

    /**
     * Seleccionar un alumno del dropdown
     */
    function seleccionarAlumno(alumno) {
        alumnoSeleccionado = alumno;
        inputBuscar.value = alumno.nombre_completo;
        resultadosContainer.classList.add('hidden');
        
        // Poner el código en el campo oculto
        document.getElementById('codigo_alumno').value = alumno.codigo_educando;
        
        // Llamar a la función de búsqueda original del otro script que llena los datos en los inputs
        if (typeof window.buscarAlumnoPorCodigo === 'function') {
            window.buscarAlumnoPorCodigo(alumno.codigo_educando);
        } else if (typeof buscarAlumnoPorCodigo === 'function') {
            buscarAlumnoPorCodigo(alumno.codigo_educando);
        } else {
            // Si no existe la función, simular clic en el botón de búsqueda
            const btnBuscar = document.getElementById('btnBuscarAlumno');
            if (btnBuscar) {
                btnBuscar.click();
            }
        }
    }
});
