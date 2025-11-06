@extends('base.administrativo.blank')

@section('titulo')
    Detalles del Pago #{{ $pago->id_pago }}
@endsection

@section('contenido')

    <div class="p-8 m-4 dark:bg-white/[0.03] rounded-2xl">
        <!-- Contenedor cabecera -->
        <div class="p-6 mb-8">
            <!-- Título + botón -->
            <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-semibold dark:text-amber-50 text-black">
                Detalles del Pago #{{ $pago->id_pago }}
            </h2>

            <a href="{{ route('pago_view') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 
                      text-gray-700 dark:text-gray-200 text-sm font-medium 
                      rounded-lg shadow hover:bg-gray-300 dark:hover:bg-gray-600 
                      transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 19l-7-7 7-7"/>
                </svg>
                Regresar
            </a>
        </div>

        <!-- Fila 1 -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
            <div class="flex flex-col">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Código Educando</label>
                <input type="text" value="{{ $alumno->codigo_educando }}" readonly
                       class="w-full rounded-lg border border-gray-300 dark:border-gray-700 
                              px-3 py-2.5 text-sm bg-gray-50 dark:bg-gray-800 
                              text-gray-800 dark:text-white/90">
            </div>

            <div class="flex flex-col">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Código Modular</label>
                <input type="text" value="{{ $alumno->codigo_modular }}" readonly
                       class="w-full rounded-lg border border-gray-300 dark:border-gray-700 
                              px-3 py-2.5 text-sm bg-gray-50 dark:bg-gray-800 
                              text-gray-800 dark:text-white/90">
            </div>

            <div class="flex flex-col col-span-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Nombre Completo</label>
                <input type="text" value="{{ $alumno->apellido_paterno }} {{ $alumno->apellido_materno }} {{ $alumno->primer_nombre }} {{ $alumno->otros_nombres }}" readonly
                       class="w-full rounded-lg border border-gray-300 dark:border-gray-700 
                              px-3 py-2.5 text-sm bg-gray-50 dark:bg-gray-800 
                              text-gray-800 dark:text-white/90">
            </div>
        </div>

        <!-- Fila 2 -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div class="flex flex-col">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">DNI</label>
                <input type="text" value="{{ $alumno->dni }}" readonly
                       class="w-full rounded-lg border border-gray-300 dark:border-gray-700 
                              px-3 py-2.5 text-sm bg-gray-50 dark:bg-gray-800 
                              text-gray-800 dark:text-white/90">
            </div>

            <div class="flex flex-col">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Año de Ingreso</label>
                <input type="text" value="{{ $alumno->año_ingreso }}" readonly
                       class="w-full rounded-lg border border-gray-300 dark:border-gray-700 
                              px-3 py-2.5 text-sm bg-gray-50 dark:bg-gray-800 
                              text-gray-800 dark:text-white/90">
            </div>

            <div class="flex flex-col">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Teléfono</label>
                <input type="text" value="{{ $alumno->telefono }}" readonly
                       class="w-full rounded-lg border border-gray-300 dark:border-gray-700 
                              px-3 py-2.5 text-sm bg-gray-50 dark:bg-gray-800 
                              text-gray-800 dark:text-white/90">
            </div>
        </div>

        <!-- Fila 3 -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="flex flex-col">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Dirección</label>
                <input type="text" value="{{ $alumno->direccion }}" readonly
                       class="w-full rounded-lg border border-gray-300 dark:border-gray-700 
                              px-3 py-2.5 text-sm bg-gray-50 dark:bg-gray-800 
                              text-gray-800 dark:text-white/90">
            </div>

            <div class="flex flex-col">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Monto Total</label>
                <input type="text" value="S/ {{ number_format($detalles->sum('monto'), 2) }}" readonly
                       class="w-full rounded-lg border border-gray-300 dark:border-gray-700 
                              px-3 py-2.5 text-sm bg-gray-50 dark:bg-gray-800 
                              text-gray-800 dark:text-white/90">
            </div>
        </div>
    </div>

    <!-- Aquí siguen tus detalles -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @php
            // Asegurar que siempre haya 2 elementos en el array
            $detallesArray = $detalles->toArray();
            $detallesCompletos = array_pad($detallesArray, 2, null);
        @endphp

        @for($i = 0; $i < 2; $i++)
            @php
                $detalle = $detallesCompletos[$i];
            @endphp

            <div class="p-4 rounded-lg border bg-gray-50 dark:bg-gray-800/40">
                <h3 class="text-md font-semibold mb-3 dark:text-amber-50 text-black">
                    Detalle #{{ $i+1 }}
                </h3>

                @if($detalle)
                    <!-- Alerta de rechazo -->
                    @if($detalle['estado_validacion'] === 'rechazado')
                        <div class="mb-4 p-4 rounded-lg bg-red-50 dark:bg-red-900/20 border-2 border-red-300 dark:border-red-800">
                            <div class="flex items-start gap-3">
                                <span class="text-2xl">⚠️</span>
                                <div class="flex-1">
                                    <h4 class="text-sm font-bold text-red-800 dark:text-red-300 mb-1">
                                        Detalle de Pago Rechazado
                                    </h4>
                                    <p class="text-sm text-red-700 dark:text-red-400">
                                        <strong>Razón:</strong> {{ $detalle['razon_ia'] ?? 'No se proporcionó una razón específica. Por favor contacte con la secretaría.' }}
                                    </p>
                                    <p class="text-xs text-red-600 dark:text-red-500 mt-2">
                                        Por favor, corrija la información a continuación y guarde los cambios.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form id="form-detalle-{{ $detalle['id_detalle'] }}" action="{{ route('pago_actualizar_detalle', $detalle['id_detalle']) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <!-- Fecha -->
                        <div class="flex flex-col mb-3">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Fecha de Pago</label>
                            <input type="date"
                                   name="fecha_pago"
                                   value="{{ \Carbon\Carbon::parse($detalle['fecha_pago'])->format('Y-m-d') }}"
                                   {{ $detalle['estado_validacion'] === 'rechazado' ? '' : 'readonly' }}
                                   class="w-full rounded-lg border border-gray-300 dark:border-gray-700 
                                          px-3 py-2.5 text-sm {{ $detalle['estado_validacion'] === 'rechazado' ? 'bg-white dark:bg-gray-900' : 'bg-gray-50 dark:bg-gray-800' }} 
                                          text-gray-800 dark:text-white/90">
                        </div>

                        <!-- Monto -->
                        <div class="flex flex-col mb-3">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Monto</label>
                            <input type="number" 
                                   name="monto"
                                   step="0.01"
                                   value="{{ $detalle['monto'] }}"
                                   {{ $detalle['estado_validacion'] === 'rechazado' ? '' : 'readonly' }}
                                   class="w-full rounded-lg border border-gray-300 dark:border-gray-700 
                                          px-3 py-2.5 text-sm {{ $detalle['estado_validacion'] === 'rechazado' ? 'bg-white dark:bg-gray-900' : 'bg-gray-50 dark:bg-gray-800' }} 
                                          text-gray-800 dark:text-white/90">
                        </div>

                        <!-- Nro Recibo -->
                        <div class="flex flex-col mb-3">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Nro Recibo</label>
                            <input type="text" 
                                   name="nro_recibo"
                                   value="{{ $detalle['nro_recibo'] }}"
                                   {{ $detalle['estado_validacion'] === 'rechazado' ? '' : 'readonly' }}
                                   class="w-full rounded-lg border border-gray-300 dark:border-gray-700 
                                          px-3 py-2.5 text-sm {{ $detalle['estado_validacion'] === 'rechazado' ? 'bg-white dark:bg-gray-900' : 'bg-gray-50 dark:bg-gray-800' }} 
                                          text-gray-800 dark:text-white/90">
                        </div>

                        <!-- Estado -->
                    <div class="flex flex-col mb-3">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Estado</label>
                        @if($detalle['estado_validacion'] === 'validado')
                            <p class="text-sm sm:text-base font-semibold px-4 py-2 rounded-full shadow-md text-center 
                                    bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 transition-all duration-300">
                                Validado
                            </p>
                        @elseif($detalle['estado_validacion'] === 'rechazado')
                            <p class="text-sm sm:text-base font-semibold px-4 py-2 rounded-full shadow-md text-center 
                                    bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 transition-all duration-300">
                                Rechazado
                            </p>
                        @else
                            <p class="text-sm sm:text-base font-semibold px-4 py-2 rounded-full shadow-md text-center 
                                    bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400 transition-all duration-300">
                                Pendiente
                            </p>
                        @endif
                    </div>

                    <!-- Constancia de Pago -->
                    @if($detalle['voucher_path'] && in_array(strtolower($detalle['metodo_pago'] ?? ''), ['yape', 'plin', 'transferencia']))
                        <div class="flex flex-col">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">
                                Constancia de Pago
                            </label>

                            @if(Str::endsWith($detalle['voucher_path'], ['.jpg', '.jpeg', '.png']))
                                <!-- BOTÓN PARA VER IMAGEN -->
                                <div class="flex items-center rounded-lg border border-gray-300 dark:border-gray-700 focus-within:ring-2 focus-within:ring-indigo-500">
                                    <button type="button"
                                            onclick="verImagen('{{ asset('storage/'.$detalle['voucher_path']) }}')"
                                            class="w-full px-3 py-2.5 text-sm text-gray-800 dark:text-white/90 text-left rounded-l-lg">
                                        VER VOUCHER (JPG, JPEG, PNG)
                                    </button>

                                    <span class="px-3 py-2.5 bg-gray-100 dark:bg-gray-800 border-l border-gray-300 dark:border-gray-700 flex items-center justify-center rounded-r-lg">
                                        <!-- Ícono de imagen -->
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5 text-gray-500 dark:text-gray-400"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 4a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V4z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8.5 11.5l2.5 2.5 3.5-4.5 4.5 6H5l3.5-4.5z" />
                                        </svg>
                                    </span>
                                </div>

                            @elseif(Str::endsWith($detalle['voucher_path'], '.pdf'))
                                <!-- BOTÓN PARA VER PDF -->
                                <div class="flex items-center rounded-lg border border-gray-300 dark:border-gray-700 focus-within:ring-2 focus-within:ring-indigo-500">
                                    <a href="{{ asset('storage/'.$detalle['voucher_path']) }}"
                                    target="_blank"
                                    class="w-full px-3 py-2.5 text-sm text-gray-800 dark:text-white/90 text-left rounded-l-lg">
                                        VER PDF
                                    </a>

                                    <span class="px-3 py-2.5 bg-gray-100 dark:bg-gray-800 border-l border-gray-300 dark:border-gray-700 flex items-center justify-center rounded-r-lg">
                                        <!-- Ícono de PDF -->
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5 text-gray-500 dark:text-gray-400"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 2h10a2 2 0 012 2v16a2 2 0 01-2 2H7a2 2 0 01-2-2V4a2 2 0 012-2z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 8h6M9 12h6m-6 4h6" />
                                        </svg>
                                    </span>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Nuevo voucher (solo si está rechazado) -->
                    @if($detalle['estado_validacion'] === 'rechazado')
                        <div class="flex flex-col mb-3">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">
                                Subir Nuevo Voucher (Opcional)
                                <span class="text-gray-500 text-xs">(JPG, PNG, PDF)</span>
                            </label>

                            <div class="flex items-center rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900">
                                <!-- Input oculto -->
                                <input type="file" 
                                       name="nuevo_voucher" 
                                       id="nuevo_voucher_{{ $detalle['id_detalle'] }}" 
                                       class="hidden"
                                       accept=".jpg,.jpeg,.png,.pdf">

                                <!-- Botón con ícono de archivo -->
                                <button type="button" 
                                        onclick="document.getElementById('nuevo_voucher_{{ $detalle['id_detalle'] }}').click()"
                                        class="w-full flex items-center justify-center gap-2 px-3 py-2.5 text-sm font-medium text-indigo-600 rounded-lg transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    <span id="voucher_label_{{ $detalle['id_detalle'] }}" class="text-gray-500">Seleccionar archivo</span>
                                </button>
                            </div>

                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Formatos: JPG, PNG, PDF (Máx. 5MB)
                            </p>
                        </div>

                        <script>
                            document.getElementById('nuevo_voucher_{{ $detalle['id_detalle'] }}').addEventListener('change', function(e) {
                                const label = document.getElementById('voucher_label_{{ $detalle['id_detalle'] }}');
                                if (e.target.files.length > 0) {
                                    label.textContent = e.target.files[0].name;
                                    label.classList.remove('text-gray-500');
                                    label.classList.add('text-indigo-600');
                                } else {
                                    label.textContent = 'Seleccionar archivo';
                                    label.classList.remove('text-indigo-600');
                                    label.classList.add('text-gray-500');
                                }
                            });
                        </script>

                        <!-- Botón Guardar Cambios -->
                        <div class="flex justify-end mt-4">
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg shadow-md transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Guardar Cambios
                            </button>
                        </div>
                    @endif

                    </form>

                @else
                    <!-- CARD VACÍO -->
                    <!-- Fecha -->
                    <div class="flex flex-col mb-3">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Fecha de Pago</label>
                        <input type="text"
                               value="dd/mm/yy"
                               readonly
                               class="w-full rounded-lg border border-gray-300 dark:border-gray-700 
                                      px-3 py-2.5 text-sm bg-gray-50 dark:bg-gray-800 
                                      text-gray-400 dark:text-gray-500">
                    </div>

                    <!-- Monto -->
                    <div class="flex flex-col mb-3">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Monto</label>
                        <input type="text" value="" readonly
                               class="w-full rounded-lg border border-gray-300 dark:border-gray-700 
                                      px-3 py-2.5 text-sm bg-gray-50 dark:bg-gray-800 
                                      text-gray-400 dark:text-gray-500">
                    </div>

                    <!-- Nro Recibo -->
                    <div class="flex flex-col mb-3">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Nro Recibo</label>
                        <input type="text" value="" readonly
                               class="w-full rounded-lg border border-gray-300 dark:border-gray-700 
                                      px-3 py-2.5 text-sm bg-gray-50 dark:bg-gray-800 
                                      text-gray-400 dark:text-gray-500">
                    </div>

                    <!-- Estado -->
                    <div class="flex flex-col mb-3">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Estado</label>
                        <p class="text-sm sm:text-base font-semibold px-4 py-2 rounded-full shadow-md text-center 
                                bg-yellow-100 text-yellow-700 transition-all duration-300">
                            Pendiente
                        </p>
                    </div>
                @endif
            </div>
        @endfor
    </div>
    </div>

    <!-- MODAL PARA LA IMAGEN -->
    <div id="modalVoucher" class="fixed inset-0 hidden bg-black/70 flex items-center justify-center z-9999999999">
        <div class="bg-white dark:bg-gray-900 rounded-lg overflow-hidden shadow-lg max-w-3xl">
            <div class="flex justify-between items-center p-3 border-b border-gray-300 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-700 dark:text-white">Imágen del Voucher</h2>
                <button onclick="cerrarModal()" class="text-gray-500 hover:text-gray-800 dark:hover:text-gray-200">
                    ✕
                </button>
            </div>
            <div class="p-4">
                <img id="imagenVoucher" src="" alt="Voucher" class="max-h-[60vh] mx-auto rounded">
            </div>
        </div>
    </div>

    <script>
        function verImagen(src) {
            const modal = document.getElementById('modalVoucher');
            const img = document.getElementById('imagenVoucher');
            img.src = src;
            modal.classList.remove('hidden');
        }

        function cerrarModal() {
            const modal = document.getElementById('modalVoucher');
            modal.classList.add('hidden');
        }   

        window.addEventListener('click', function (e) {
            const modal = document.getElementById('modalVoucher');
            if (e.target === modal) {
                cerrarModal();
            }
        });
    </script>
@endsection