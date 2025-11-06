<div
    class="overflow-hidden rounded-2xl border border-gray-200 bg-white px-4 pb-3 pt-4 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6">

    <div class="flex flex-col gap-2 mb-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                {{ $titulo }}
            </h3>
        </div>

        <div class="flex items-center gap-3">
            <button
                class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
                <svg class="stroke-current fill-white dark:fill-gray-800" width="20" height="20" viewBox="0 0 20 20"
                    fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2.29004 5.90393H17.7067" stroke="" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path d="M17.7075 14.0961H2.29085" stroke="" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path
                        d="M12.0826 3.33331C13.5024 3.33331 14.6534 4.48431 14.6534 5.90414C14.6534 7.32398 13.5024 8.47498 12.0826 8.47498C10.6627 8.47498 9.51172 7.32398 9.51172 5.90415C9.51172 4.48432 10.6627 3.33331 12.0826 3.33331Z"
                        fill="" stroke="" stroke-width="1.5" />
                    <path
                        d="M7.91745 11.525C6.49762 11.525 5.34662 12.676 5.34662 14.0959C5.34661 15.5157 6.49762 16.6667 7.91745 16.6667C9.33728 16.6667 10.4883 15.5157 10.4883 14.0959C10.4883 12.676 9.33728 11.525 7.91745 11.525Z"
                        fill="" stroke="" stroke-width="1.5" />
                </svg>
                Filtros
            </button>

            <button
                class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
                Ver todo
            </button>

            <!-- Toggle IA automática -->
            <div class="flex items-center gap-2">
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="activarIA" class="sr-only peer" {{ $ia_activa ? 'checked' : '' }}>
                    <div class="relative w-11 h-6 bg-gray-200 peer-checked:bg-green-600 dark:bg-gray-700 dark:peer-checked:bg-green-500 rounded-full peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 transition-colors duration-200"></div>
                    <span class="ms-3 text-sm font-medium text-gray-700 dark:text-gray-300">IA automática</span>
                </label>
            </div>
        </div>
    </div>

    <div class="w-full overflow-x-auto text-[0.9rem]">
        <div class="flex items-center justify-between pb-4"> 
            <div class="flex items-center gap-3">
                <span class="text-gray-500 dark:text-gray-300">Viendo</span>
                <div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent">
                    <select
                        class="select-entries dark:bg-dark-900 h-9 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none py-2 pl-3 pr-8 text-sm shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800 text-gray-500 inline"
                        @click="isOptionSelected = true" @change="perPage = $event.target.value">
                        <option value="10" @if ($showing == 10) selected @endif>10</option>
                        <option value="8" @if ($showing == 8) selected @endif>8</option>
                        <option value="5" @if ($showing == 5) selected @endif>5</option>
                    </select>
                    <span class="absolute right-2 top-1/2 z-30 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                        <svg class="stroke-current" width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M3.8335 5.9165L8.00016 10.0832L12.1668 5.9165" stroke="" stroke-width="1.2"
                                stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </span>
                </div>
                <span class="text-gray-500 dark:text-gray-300">entradas</span>
            </div>

            <div class="relative">
                <span class="pointer-events-none absolute top-1/2 left-4 -translate-y-1/2">
                    <svg class="fill-gray-500 dark:fill-gray-400" width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z"
                            fill=""></path>
                    </svg>
                </span>
                <input type="text" placeholder="Buscar..."
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-[42px] w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pr-4 pl-[42px] text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden xl:w-[300px] dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
            </div>
        </div>

        <table class="min-w-full">
            <thead>
                <tr class="border-gray-100 border-y dark:border-gray-800">
                    @foreach ($columnas as $columna)
                        <th class="py-3 text-left">
                            <div class="flex items-center">
                                <p class="font-medium text-gray-900 text-theme-xs dark:text-gray-300">{{ $columna }}</p>
                            </div>
                        </th>
                    @endforeach

                    <th class="py-3 text-center">
                        <p class="font-medium text-gray-900 text-theme-xs dark:text-gray-300">Acción</p>
                    </th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach($filas as $fila)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                        @for($i = 0; $i < count($columnas); $i++)
                            <td class="py-3 px-2">
                                <p data-order="{{ $i }}" class="row{{ $fila[0] }} text-gray-600 text-theme-sm dark:text-gray-400">
                                    {{ $fila[$i] }}
                                </p>
                            </td>
                        @endfor

                        <!-- Acciones -->
                        <td class="py-3 px-2 text-center">
                            <a href="{{ route('validacion_pago_validar', $fila[0]) }}" 
                                class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-blue-500 dark:hover:bg-blue-600 transition-colors">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Ver Detalle
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if(count($filas) == 0)
            <div class="text-center py-8">
                <p class="text-gray-500 dark:text-gray-400">No hay pagos para mostrar</p>
            </div>
        @endif
    </div> 
</div>

<script>
    document.getElementById('activarIA').addEventListener('change', function() {
        const toggle = this;
        
        toggle.disabled = true;
        
        fetch('{{ route("validacion_pago_toggle_ia") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json', 
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ ia_activa: toggle.checked })
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                toggle.checked = !toggle.checked;
                alert('Error al cambiar el estado de la IA');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toggle.checked = !toggle.checked;
            alert('Error de conexión');
        })
        .finally(() => {
            toggle.disabled = false;
        });
    });
</script>