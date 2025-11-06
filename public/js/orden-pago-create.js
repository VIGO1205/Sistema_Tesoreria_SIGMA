document.addEventListener('DOMContentLoaded', function() {
    const btnBuscarAlumno = document.getElementById('btnBuscarAlumno');
    const btnLimpiarBusqueda = document.getElementById('btnLimpiarBusqueda');
    const btnGenerarOrden = document.getElementById('btnGenerarOrden');
    const codigoAlumnoInput = document.getElementById('codigo_alumno');
    const errorCodigo = document.getElementById('error_codigo_alumno');
    
    const contenedorDeuda = document.getElementById('contenedorDeuda');
    const contenedorMeses = document.getElementById('contenedorMeses');
    const contenedorFecha = document.getElementById('contenedorFecha');
    const contenedorResumen = document.getElementById('contenedorResumen');

    const formOrdenPago = document.getElementById('formOrdenPago');
    let alumnoData = null;
    let montoMensualActual = 0;
    let alumnoTieneDeudasAnteriores = false; // Variable para guardar el estado del backend
    let mesActualAplicaMora = false; // Indica si el mes actual tiene mora
    let mesActualPorcentajeMora = 0; // Porcentaje de mora del mes actual
    let mesActualDescripcionPolitica = ''; // Descripción de la política de mora

    const urlParams = new URLSearchParams(window.location.search);
    const codigoParam = urlParams.get('codigo');
    if (codigoParam && codigoAlumnoInput) {
        codigoAlumnoInput.value = codigoParam;
        setTimeout(() => {
            btnBuscarAlumno.click();
        }, 500);
    }

    function limpiarFormulario() {
        codigoAlumnoInput.value = '';
        alumnoTieneDeudasAnteriores = false; // Resetear el estado
        mesActualAplicaMora = false; // Resetear mora del mes actual
        mesActualPorcentajeMora = 0; // Resetear porcentaje
        mesActualDescripcionPolitica = ''; // Resetear descripción de la política
        
        if (errorCodigo) {
            errorCodigo.textContent = '';
            errorCodigo.classList.add('hidden');
        }
        if (codigoAlumnoInput) {
            codigoAlumnoInput.classList.remove('border-red-500');
        }

        const mensajes = [
            'mensajeDeudaAnterior',
            'mensajeDeudaBloqueante',
            'mensajeOrdenReciente',
            'mensajeSinDeudas',
            'mensajeAlDia',
            'mensajeDeudasMultiples',
            'error_servidor',
            'error_deudas'
        ];
        mensajes.forEach(id => {
            const elemento = document.getElementById(id);
            if (elemento) {
                elemento.remove();
            }
        });
        
        if (contenedorDeuda) contenedorDeuda.classList.add('hidden');
        if (contenedorMeses) contenedorMeses.classList.add('hidden');
        if (contenedorFecha) contenedorFecha.classList.add('hidden');
        if (contenedorResumen) contenedorResumen.classList.add('hidden');
        
        const errorDeudas = document.getElementById('error_deudas');
        if (errorDeudas) {
            errorDeudas.classList.add('hidden');
            const errorTexto = document.getElementById('error_deudas_texto');
            if (errorTexto) errorTexto.textContent = '';
        }

        const campos = ['id_alumno', 'id_matricula', 'nombre_completo', 'dni_alumno', 'nivel_educativo', 'grado', 'seccion', 'escala', 'monto_mensual'];
        campos.forEach(campo => {
            const elemento = document.getElementById(campo);
            if (elemento) {
                elemento.value = '';
                if (elemento.hasAttribute('readonly')) {
                    if (campo === 'nombre_completo') elemento.placeholder = 'Busque un alumno primero';
                    else elemento.placeholder = '-';
                }
            }
        });
        
        // Resetear el campo de deudas anteriores
        document.getElementById('tiene_deudas_anteriores').value = '0';
        
        const listaDeudas = document.getElementById('listaDeudasCheckbox');
        if (listaDeudas) listaDeudas.innerHTML = '';
        
        const inputAdelantar = document.getElementById('meses_adelantar');
        if (inputAdelantar) {
            inputAdelantar.value = '0';
            inputAdelantar.max = '12';
        }
        
        const preguntaAdelantar = document.getElementById('preguntaAdelantar');
        const inputMesesAdelantar = document.getElementById('inputMesesAdelantar');
        if (preguntaAdelantar) preguntaAdelantar.classList.remove('hidden');
        if (inputMesesAdelantar) inputMesesAdelantar.classList.add('hidden');
        
        const resumenMeses = document.getElementById('resumen_meses');
        const resumenBase = document.getElementById('resumen_base');
        const resumenDescuento = document.getElementById('resumen_descuento');
        const resumenMora = document.getElementById('resumen_mora');
        const resumenTotal = document.getElementById('resumen_total');
        
        if (resumenMeses) resumenMeses.textContent = '1 mes';
        if (resumenBase) resumenBase.textContent = 'S/ 0.00';
        if (resumenDescuento) resumenDescuento.textContent = '- S/ 0.00';
        if (resumenMora) resumenMora.textContent = '+ S/ 0.00';
        if (resumenTotal) resumenTotal.textContent = 'S/ 0.00';
        
        // Ocultar el contenedor de descripción de política
        const contenedorDetalle = document.getElementById('contenedor_detalle_politica');
        if (contenedorDetalle) contenedorDetalle.classList.add('hidden');
        
        if (btnGenerarOrden) {
            btnGenerarOrden.disabled = true;
            btnGenerarOrden.classList.add('opacity-50', 'cursor-not-allowed');
        }
        
        alumnoData = null;
        montoMensualActual = 0;
        alumnoTieneDeudasAnteriores = false;
        mesActualAplicaMora = false;
        mesActualPorcentajeMora = 0;
        mesActualDescripcionPolitica = '';
        
        if (codigoAlumnoInput) {
            codigoAlumnoInput.focus();
        }        
    }

    if (btnLimpiarBusqueda) {
        btnLimpiarBusqueda.addEventListener('click', function(e) {
            e.preventDefault();
            limpiarFormulario();
        });
    }

    btnBuscarAlumno.addEventListener('click', function() {
        const codigo = codigoAlumnoInput.value.trim();
        
        console.log('Buscando alumno con código:', codigo);
        
        errorCodigo.textContent = '';
        errorCodigo.classList.add('hidden');
        codigoAlumnoInput.classList.remove('border-red-500');
        
        const mensajeAnterior = document.getElementById('mensajeDeudaAnterior');
        if (mensajeAnterior) mensajeAnterior.remove();
        
        const mensajeBloqueante = document.getElementById('mensajeDeudaBloqueante');
        if (mensajeBloqueante) mensajeBloqueante.remove();
        
        const mensajeOrdenReciente = document.getElementById('mensajeOrdenReciente');
        if (mensajeOrdenReciente) mensajeOrdenReciente.remove();
        
        const mensajeSinDeudas = document.getElementById('mensajeSinDeudas');
        if (mensajeSinDeudas) mensajeSinDeudas.remove();
        
        const mensajeAlDia = document.getElementById('mensajeAlDia');
        if (mensajeAlDia) mensajeAlDia.remove();
        
        if (!codigo) {
            errorCodigo.textContent = 'Por favor ingrese el código del alumno';
            errorCodigo.classList.remove('hidden');
            codigoAlumnoInput.classList.add('border-red-500');
            return;
        }

        const baseUrl = window.location.origin;
        const url = `${baseUrl}/orden-pago/buscarAlumno/${codigo}`;
        console.log('Fetching URL:', url);
        
        fetch(url)
            .then(response => {
                console.log('Response status:', response.status);
                return response.json().then(data => {
                    if (!response.ok) {
                        // Manejo especial para alumno sin deudas
                        if (data.sin_deudas) {
                            throw new Error(data.message || 'El alumno está al día con sus pagos');
                        }
                        throw new Error(data.error || 'Alumno no encontrado');
                    }
                    return data;
                });
            })
            .then(data => {
                console.log('Datos recibidos:', data);
                
                // Verificar si el alumno está completamente al día ANTES de cualquier otro procesamiento
                if (data.al_dia) {
                    console.log('Alumno está al día, mostrando mensaje...');
                    
                    // NO limpiar el formulario para mantener el código del alumno visible
                    
                    // Ocultar secciones de deudas y fechas
                    if (contenedorDeuda) contenedorDeuda.classList.add('hidden');
                    if (contenedorMeses) contenedorMeses.classList.add('hidden');
                    if (contenedorFecha) contenedorFecha.classList.add('hidden');
                    if (contenedorResumen) contenedorResumen.classList.add('hidden');
                    
                    // Deshabilitar el botón de generar
                    if (btnGenerarOrden) {
                        btnGenerarOrden.disabled = true;
                        btnGenerarOrden.classList.add('opacity-50', 'cursor-not-allowed');
                    }
                    
                    // Crear y mostrar mensaje de éxito
                    if (!document.getElementById('mensajeAlDia')) {
                        const mensajeDiv = document.createElement('div');
                        mensajeDiv.id = 'mensajeAlDia';
                        mensajeDiv.className = 'bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg mb-4 col-span-1 sm:col-span-2 lg:col-span-3';
                        mensajeDiv.innerHTML = `
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-medium">${data.message || '¡Felicidades! El estudiante está completamente al día con sus pagos.'}</span>
                            </div>
                        `;
                        const formSection = document.getElementById('formOrdenPago');
                        if (formSection) {
                            formSection.insertBefore(mensajeDiv, formSection.firstChild);
                            console.log('Mensaje insertado en el formulario');
                        } else {
                            console.error('No se encontró el formulario #formOrdenPago');
                        }
                    }
                    
                    // Detener el procesamiento aquí, no continuar con el flujo normal
                    return;
                }
                
                if (!data.success) {
                    throw new Error(data.error || 'Error al buscar alumno');
                }
                
                alumnoData = data;
                
                document.getElementById('id_alumno').value = data.alumno.id_alumno;
                document.getElementById('id_matricula').value = data.matricula.id_matricula;
                document.getElementById('nombre_completo').value = data.alumno.nombre_completo;
                document.getElementById('dni_alumno').value = data.alumno.dni || '';
                
                console.log('Campos llenados con:', {
                    nombre: data.alumno.nombre_completo,
                    dni: data.alumno.dni,
                    grado: data.matricula.grado,
                    seccion: data.matricula.seccion
                });
                
                document.getElementById('nivel_educativo').value = data.matricula.nivel_educativo || '';
                document.getElementById('grado').value = data.matricula.grado || '';
                document.getElementById('seccion').value = data.matricula.seccion || '';
                
                const escala = data.matricula.escala || 'E';
                document.getElementById('escala').value = escala;
                
                // Calcular monto mensual según la escala
                const montosEscala = {
                    'A': 500.00,
                    'B': 400.00,
                    'C': 300.00,
                    'D': 200.00,
                    'E': 100.00
                };
                
                const montoMensual = montosEscala[escala.toUpperCase()] || 100.00;
                document.getElementById('monto_mensual').value = 'S/ ' + montoMensual.toFixed(2);

                const listaDeudasCheckbox = document.getElementById('listaDeudasCheckbox');
                if (listaDeudasCheckbox) {
                    listaDeudasCheckbox.innerHTML = '';
                }
                
                if (data.orden_reciente) {
                    if (contenedorDeuda) contenedorDeuda.classList.add('hidden');
                    if (contenedorMeses) contenedorMeses.classList.add('hidden');
                    if (contenedorFecha) contenedorFecha.classList.add('hidden');
                    if (contenedorResumen) contenedorResumen.classList.add('hidden');
                    
                    if (!document.getElementById('mensajeOrdenReciente')) {
                        const mensajeDiv = document.createElement('div');
                        mensajeDiv.id = 'mensajeOrdenReciente';
                        mensajeDiv.className = 'sm:col-span-3 lg:col-span-3 mt-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4';
                        mensajeDiv.innerHTML = `
                            <div class="flex items-start gap-3">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <div>
                                    <h4 class="font-semibold text-blue-800 dark:text-blue-300 mb-1">Ya existe una orden de pago pendiente</h4>
                                    <p class="text-sm text-blue-700 dark:text-blue-400">Ya generó una orden de pago recientemente (<strong>${data.ultima_orden}</strong>).</p>
                                    <p class="text-sm text-blue-700 dark:text-blue-400 mt-2">Esta orden está vigente hasta el: <strong>${data.fecha_vencimiento}</strong></p>
                                    <p class="text-sm text-blue-600 dark:text-blue-300 mt-3 italic">💡 Podrá generar una nueva orden cuando:</p>
                                    <ul class="text-sm text-blue-600 dark:text-blue-300 mt-1 ml-4 list-disc">
                                        <li>Haya pagado completamente la orden actual, o</li>
                                        <li>Se cumpla la fecha de vencimiento (${data.fecha_vencimiento})</li>
                                    </ul>
                                </div>
                            </div>
                        `;
                        const contenedorPrincipal = document.querySelector('.grid.grid-cols-1');
                        if (contenedorPrincipal) {
                            contenedorPrincipal.appendChild(mensajeDiv);
                        }
                    }
                    
                    return;
                }
                
                if (data.deudas_bloqueantes) {
                    if (contenedorDeuda) contenedorDeuda.classList.add('hidden');
                    if (contenedorMeses) contenedorMeses.classList.add('hidden');
                    if (contenedorFecha) contenedorFecha.classList.add('hidden');
                    if (contenedorResumen) contenedorResumen.classList.add('hidden');
                    
                    if (!document.getElementById('mensajeDeudaBloqueante')) {
                        const mensajeDiv = document.createElement('div');
                        mensajeDiv.id = 'mensajeDeudaBloqueante';
                        mensajeDiv.className = 'sm:col-span-3 lg:col-span-3 mt-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4';
                        mensajeDiv.innerHTML = `
                            <div class="flex items-start gap-3">
                                <svg class="w-6 h-6 text-red-600 dark:text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <div>
                                    <h4 class="font-semibold text-red-800 dark:text-red-300 mb-1">No se puede generar orden de pago</h4>
                                    <p class="text-sm text-red-700 dark:text-red-400">Este estudiante tiene deudas de meses anteriores sin pagar. Debe cancelar primero las deudas atrasadas antes de generar nuevas órdenes de pago.</p>
                                    <p class="text-sm text-red-700 dark:text-red-400 mt-2"><strong>Nota:</strong> Si ya generó una orden de pago para las deudas atrasadas, debe esperar a que se realice el pago o que la orden venza antes de poder generar una nueva.</p>
                                </div>
                            </div>
                        `;
                        const contenedorPrincipal = document.querySelector('.grid.grid-cols-1');
                        if (contenedorPrincipal) {
                            contenedorPrincipal.appendChild(mensajeDiv);
                        }
                    }
                    
                    return;
                }
                
                if (data.deudas && data.deudas.length > 0) {
                    montoMensualActual = data.deudas[0].monto_pendiente || 0;
                    mesActualAplicaMora = data.deudas[0].aplica_mora || false;
                    mesActualPorcentajeMora = parseFloat(data.deudas[0].porcentaje_mora) || 0;
                    mesActualDescripcionPolitica = data.deudas[0].descripcion_politica || '';
                    
                    data.deudas.forEach((deuda, index) => {
                        const deudaDiv = document.createElement('div');
                        deudaDiv.className = 'flex items-start gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors';
                        
                        const moraTexto = deuda.aplica_mora ? ` <span class="text-red-600 dark:text-red-400">(+${deuda.porcentaje_mora}% mora)</span>` : '';
                        
                        deudaDiv.innerHTML = `
                            <input type="checkbox" 
                                   id="deuda_${deuda.id_deuda}" 
                                   name="deudas[]" 
                                   value="${deuda.id_deuda}"
                                   data-monto-pendiente="${deuda.monto_pendiente}"
                                   data-porcentaje-mora="${deuda.porcentaje_mora || 0}"
                                   data-aplica-mora="${deuda.aplica_mora ? '1' : '0'}"
                                   data-es-deuda-anterior="${deuda.es_deuda_anterior ? '1' : '0'}"
                                   data-meses-disponibles="${deuda.meses_disponibles_adelantar}"
                                   data-periodo="${deuda.periodo}"
                                   data-fecha-limite="${deuda.fecha_limite}"
                                   class="mt-1 w-4 h-4 text-indigo-600 rounded focus:ring-2 focus:ring-indigo-500 deuda-checkbox">
                            <label for="deuda_${deuda.id_deuda}" class="flex-1 cursor-pointer">
                                <div class="font-medium text-gray-800 dark:text-white text-sm">
                                    ${deuda.concepto}
                                </div>
                                <div class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">
                                    Pendiente: <span class="font-semibold">S/ ${deuda.monto_pendiente.toFixed(2)}</span> ${moraTexto}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-500 mt-0.5">
                                    Vencimiento: ${deuda.fecha_limite}
                                </div>
                            </label>
                        `;
                        
                        if (listaDeudasCheckbox) {
                            listaDeudasCheckbox.appendChild(deudaDiv);
                        }
                    });
                    
                    if (contenedorDeuda) contenedorDeuda.classList.remove('hidden');
                    
                    if (data.deudas.length > 1) {
                        const mensajeDeudasMultiples = document.getElementById('mensajeDeudasMultiples');
                        if (mensajeDeudasMultiples) {
                            mensajeDeudasMultiples.classList.remove('hidden');
                        }
                    }

                    document.querySelectorAll('.deuda-checkbox').forEach(checkbox => {
                        checkbox.addEventListener('change', function() {
                            const errorDeudas = document.getElementById('error_deudas');
                            const listaDeudas = document.getElementById('listaDeudasCheckbox');
                            if (errorDeudas && !errorDeudas.classList.contains('hidden')) {
                                errorDeudas.classList.add('hidden');
                                listaDeudas.classList.remove('border-red-500');
                            }
                            actualizarResumen();
                        });
                    });
                    
                    formOrdenPago.dataset.porcentajeDescuento = data.politica_descuento || 10;
                    
                    // Usar el valor global en lugar de la primera deuda
                    const mesesMaximos = data.meses_maximos_adelantar || 0;
                    const inputMesesAdelantar = document.getElementById('meses_adelantar');
                    if (inputMesesAdelantar) {
                        inputMesesAdelantar.max = mesesMaximos;
                        inputMesesAdelantar.value = 0;
                        inputMesesAdelantar.dataset.mesesMaximos = mesesMaximos;
                        
                        const labelMeses = document.querySelector('label[for="meses_adelantar"]');
                        if (labelMeses && mesesMaximos > 0) {
                            labelMeses.textContent = '¿Cuántos meses desea adelantar?';
                        }
                    }

                    if (data.tiene_deudas_anteriores) {
                        alumnoTieneDeudasAnteriores = true; // Guardar el estado
                        document.getElementById('tiene_deudas_anteriores').value = '1';
                        if (contenedorDeuda) contenedorDeuda.classList.remove('hidden');
                        if (contenedorMeses) contenedorMeses.classList.add('hidden');
                        
                        const tituloDeudas = document.getElementById('tituloDeudas');
                        if (tituloDeudas) {
                            tituloDeudas.textContent = 'Deudas Atrasadas (Selecciona cuáles pagar)';
                        }
                        
                        if (!document.getElementById('mensajeDeudaAnterior')) {
                            const mensajeDiv = document.createElement('div');
                            mensajeDiv.id = 'mensajeDeudaAnterior';
                            mensajeDiv.className = 'sm:col-span-3 lg:col-span-3 mt-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4';
                            mensajeDiv.innerHTML = `
                                <div class="flex items-start gap-3">
                                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    <div>
                                        <h4 class="font-semibold text-yellow-800 dark:text-yellow-300 mb-1">Deudas de meses anteriores pendientes</h4>
                                        <p class="text-sm text-yellow-700 dark:text-yellow-400">Debe pagar primero las deudas atrasadas antes de poder adelantar pagos.</p>
                                    </div>
                                </div>
                            `;
                            if (contenedorDeuda && contenedorDeuda.parentElement) {
                                contenedorDeuda.parentElement.insertBefore(mensajeDiv, contenedorDeuda.nextSibling);
                            }
                        }
                    } else {
                        alumnoTieneDeudasAnteriores = false;
                        document.getElementById('tiene_deudas_anteriores').value = '0';
                        if (contenedorDeuda) contenedorDeuda.classList.add('hidden');
                        if (contenedorMeses) contenedorMeses.classList.remove('hidden');

                        const mensajeExistente = document.getElementById('mensajeDeudaAnterior');
                        if (mensajeExistente) {
                            mensajeExistente.remove();
                        }
                        
                        actualizarResumen();
                    }
                    
                    if (contenedorFecha) contenedorFecha.classList.remove('hidden');
                    if (contenedorResumen) contenedorResumen.classList.remove('hidden');
                    
                    actualizarResumen();
                } else {
                    if (!document.getElementById('mensajeSinDeudas')) {
                        const mensajeDiv = document.createElement('div');
                        mensajeDiv.id = 'mensajeSinDeudas';
                        mensajeDiv.className = 'sm:col-span-3 lg:col-span-3 mt-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4';
                        mensajeDiv.innerHTML = `
                            <div class="flex items-start gap-3">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <h4 class="font-semibold text-blue-800 dark:text-blue-300 mb-1">Alumno sin deudas pendientes</h4>
                                    <p class="text-sm text-blue-700 dark:text-blue-400">Este alumno no tiene deudas pendientes por pagar en este momento.</p>
                                </div>
                            </div>
                        `;
                        if (contenedorDeuda && contenedorDeuda.parentElement) {
                            contenedorDeuda.parentElement.insertBefore(mensajeDiv, contenedorDeuda);
                        }
                    }
                    limpiarFormulario();
                }
            })
            .catch(error => {
                console.error('Error al buscar alumno:', error);
                errorCodigo.textContent = error.message || 'Error al buscar el alumno';
                errorCodigo.classList.remove('hidden');
                codigoAlumnoInput.classList.add('border-red-500');
                // NO llamar a limpiarFormulario() para mantener el código del alumno visible
            });
    });

    document.getElementById('meses_adelantar').addEventListener('input', function(e) {
        const inputMeses = this;
        let valorTexto = inputMeses.value;
        
        // Eliminar cualquier caracter que no sea dígito
        valorTexto = valorTexto.replace(/[^0-9]/g, '');
        
        // Si está vacío, poner 0
        if (valorTexto === '') {
            inputMeses.value = '0';
            actualizarResumen();
            return;
        }
        
        let valor = parseInt(valorTexto);
        const mesesMaximos = parseInt(inputMeses.dataset.mesesMaximos) || 10;
        
        // Restringir entre 0 y el máximo calculado dinámicamente
        if (valor < 0) {
            valor = 0;
        }
        
        // Solo validar con mesesMaximos dinámico del backend
        if (valor > mesesMaximos) {
            valor = mesesMaximos;
            const errorMeses = document.getElementById('error_meses');
            if (!errorMeses) {
                const errorDiv = document.createElement('div');
                errorDiv.id = 'error_meses';
                errorDiv.className = 'text-sm text-amber-600 dark:text-amber-400 mt-1';
                errorDiv.textContent = `Solo puede adelantar hasta ${mesesMaximos} mes${mesesMaximos > 1 ? 'es' : ''} (hasta diciembre del año actual)`;
                inputMeses.parentElement.parentElement.appendChild(errorDiv);
                
                setTimeout(() => {
                    errorDiv.remove();
                }, 3000);
            }
        }
        
        inputMeses.value = valor.toString();
        actualizarResumen();
    });

    // Botones de incrementar/decrementar meses adelantar
    const btnIncrementar = document.getElementById('btnIncrementar');
    const btnDecrementar = document.getElementById('btnDecrementar');
    const inputMesesAdelantarContador = document.getElementById('meses_adelantar');

    if (btnIncrementar) {
        btnIncrementar.addEventListener('click', function(e) {
            e.preventDefault();
            const valorActual = parseInt(inputMesesAdelantarContador.value) || 0;
            const mesesMaximos = parseInt(inputMesesAdelantarContador.dataset.mesesMaximos) || 10;
            
            if (valorActual < mesesMaximos) {
                inputMesesAdelantarContador.value = valorActual + 1;
                inputMesesAdelantarContador.dispatchEvent(new Event('input'));
            } else {
                // Mostrar mensaje cuando se alcanza el límite
                const errorMeses = document.getElementById('error_meses');
                if (!errorMeses) {
                    const errorDiv = document.createElement('div');
                    errorDiv.id = 'error_meses';
                    errorDiv.className = 'text-sm text-amber-600 dark:text-amber-400 mt-1';
                    errorDiv.textContent = `Solo puede adelantar hasta ${mesesMaximos} mes${mesesMaximos > 1 ? 'es' : ''} (hasta diciembre del año actual)`;
                    inputMesesAdelantarContador.parentElement.parentElement.appendChild(errorDiv);
                    
                    setTimeout(() => {
                        errorDiv.remove();
                    }, 3000);
                }
            }
        });
    }

    if (btnDecrementar) {
        btnDecrementar.addEventListener('click', function(e) {
            e.preventDefault();
            const valorActual = parseInt(inputMesesAdelantarContador.value) || 0;
            
            if (valorActual > 0) {
                inputMesesAdelantarContador.value = valorActual - 1;
                inputMesesAdelantarContador.dispatchEvent(new Event('input'));
            }
        });
    }

    function actualizarResumen() {
        const checkboxesSeleccionados = document.querySelectorAll('.deuda-checkbox:checked');
        const mesesAdelantar = parseInt(document.getElementById('meses_adelantar').value) || 0;
        const porcentajeDescuento = parseFloat(formOrdenPago.dataset.porcentajeDescuento) || 10;
        
        const montoMensualEscala = montoMensualActual;
        
        let montoBaseDeudas = 0;
        let moraTotal = 0;
        let cantidadDeudas = checkboxesSeleccionados.length;
        let tieneDeudas = cantidadDeudas > 0;
        let tieneDeudasAtrasadas = alumnoTieneDeudasAnteriores; // Usar el estado del backend por defecto

        if (tieneDeudas) {
            checkboxesSeleccionados.forEach(checkbox => {
                const montoPendiente = parseFloat(checkbox.dataset.montoPendiente) || 0;
                const porcentajeMora = parseFloat(checkbox.dataset.porcentajeMora) || 0;
                const aplicaMora = checkbox.dataset.aplicaMora === '1';
                const esDeudaAnterior = checkbox.dataset.esDeudaAnterior === '1';
                
                montoBaseDeudas += montoPendiente;
                
                if (aplicaMora && porcentajeMora > 0) {
                    moraTotal += montoPendiente * (porcentajeMora / 100);
                }
                
                // Detectar si tiene deudas atrasadas (de meses anteriores)
                if (esDeudaAnterior) {
                    tieneDeudasAtrasadas = true;
                }
            });
        } else {
            // Solo usar el mes actual si NO tiene deudas anteriores pendientes
            if (!alumnoTieneDeudasAnteriores) {
                // Verificar si hay una deuda del mes actual pendiente
                // Si alumnoData tiene deudas y la primera es del mes actual
                const mesActual = new Date().getMonth() + 1; // 1-12
                let tieneDeudaMesActual = false;
                
                if (alumnoData && alumnoData.deudas && alumnoData.deudas.length > 0) {
                    const primeraDeuda = alumnoData.deudas[0];
                    if (primeraDeuda.mes === mesActual) {
                        tieneDeudaMesActual = true;
                    }
                }
                
                if (tieneDeudaMesActual) {
                    // Hay deuda del mes actual pendiente
                    montoBaseDeudas = montoMensualEscala;
                    cantidadDeudas = 1;
                    
                    // Calcular mora del mes actual si aplica
                    if (mesActualAplicaMora && mesActualPorcentajeMora > 0) {
                        moraTotal = montoMensualEscala * (mesActualPorcentajeMora / 100);
                    }
                } else {
                    // Ya pagó el mes actual, no hay mes base
                    montoBaseDeudas = 0;
                    cantidadDeudas = 0;
                }
            } else {
                // Si tiene deudas anteriores y no ha seleccionado ninguna, mostrar 0
                montoBaseDeudas = 0;
                cantidadDeudas = 0;
            }
        }
        
        let montoAdelantado = 0;
        if (mesesAdelantar > 0) {
            montoAdelantado = montoMensualEscala * mesesAdelantar;
        }
        
        const montoBase = montoBaseDeudas + montoAdelantado;
        
        const descuento = mesesAdelantar > 0 ? montoAdelantado * (porcentajeDescuento / 100) : 0;
        
        const total = montoBase - descuento + moraTotal;

        const totalMeses = cantidadDeudas + mesesAdelantar;
        if (mesesAdelantar === 0) {
            if (tieneDeudas) {
                document.getElementById('resumen_meses').textContent = `${cantidadDeudas} mes${cantidadDeudas > 1 ? 'es' : ''}`;
            } else if (alumnoTieneDeudasAnteriores && cantidadDeudas === 0) {
                // Si tiene deudas anteriores pero no ha seleccionado nada
                document.getElementById('resumen_meses').textContent = '0 meses';
            } else if (cantidadDeudas === 1) {
                document.getElementById('resumen_meses').textContent = '1 mes (actual)';
            } else {
                // No tiene mes actual pendiente
                document.getElementById('resumen_meses').textContent = '0 meses';
            }
        } else {
            if (tieneDeudas) {
                document.getElementById('resumen_meses').textContent = `${totalMeses} mes${totalMeses > 1 ? 'es' : ''} (${cantidadDeudas} actual${cantidadDeudas > 1 ? 'es' : ''} + ${mesesAdelantar} adelantado${mesesAdelantar > 1 ? 's' : ''})`;
            } else if (cantidadDeudas === 1) {
                // Tiene mes actual pendiente
                document.getElementById('resumen_meses').textContent = `${totalMeses} mes${totalMeses > 1 ? 'es' : ''} (1 actual + ${mesesAdelantar} adelantado${mesesAdelantar > 1 ? 's' : ''})`;
            } else {
                // Ya pagó el mes actual, solo adelantos
                document.getElementById('resumen_meses').textContent = `${mesesAdelantar} mes${mesesAdelantar > 1 ? 'es' : ''} (${mesesAdelantar} adelantado${mesesAdelantar > 1 ? 's' : ''})`;
            }
        }

        document.getElementById('resumen_base').textContent = `S/ ${montoBase.toFixed(2)}`;
        document.getElementById('resumen_descuento').textContent = `- S/ ${descuento.toFixed(2)}`;
        document.getElementById('resumen_mora').textContent = `+ S/ ${moraTotal.toFixed(2)}`;
        document.getElementById('resumen_total').textContent = `S/ ${total.toFixed(2)}`;
        
        // Mostrar u ocultar la descripción de la política
        const contenedorDetalle = document.getElementById('contenedor_detalle_politica');
        const textoDetalle = document.getElementById('resumen_detalle_politica');
        
        if (moraTotal > 0 && mesActualDescripcionPolitica) {
            const porcentajeFormateado = mesActualPorcentajeMora % 1 === 0 
                ? Math.floor(mesActualPorcentajeMora) 
                : mesActualPorcentajeMora;
            textoDetalle.textContent = `Mora ${porcentajeFormateado}% - ${mesActualDescripcionPolitica}`;
            contenedorDetalle.classList.remove('hidden');
        } else {
            contenedorDetalle.classList.add('hidden');
        }
        
        if (montoMensualEscala > 0) {
            if (contenedorResumen) contenedorResumen.classList.remove('hidden');
            if (contenedorFecha) contenedorFecha.classList.remove('hidden');
        }
        
        actualizarFechaVencimiento(mesesAdelantar, tieneDeudasAtrasadas);

        // Habilitar botón solo si el total de meses es mayor a 0
        const totalMesesParaBoton = cantidadDeudas + mesesAdelantar;
        let tieneAlgoSeleccionado;
        
        if (alumnoTieneDeudasAnteriores) {
            // Con deudas atrasadas: debe marcar checkboxes o adelantar meses
            tieneAlgoSeleccionado = tieneDeudas || mesesAdelantar > 0;
        } else {
            // Sin deudas atrasadas: debe tener al menos 1 mes (actual o adelantado)
            tieneAlgoSeleccionado = totalMesesParaBoton > 0;
        }
        
        if (tieneAlgoSeleccionado) {
            btnGenerarOrden.disabled = false;
            btnGenerarOrden.removeAttribute('disabled');
            btnGenerarOrden.classList.remove('opacity-50', 'cursor-not-allowed');
            btnGenerarOrden.classList.add('cursor-pointer');
        } else {
            btnGenerarOrden.disabled = true;
            btnGenerarOrden.setAttribute('disabled', 'disabled');
            btnGenerarOrden.classList.add('opacity-50', 'cursor-not-allowed');
            btnGenerarOrden.classList.remove('cursor-pointer');
        }
    }
    
    function actualizarFechaVencimiento(mesesAdelantar, tieneDeudasAtrasadas) {
        const hoy = new Date();
        let diasVencimiento;
        let textoVencimiento;
        
        // Deudas atrasadas tienen más tiempo (7 días)
        // Deudas normales y adelantos tienen menos tiempo (3 días)
        if (tieneDeudasAtrasadas) {
            // Deudas atrasadas: 7 días
            diasVencimiento = 7;
            textoVencimiento = 'Tiene 7 días para realizar el pago (deudas atrasadas)';
        } else if (mesesAdelantar > 0) {
            // Adelantos: 3 días
            diasVencimiento = 3;
            textoVencimiento = 'Tiene 3 días para realizar el pago (adelanto)';
        } else {
            // Deudas normales: 3 días
            diasVencimiento = 3;
            textoVencimiento = 'Tiene 3 días para realizar el pago';
        }
        
        const fechaVencimiento = new Date(hoy);
        fechaVencimiento.setDate(hoy.getDate() + diasVencimiento);
        
        const diaDisplay = String(fechaVencimiento.getDate()).padStart(2, '0');
        const mesDisplay = String(fechaVencimiento.getMonth() + 1).padStart(2, '0');
        const anioDisplay = fechaVencimiento.getFullYear();
        const fechaDisplay = `${diaDisplay}/${mesDisplay}/${anioDisplay}`;
        
        const diaBackend = String(fechaVencimiento.getDate()).padStart(2, '0');
        const mesBackend = String(fechaVencimiento.getMonth() + 1).padStart(2, '0');
        const anioBackend = fechaVencimiento.getFullYear();
        const fechaBackend = `${anioBackend}-${mesBackend}-${diaBackend}`;
        
        document.getElementById('fecha_vencimiento_display').value = fechaDisplay;
        document.getElementById('fecha_vencimiento').value = fechaBackend;
        
        const textoExplicativo = document.getElementById('textoVencimiento');
        if (textoExplicativo) {
            textoExplicativo.textContent = textoVencimiento;
        }
    }

    formOrdenPago.addEventListener('submit', function(e) {
        e.preventDefault();

        const errorDeudas = document.getElementById('error_deudas');
        const listaDeudas = document.getElementById('listaDeudasCheckbox');
        errorDeudas.classList.add('hidden');
        listaDeudas.classList.remove('border-red-500');
        
        if (!codigoAlumnoInput.value.trim()) {
            errorCodigo.textContent = 'Por favor ingrese el código del alumno';
            errorCodigo.classList.remove('hidden');
            codigoAlumnoInput.classList.add('border-red-500');
            codigoAlumnoInput.focus();
            return false;
        }

        if (!document.getElementById('id_alumno').value) {
            errorCodigo.textContent = 'Por favor busque el alumno primero';
            errorCodigo.classList.remove('hidden');
            codigoAlumnoInput.classList.add('border-red-500');
            return false;
        }

        const checkboxesSeleccionados = document.querySelectorAll('.deuda-checkbox:checked');
        const mesesAdelantar = parseInt(document.getElementById('meses_adelantar').value) || 0;
        const tieneDeudaAtrasada = !contenedorDeuda.classList.contains('hidden');

        if (tieneDeudaAtrasada && checkboxesSeleccionados.length === 0) {
            document.getElementById('error_deudas_texto').textContent = 'Debe seleccionar al menos una deuda para generar la orden de pago';
            errorDeudas.classList.remove('hidden');
            listaDeudas.classList.add('border-red-500');
            listaDeudas.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return false;
        }

        const formData = new FormData(formOrdenPago);

        document.getElementById('error_servidor').classList.add('hidden');
        
        btnGenerarOrden.disabled = true;
        btnGenerarOrden.innerHTML = '<svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Generando...';

        fetch(formOrdenPago.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.open(data.pdf_url, '_blank');
                
                window.location.href = data.redirect_url;
            } else {
                document.getElementById('error_servidor_texto').textContent = data.message || 'No se pudo generar la orden de pago';
                document.getElementById('error_servidor').classList.remove('hidden');
                document.getElementById('error_servidor').scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                btnGenerarOrden.disabled = false;
                btnGenerarOrden.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg> Generar Orden de Pago';
            }
        })
        .catch(error => {
            document.getElementById('error_servidor_texto').textContent = 'Error de conexión: ' + error.message;
            document.getElementById('error_servidor').classList.remove('hidden');
            document.getElementById('error_servidor').scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            btnGenerarOrden.disabled = false;
            btnGenerarOrden.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg> Generar Orden de Pago';
        });
    });

    const btnSiAdelantar = document.getElementById('btnSiAdelantar');
    const btnCancelarAdelantar = document.getElementById('btnCancelarAdelantar');
    const preguntaAdelantar = document.getElementById('preguntaAdelantar');
    const inputMesesAdelantar = document.getElementById('inputMesesAdelantar');
    const mesesAdelantarInput = document.getElementById('meses_adelantar');

    btnSiAdelantar.addEventListener('click', function() {
        preguntaAdelantar.classList.add('hidden');
        inputMesesAdelantar.classList.remove('hidden');
        mesesAdelantarInput.value = '1';
        mesesAdelantarInput.min = '1';
        actualizarResumen();
        if (contenedorFecha) contenedorFecha.classList.remove('hidden');
        if (contenedorResumen) contenedorResumen.classList.remove('hidden');
    });

    btnCancelarAdelantar.addEventListener('click', function() {
        inputMesesAdelantar.classList.add('hidden');
        preguntaAdelantar.classList.remove('hidden');
        mesesAdelantarInput.value = '0';
        mesesAdelantarInput.min = '0';
        actualizarResumen();
        if (contenedorFecha) contenedorFecha.classList.remove('hidden');
        if (contenedorResumen) contenedorResumen.classList.remove('hidden');
    });
});
