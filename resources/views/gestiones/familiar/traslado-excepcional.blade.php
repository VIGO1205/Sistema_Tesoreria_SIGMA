@extends('base.familiar.blank')

@section('titulo')
    Solicitud de Traslado Excepcional
@endsection

@section('extracss')
<style>
    .alert-deuda {
        animation: slideDown 0.3s ease-out;
    }
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endsection

@section('contenido')
    <div class="p-8 m-4 bg-gray-100 dark:bg-white/[0.03] rounded-2xl">
        <!-- Header -->
        <div class="flex pb-6 justify-between items-center border-b border-gray-200 dark:border-gray-700">
            <div>
                <h2 class="text-2xl font-bold dark:text-gray-200 text-gray-800">Generar Solicitud de Traslado</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Complete el formulario para solicitar el traslado excepcional</p>
            </div>
        </div>

        <form id="formSolicitudTraslado" class="mt-8">
            @csrf

            <!-- Información del Alumno -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Información del Alumno
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white dark:bg-gray-800 p-6 rounded-lg border border-gray-200 dark:border-gray-700">
                    <!-- Código de Educando -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Código de Educando
                        </label>
                        <input type="text" readonly
                            value="{{ $alumno->codigo_educando ?? 'N/A' }}"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                    </div>

                    <!-- Nombre Completo -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nombre Completo
                        </label>
                        <input type="text" readonly
                            value="{{ trim($alumno->primer_nombre . ' ' . $alumno->otros_nombres . ' ' . $alumno->apellido_paterno . ' ' . $alumno->apellido_materno) }}"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                    </div>

                    <!-- DNI -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            DNI
                        </label>
                        <input type="text" readonly
                            value="{{ $alumno->dni }}"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                    </div>

                    <!-- Información Académica -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Información Académica
                        </label>
                        <input type="text" readonly
                            value="{{ $matriculaActiva ? ($matriculaActiva->grado->nombre_grado . ' - Sección: ' . $matriculaActiva->nombreSeccion . ' (' . $matriculaActiva->año_escolar . ')') : 'No Matriculado' }}"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                    </div>
                </div>

                <!-- Mensaje Sin Deudas Pendientes (si no tiene deudas) -->
                @if(!$tieneDeudas)
                <div class="mt-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <p class="text-sm text-green-700 dark:text-green-300 font-medium">
                            Sin Deudas Pendientes
                        </p>
                    </div>
                    <p class="text-xs text-green-600 dark:text-green-400 mt-1 ml-7">
                        El alumno no tiene deudas pendientes. Puede proceder con la solicitud de traslado.
                    </p>
                </div>
                @endif
            </div>

            <!-- Mensaje de Verificación de Deudas (oculto por defecto) -->
            <div id="mensajeVerificacionDeuda" class="hidden mb-6"></div>

            <!-- Datos de la Solicitud de Traslado -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Datos de la Solicitud de Traslado
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white dark:bg-gray-800 p-6 rounded-lg border border-gray-200 dark:border-gray-700">
                    <!-- Colegio de Destino -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Colegio de Destino <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="colegio_destino" name="colegio_destino" required
                            placeholder="Ej: I.E. San Juan Bosco"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 px-4 py-3 text-sm text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <!-- Fecha de Traslado con botón de verificar -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Fecha de Traslado <span class="text-red-500">*</span>
                        </label>
                        <div class="flex gap-2">
                            <input type="date" id="fecha_traslado" name="fecha_traslado" required
                                min="{{ date('Y-m-d') }}"
                                class="flex-1 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 px-4 py-3 text-sm text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">

                            <button type="button" id="btnVerificarDeudas"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors flex items-center gap-2 whitespace-nowrap">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Verificar Deudas
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Haga clic en "Verificar Deudas" para confirmar que no hay pagos pendientes hasta esta fecha
                        </p>
                    </div>

                    <!-- Dirección del Nuevo Colegio -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Dirección del Nuevo Colegio
                        </label>
                        <input type="text" id="direccion_nuevo_colegio" name="direccion_nuevo_colegio"
                            placeholder="Av. Los Pinos 123, Lima"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 px-4 py-3 text-sm text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <!-- Teléfono del Nuevo Colegio -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Teléfono del Nuevo Colegio
                        </label>
                        <input type="tel" id="telefono_nuevo_colegio" name="telefono_nuevo_colegio"
                            placeholder="(01) 234-5678"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 px-4 py-3 text-sm text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <!-- Motivo del Traslado -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Motivo del Traslado <span class="text-red-500">*</span>
                        </label>
                        <textarea id="motivo_traslado" name="motivo_traslado" required rows="3"
                            placeholder="Explique brevemente el motivo del traslado..."
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 px-4 py-3 text-sm text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </div>

                    <!-- Observaciones Adicionales (opcional) -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Observaciones Adicionales (opcional)
                        </label>
                        <textarea id="observaciones" name="observaciones" rows="2"
                            placeholder="Cualquier información adicional relevante..."
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 px-4 py-3 text-sm text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="flex justify-end gap-3">
                <a href="{{ route('principal') }}"
                    class="px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    Cancelar
                </a>
                <button type="submit" id="btnEnviarSolicitud"
                    class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                    Enviar Solicitud
                </button>
            </div>
        </form>
    </div>
@endsection

@section('extrajs')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnVerificarDeudas = document.getElementById('btnVerificarDeudas');
    const btnEnviarSolicitud = document.getElementById('btnEnviarSolicitud');
    const fechaTrasladoInput = document.getElementById('fecha_traslado');
    const mensajeVerificacion = document.getElementById('mensajeVerificacionDeuda');
    const formSolicitud = document.getElementById('formSolicitudTraslado');

    let deudaVerificada = false;
    let tieneDeudas = null;

    // Verificar deudas pendientes
    btnVerificarDeudas.addEventListener('click', async function() {
        const fechaTraslado = fechaTrasladoInput.value;

        if (!fechaTraslado) {
            Swal.fire({
                icon: 'warning',
                title: 'Fecha requerida',
                text: 'Por favor, seleccione una fecha de traslado primero',
                confirmButtonColor: '#4F46E5'
            });
            return;
        }

        // Mostrar loading
        Swal.fire({
            title: 'Verificando deudas...',
            html: 'Por favor espere mientras verificamos las deudas pendientes',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        try {
            const response = await fetch('{{ route("familiar_traslado_verificar_deudas") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    fecha_traslado: fechaTraslado
                })
            });

            const data = await response.json();

            if (data.success) {
                deudaVerificada = true;
                tieneDeudas = data.tiene_deudas;

                // Ocultar el Swal de loading
                Swal.close();

                // Mostrar mensaje según resultado
                if (data.tiene_deudas) {
                    // Tiene deudas - Mostrar en ROJO
                    let deudasHTML = '<ul class="mt-2 space-y-1">';
                    data.deudas.forEach(deuda => {
                        deudasHTML += `<li class="text-xs">• ${deuda.concepto} - ${deuda.periodo} (Vence: ${deuda.fecha_limite}) - S/ ${deuda.monto_pendiente}</li>`;
                    });
                    deudasHTML += '</ul>';

                    mensajeVerificacion.innerHTML = `
                        <div class="alert-deuda p-4 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 rounded-lg">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-red-600 dark:text-red-400 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-red-800 dark:text-red-200">
                                        ⚠️ Deudas Pendientes Detectadas
                                    </p>
                                    <p class="text-xs text-red-700 dark:text-red-300 mt-1">
                                        El alumno tiene ${data.cantidad_deudas} deuda(s) pendiente(s) hasta la fecha de traslado por un monto total de S/ ${data.monto_total_pendiente}
                                    </p>
                                    ${deudasHTML}
                                    <p class="text-xs text-red-600 dark:text-red-400 mt-2 font-medium">
                                        Debe regularizar estos pagos antes de solicitar el traslado.
                                    </p>
                                </div>
                            </div>
                        </div>
                    `;
                    mensajeVerificacion.classList.remove('hidden');
                } else {
                    // No tiene deudas - Mostrar en VERDE
                    mensajeVerificacion.innerHTML = `
                        <div class="alert-deuda p-4 bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 rounded-lg">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-green-800 dark:text-green-200">
                                        ✓ Sin Deudas Pendientes
                                    </p>
                                    <p class="text-xs text-green-700 dark:text-green-300 mt-1">
                                        El alumno no tiene deudas pendientes hasta la fecha de traslado. Puede proceder con la solicitud.
                                    </p>
                                </div>
                            </div>
                        </div>
                    `;
                    mensajeVerificacion.classList.remove('hidden');
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'No se pudo verificar las deudas',
                    confirmButtonColor: '#4F46E5'
                });
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error al verificar las deudas. Intente nuevamente.',
                confirmButtonColor: '#4F46E5'
            });
        }
    });

    // Enviar solicitud
    formSolicitud.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Validar que se haya verificado las deudas
        if (!deudaVerificada) {
            Swal.fire({
                icon: 'warning',
                title: 'Verificación Requerida',
                text: 'Por favor, verifique las deudas pendientes antes de enviar la solicitud',
                confirmButtonColor: '#4F46E5'
            });
            return;
        }

        // Si tiene deudas, no permitir enviar
        if (tieneDeudas) {
            Swal.fire({
                icon: 'error',
                title: 'Deudas Pendientes',
                text: 'No puede solicitar el traslado mientras tenga deudas pendientes. Por favor, regularice sus pagos primero.',
                confirmButtonColor: '#4F46E5'
            });
            return;
        }

        // Confirmación final
        const result = await Swal.fire({
            title: '¿Confirmar Solicitud?',
            text: 'Se enviará la solicitud de traslado excepcional',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4F46E5',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Sí, enviar',
            cancelButtonText: 'Cancelar'
        });

        if (!result.isConfirmed) return;

        // Mostrar loading
        Swal.fire({
            title: 'Enviando solicitud...',
            html: 'Por favor espere',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Recopilar datos del formulario
        const formData = {
            colegio_destino: document.getElementById('colegio_destino').value,
            fecha_traslado: document.getElementById('fecha_traslado').value,
            direccion_nuevo_colegio: document.getElementById('direccion_nuevo_colegio').value,
            telefono_nuevo_colegio: document.getElementById('telefono_nuevo_colegio').value,
            motivo_traslado: document.getElementById('motivo_traslado').value,
            observaciones: document.getElementById('observaciones').value
        };

        try {
            const response = await fetch('{{ route("familiar_traslado_guardar_solicitud") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(formData)
            });

            const data = await response.json();

            if (data.success) {
                await Swal.fire({
                    icon: 'success',
                    title: '¡Solicitud Enviada!',
                    html: `
                        <p class="mb-2">Su solicitud ha sido enviada exitosamente.</p>
                        <p class="font-semibold text-indigo-600">Código: ${data.codigo_solicitud}</p>
                    `,
                    confirmButtonColor: '#4F46E5'
                });

                // Redirigir al inicio
                window.location.href = '{{ route("principal") }}';
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'No se pudo enviar la solicitud',
                    confirmButtonColor: '#4F46E5'
                });
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error al enviar la solicitud. Intente nuevamente.',
                confirmButtonColor: '#4F46E5'
            });
        }
    });

    // Si cambia la fecha de traslado, resetear la verificación
    fechaTrasladoInput.addEventListener('change', function() {
        deudaVerificada = false;
        tieneDeudas = null;
        mensajeVerificacion.classList.add('hidden');
    });
});
</script>
@endsection
