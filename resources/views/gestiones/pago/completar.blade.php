@extends('base.administrativo.blank')

@section('title')
    Completar Pago
@endsection

@section('contenido')

    @if(session('success'))
        <div class="p-4 m-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="p-8 m-4 dark:bg-white/[0.03] rounded-2xl">
        <div class="flex pb-4 justify-between items-center">
            <h2 class="text-lg dark:text-gray-200 text-gray-800">
                Estás completando el Pago
            </h2>

            <div class="flex gap-4">
                <input form="form" type="submit" id="btnCompletar"
                    class="cursor-pointer inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-gray-200 px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-300 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-white/[0.03] dark:hover:text-gray-200"
                    value="Completar Pago">

                <a href="{{ $data['return'] }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-gray-200 px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-300 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
                    Cancelar
                </a>
            </div>
        </div>

        <form method="POST" id="form" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" 
            action="{{ route('pago_completarPago', $data['id_pago']) }}"
            enctype="multipart/form-data">
            @method('PUT')
            @csrf

            <!-- Fecha de Pago -->
            <div class="flex flex-col">
                <label for="fecha_de_pago" class="text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">
                    Fecha de Pago
                </label>

                <div
                    class="flex items-center rounded-lg border border-gray-300 dark:border-gray-700 focus-within:ring-2 focus-within:ring-indigo-500">
                    <input type="date" id="fecha_de_pago" name="fecha_de_pago" readonly
                        value="{{ old('fecha_de_pago') ?? now()->format('Y-m-d') }}"
                        class="w-full px-3 py-2.5 text-sm text-gray-800 dark:text-white/90 focus:outline-none rounded-l-lg bg-gray-100 dark:bg-gray-800">

                    <span
                        class="px-3 py-2.5 bg-gray-100 dark:bg-gray-800 border-l border-gray-300 dark:border-gray-700 flex items-center justify-center rounded-r-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </span>
                </div>

                <div class="min-h-[20px]">
                    @error('fecha_de_pago')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Código de Educando -->
            <div class="flex flex-col">
                <label for="codigo_alumno" class="text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">
                    Código educando
                </label>
                <input type="text" id="codigo_alumno" name="codigo_alumno" 
                    value="{{ $data['alumno']->codigo_educando }}" readonly
                    class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 px-3 py-2.5 text-sm text-gray-800 dark:text-white/90">
                <div class="min-h-[20px]"></div>
            </div>

            <!-- Nombre Completo del Alumno -->
            <div class="flex flex-col">
                <label for="nombre_alumno" class="text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">
                    Nombre Completo
                </label>
                <input type="text" id="nombre_alumno" name="nombre_alumno" 
                    value="{{ trim($data['alumno']->primer_nombre . ' ' . ($data['alumno']->otros_nombres ?? '') . ' ' . $data['alumno']->apellido_paterno . ' ' . $data['alumno']->apellido_materno) }}" readonly
                    class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 px-3 py-2.5 text-sm text-gray-800 dark:text-white/90">
                <div class="min-h-[20px]"></div>
            </div>

            {{-- Deuda --}}
            <div class="flex flex-col">
                <label for="select_deuda" class="text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">
                    Deuda
                </label>
                <select id="select_deuda" name="id_deuda"
                    class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 text-sm text-gray-800 dark:text-white/90 px-3 py-2.5"
                    disabled>
                    <option value="{{ $data['deuda']->id_deuda }}" selected>
                        {{ $data['concepto']->descripcion }} - {{ $data['deuda']->periodo }} (S/ {{ number_format($data['deuda']->monto_total, 2) }})
                    </option>
                </select>
                <input type="hidden" name="id_deuda" value="{{ $data['deuda']->id_deuda }}">
                <div class="min-h-[20px]">
                    @error('id_deuda')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Monto total a pagar --}}
            <div class="flex flex-col">
                <label for="monto_total_a_pagar" class="text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">
                    Monto total a pagar
                </label>
                <input type="text" id="monto_total_a_pagar" name="monto_total_a_pagar"
                    value="{{ number_format($data['deuda']->monto_total, 2, '.', '') }}" readonly
                    class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 px-3 py-2.5 text-sm text-gray-800 dark:text-white/90">
                <div class="min-h-[20px]"></div>
            </div>

            {{-- Monto pagado --}}
            <div class="flex flex-col">
                <label for="monto_pagado" class="text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">
                    Monto pagado
                </label>
                <input type="text" id="monto_pagado" name="monto_pagado" 
                    value="{{ number_format($data['detalle_existente']->monto, 2, '.', '') }}"
                    readonly
                    class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 px-3 py-2.5 text-sm text-gray-800 dark:text-white/90">
                <div class="min-h-[20px]"></div>
            </div>

            <!-- Observaciones -->
            <div class="col-span-1 sm:col-span-2 lg:col-span-3 flex flex-col">
                <label for="observaciones"
                    class="text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Observaciones</label>
                <textarea id="observaciones" name="observaciones" rows="2"
                    class="w-full rounded-lg border border-gray-300 dark:border-gray-700 px-3 py-2.5 text-sm text-gray-800 dark:text-white/90 focus:ring-2 focus:ring-indigo-500">{{ old('observaciones') }}</textarea>
                <div class="min-h-[20px]">
                    @error('observaciones')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Detalle de Pago --}}
            <div id="detalles-pago-container" class="col-span-1 sm:col-span-2 lg:col-span-3 mt-8">

                <h2 class="text-lg text-gray-800 dark:text-gray-200">
                    Detalle de Pago
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                    {{-- Primer pago (existente) --}}
                    <div class="detalle-pago border rounded-lg p-6 bg-green-50 dark:bg-green-900/20">
                        <h4 class="text-base text-gray-800 dark:text-gray-200 mb-4">Pago 1</h4>
                        
                        <!-- Estado -->
                        <p id="estado_pago_1"
                            class="text-xs font-semibold mb-2 px-4 py-2 rounded-lg text-center shadow-sm 
                            @if($data['detalle_existente']->estado_validacion === 'validado')
                                bg-green-100 text-green-800
                            @elseif($data['detalle_existente']->estado_validacion === 'rechazado')
                                bg-red-100 text-red-800
                            @else
                                bg-yellow-100 text-yellow-800
                            @endif">
                            @if($data['detalle_existente']->estado_validacion === 'validado')
                                VALIDADO
                            @elseif($data['detalle_existente']->estado_validacion === 'rechazado')
                                RECHAZADO
                            @else
                                PENDIENTE
                            @endif
                        </p>

                        <!-- Método de pago -->
                        <div class="flex flex-col mb-3">
                            <label for="metodo_pago_1" class="text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">
                                Método de Pago
                            </label>
                            <select name="metodo_pago_1_readonly" id="metodo_pago_1" class="w-full rounded-lg border border-gray-300 dark:border-gray-700
                                        bg-gray-100 dark:bg-gray-800 text-sm px-3 py-2.5 
                                        text-gray-800 dark:text-white/90" disabled>
                                <option value="{{ $data['detalle_existente']->metodo_pago }}" selected>{{ ucfirst($data['detalle_existente']->metodo_pago) }}</option>
                            </select>
                            <div class="min-h-[20px]"></div>
                        </div>

                        <!-- Número de operación -->
                        <div class="flex flex-col mb-3">
                            <label for="detalle_recibo_1" class="text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">
                                Nro de operación / recibo
                            </label>
                            <input type="text" id="detalle_recibo_1"
                                value="{{ $data['detalle_existente']->nro_recibo }}" readonly
                                class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800
                                        px-3 py-2.5 text-sm text-gray-800 dark:text-white/90">
                            <div class="min-h-[20px]"></div>
                        </div>

                        <!-- Monto -->
                        <div class="flex flex-col mb-3">
                            <label for="detalle_monto_1" class="text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">
                                Monto
                            </label>
                            <input type="text" id="detalle_monto_1"
                                value="{{ number_format((float) $data['detalle_existente']->monto, 2, '.', '') }}" readonly
                                class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800
                                        px-3 py-2.5 text-sm text-gray-800 dark:text-white/90">
                            <div class="min-h-[20px]"></div>
                        </div>

                        <!-- Fecha -->
                        <div class="flex flex-col mb-3">
                            <label for="detalle_fecha_1" class="text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">
                                Fecha de Pago
                            </label>

                            <div class="flex items-center rounded-lg border border-gray-300 dark:border-gray-700">
                                <input type="date" id="detalle_fecha_1"
                                    value="{{ $data['detalle_existente']->fecha_pago->format('Y-m-d') }}" readonly
                                    class="w-full px-3 py-2.5 text-sm text-gray-800 dark:text-white/90 
                                            focus:outline-none rounded-l-lg bg-gray-100 dark:bg-gray-800">

                                <span class="px-3 py-2.5 bg-gray-100 dark:bg-gray-800 border-l border-gray-300 dark:border-gray-700 
                                                flex items-center justify-center rounded-r-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 dark:text-gray-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </span>
                            </div>

                            <div class="min-h-[20px]"></div>
                        </div>

                        @if($data['detalle_existente']->voucher_path)
                        <div class="flex flex-col mb-3">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">
                                Constancia de Pago
                                <span class="text-gray-500 text-xs">(JPG, PNG, PDF)</span>
                            </label>

                            <div class="flex items-center rounded-lg border border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800">
                                <div class="w-full flex items-center justify-center gap-2 px-3 py-2.5 text-sm font-medium rounded-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    <span class="text-green-600 font-semibold">✓ {{ basename($data['detalle_existente']->voucher_path) }}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- Segundo pago (a completar) --}}
                    <div class="detalle-pago border rounded-xl p-6 bg-gray-50 dark:bg-gray-800/40">
                        <h4 class="text-base text-gray-800 dark:text-gray-200 mb-4">Pago 2</h4>

                        <!-- Estado -->
                        <p id="estado_pago_2"
                            class="text-xs font-semibold mb-2 px-4 py-2 rounded-lg text-center shadow-sm bg-yellow-100 text-yellow-800">
                            PENDIENTE
                        </p>

                        <!-- Método de pago -->
                        <div class="flex flex-col mb-3">
                            <label for="metodo_pago_2" class="text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">
                                Método de Pago
                            </label>
                            <select name="metodo_pago" id="metodo_pago_2" class="w-full rounded-lg border 
                                        {{ $errors->has('metodo_pago') ? 'border-red-500' : 'border-gray-300 dark:border-gray-700' }}
                                        bg-white dark:bg-gray-800 text-sm px-3 py-2.5 
                                        text-gray-800 dark:text-white/90 focus:ring-2 focus:ring-indigo-500">
                                <option value="">Seleccione...</option>
                                <option value="tarjeta" {{ old('metodo_pago') == 'tarjeta' ? 'selected' : '' }}>Tarjeta</option>
                                <option value="yape" {{ old('metodo_pago') == 'yape' ? 'selected' : '' }}>Yape / Plin</option>
                                <option value="transferencia" {{ old('metodo_pago') == 'transferencia' ? 'selected' : '' }}>Transferencia</option>
                                <option value="paypal" {{ old('metodo_pago') == 'paypal' ? 'selected' : '' }}>PayPal</option>
                            </select>
                            <div class="min-h-[20px]">
                                @error('metodo_pago')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Número de operación -->
                        <div class="flex flex-col mb-3">
                            <label for="detalle_recibo_2" class="text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">
                                Nro de operación / recibo
                            </label>
                            <input type="text" name="detalle_recibo" id="detalle_recibo_2"
                                value="{{ old('detalle_recibo') }}"
                                class="w-full rounded-lg border 
                                        {{ $errors->has('detalle_recibo') ? 'border-red-500' : 'border-gray-300 dark:border-gray-700' }}
                                        px-3 py-2.5 text-sm text-gray-800 dark:text-white/90 focus:ring-2 focus:ring-indigo-500
                                        {{ !old('metodo_pago') ? 'bg-gray-100 dark:bg-gray-800 cursor-not-allowed' : '' }}"
                                {{ !old('metodo_pago') ? 'data-blocked=true' : '' }}>
                            <div class="min-h-[20px]">
                                @error('detalle_recibo')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Monto -->
                        <div class="flex flex-col mb-3">
                            <label for="detalle_monto_2" class="text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">
                                Monto
                            </label>
                            <input type="text" name="detalle_monto" id="detalle_monto_2"
                                value="{{ old('detalle_monto') ? number_format((float) old('detalle_monto'), 2, '.', '') : '' }}"
                                placeholder="Restante: S/ {{ number_format($data['deuda']->monto_total - $data['detalle_existente']->monto, 2, '.', '') }}"
                                class="w-full rounded-lg border 
                                        {{ $errors->has('detalle_monto') ? 'border-red-500' : 'border-gray-300 dark:border-gray-700' }}
                                        px-3 py-2.5 text-sm text-gray-800 dark:text-white/90 focus:ring-2 focus:ring-indigo-500">
                            <div class="min-h-[20px]">
                                @error('detalle_monto')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Fecha -->
                        <div class="flex flex-col mb-3">
                            <label for="detalle_fecha_2" class="text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">
                                Fecha de Pago
                            </label>

                            <div class="flex items-center rounded-lg border 
                                    {{ $errors->has('detalle_fecha') ? 'border-red-500' : 'border-gray-300 dark:border-gray-700' }}
                                    focus-within:ring-2 focus-within:ring-indigo-500">

                                <input type="date" name="detalle_fecha" id="detalle_fecha_2"
                                    value="{{ old('detalle_fecha') }}" 
                                    class="w-full px-3 py-2.5 text-sm text-gray-800 dark:text-white/90 
                                            focus:outline-none rounded-l-lg bg-white dark:bg-gray-800">

                                <span class="px-3 py-2.5 bg-gray-100 dark:bg-gray-800 border-l border-gray-300 dark:border-gray-700 
                                                flex items-center justify-center rounded-r-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 dark:text-gray-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </span>
                            </div>

                            <div class="min-h-[20px]">
                                @error('detalle_fecha')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Voucher (solo para transferencia/yape) -->
                        <div class="flex-col mb-3 {{ in_array(old('metodo_pago'), ['transferencia', 'yape', 'plin']) || $errors->has('voucher_path') ? '' : 'hidden' }}" id="voucher_group_2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">
                                Constancia de Pago
                                <span class="text-gray-500 text-xs">(JPG, PNG, PDF)</span>
                            </label>

                            <div
                                class="flex items-center rounded-lg border 
                                {{ $errors->has('voucher_path') ? 'border-red-500' : 'border-gray-300 dark:border-gray-700' }}
                                bg-white dark:bg-gray-900">
                                <!-- Input oculto -->
                                <input type="file" name="voucher_path" id="voucher_path_2" class="hidden"
                                    accept=".jpg,.jpeg,.png,.pdf">

                                <!-- Botón con ícono de archivo -->
                                <button type="button" id="btnUploadVoucher_2"
                                    class="w-full flex items-center justify-center gap-2 px-3 py-2.5 text-sm font-medium text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 dark:hover:bg-gray-800 rounded-lg transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    <span id="voucher_label_2" class="text-gray-500">Seleccionar archivo</span>
                                </button>
                            </div>

                            <div class="min-h-[20px]">
                                @error('voucher_path')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>

@endsection

@section('custom-js')
    <script src="{{ asset('js/pago-completar.js') }}?v={{ time() }}"></script>
@endsection
