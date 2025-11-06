<div>
  @if ($error)
    <p class="py-2 text-red-700 dark:text-red-200">{{ $error }}</p>
  @endif

  <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
    {{ $label }}
  </label>

  <div class="relative">
    <input
      type="date"
      name="{{ $label }}"
      id="fecha_pago" {{-- importante para JS --}}
      placeholder="Select date"
      value="{{ $value ?? '' }}"
      class="date-{{$label}} dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pr-11 pl-4 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @if($error) border-red-700 dark:border-red-200 @endif"
      onclick="this.showPicker()"
    />
    <span class="pointer-events-none absolute top-1/2 right-3 -translate-y-1/2 text-gray-500 dark:text-gray-400">
      <!-- ícono -->
    </span>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const inputFecha = document.getElementById("fecha_pago");

    if (inputFecha) {
      inputFecha.addEventListener("change", function () {
        const fecha = new Date(this.value);
        const anio = fecha.getFullYear();

        const campoPeriodo = document.getElementById("periodo");
        if (campoPeriodo && !isNaN(anio)) {
          campoPeriodo.value = anio;
        }
      });
    }
  });
</script>
