
@extends('base.administrativo.blank')

@section('titulo')
  Crear Familiar
@endsection

@section('contenido')
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header con título y botones -->
    <div class="mb-8">
      <div class="flex justify-between items-start">
        <div>
          <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Nuevo Familiar</h1>
          <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Registra un nuevo familiar en el sistema</p>
        </div>
        <div class="flex gap-3">
          <button type="submit" form="form" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium text-sm transition-colors">
            Crear Familiar
          </button>
          <a href="{{ $data['return'] ?? route('familiar.index') }}" class="px-6 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 font-medium text-sm transition-colors">
            Cancelar
          </a>
        </div>
      </div>
    </div>

    @if ($errors->any())
      <div class="mb-6 p-4 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
        <div class="flex items-start">
          <svg class="w-5 h-5 text-red-600 dark:text-red-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
          </svg>
          <div class="ml-3">
            <h3 class="text-sm font-medium text-red-800 dark:text-red-300">Hay algunos errores con tu envío</h3>
            <ul class="mt-2 text-sm text-red-700 dark:text-red-400 list-disc list-inside">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
    @endif

    <form method="POST" id="form" action="{{ route('familiar_createNewEntry') }}" enctype="multipart/form-data">
      @method('PUT')
      @csrf

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Columna Izquierda: Fotografía -->
        <div class="lg:col-span-1">
          <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Fotografía del Familiar</h2>

            <div class="space-y-4">
              <input type="file" id="fotografia" name="fotografia" accept="image/*" class="hidden">

              <div id="dropZone" class="relative border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-8 text-center hover:border-blue-500 dark:hover:border-blue-400 transition-all cursor-pointer bg-gray-50 dark:bg-gray-900/50">
                <div id="uploadPrompt" class="space-y-3">
                  <div class="mx-auto w-16 h-16 bg-blue-50 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                  </div>
                  <div>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Arrastra o haz clic para subir foto</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">para subir foto</p>
                  </div>
                </div>

                <div id="imagePreview" class="hidden space-y-4">
                  <img id="preview" src="" alt="Preview" class="max-w-full h-auto rounded-lg mx-auto shadow-md">
                  <button type="button" id="removeBtn" class="px-4 py-2 bg-red-500 text-white text-sm rounded-lg hover:bg-red-600 transition-colors">
                    Quitar foto
                  </button>
                </div>
              </div>

              <button type="button" id="selectPhotoBtn" class="w-full px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium text-sm transition-colors flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Seleccionar foto
              </button>

              <div class="text-xs text-gray-500 dark:text-gray-400 space-y-1">
                <p><span class="font-medium">Formatos permitidos:</span> JPG, PNG</p>
                <p><span class="font-medium">Tamaño recomendado:</span> 240 x 288 px</p>
                <p><span class="font-medium">Tamaño máximo:</span> 2MB</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Columna Derecha: Datos -->
        <div class="lg:col-span-2 space-y-6">

          <!-- Sección: Datos de Autenticación -->
          <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Datos de Autenticación</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="space-y-2">
                <label for="nombre_usuario" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                  Nombre de Usuario <span class="text-red-500">*</span>
                </label>
                <input
                  type="text"
                  id="nombre_usuario"
                  name="nombre_usuario"
                  value="{{ old('nombre_usuario') }}"
                  placeholder="Ingrese el nombre de usuario"
                  required
                  class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                >
              </div>

              <div class="space-y-2">
                <label for="tipo_usuario" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                  Tipo de Usuario <span class="text-red-500">*</span>
                </label>
                <select
                  id="tipo_usuario"
                  name="tipo_usuario"
                  required
                  class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                >
                  <option value="">Buscar tipo de usuario...</option>
                  @foreach($data['tiposUsuario'] as $key => $tipo)
                    <option value="{{ $key }}" {{ old('tipo_usuario') == $key ? 'selected' : '' }}>
                      {{ $tipo }}
                    </option>
                  @endforeach
                </select>
              </div>

              <div class="space-y-2">
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                  Contraseña <span class="text-red-500">*</span>
                </label>
                <input
                  type="password"
                  id="password"
                  name="password"
                  placeholder="••••••••"
                  required
                  class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                >
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Mínimo 8 caracteres</p>
              </div>

              <div class="space-y-2">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                  Confirmar Contraseña <span class="text-red-500">*</span>
                </label>
                <input
                  type="password"
                  id="password_confirmation"
                  name="password_confirmation"
                  placeholder="••••••••"
                  required
                  class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                >
              </div>
            </div>
          </div>

          <!-- Sección: Datos Personales -->
          <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Datos Personales</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              @include('components.forms.string', [
                'label' => 'DNI',
                'name' => 'dni',
                'error' => $errors->first('dni') ?? false,
                'value' => old('dni')
              ])

              @include('components.forms.string', [
                'label' => 'Apellido Paterno',
                'name' => 'apellido_paterno',
                'error' => $errors->first('apellido_paterno') ?? false,
                'value' => old('apellido_paterno')
              ])

              @include('components.forms.string', [
                'label' => 'Apellido Materno',
                'name' => 'apellido_materno',
                'error' => $errors->first('apellido_materno') ?? false,
                'value' => old('apellido_materno')
              ])

              @include('components.forms.string', [
                'label' => 'Primer Nombre',
                'name' => 'primer_nombre',
                'error' => $errors->first('primer_nombre') ?? false,
                'value' => old('primer_nombre')
              ])

              @include('components.forms.string', [
                'label' => 'Otros Nombres',
                'name' => 'otros_nombres',
                'error' => $errors->first('otros_nombres') ?? false,
                'value' => old('otros_nombres')
              ])

              @include('components.forms.string', [
                'label' => 'Número de Contacto',
                'name' => 'numero_contacto',
                'error' => $errors->first('numero_contacto') ?? false,
                'value' => old('numero_contacto')
              ])

              <div class="md:col-span-2">
                @include('components.forms.string', [
                  'label' => 'Correo Electrónico',
                  'name' => 'correo_electronico',
                  'error' => $errors->first('correo_electronico') ?? false,
                  'value' => old('correo_electronico')
                ])
              </div>
            </div>
          </div>

        </div>
      </div>
    </form>
  </div>

  <script>
    // Elementos del DOM
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fotografia');
    const uploadPrompt = document.getElementById('uploadPrompt');
    const imagePreview = document.getElementById('imagePreview');
    const preview = document.getElementById('preview');
    const removeBtn = document.getElementById('removeBtn');
    const selectPhotoBtn = document.getElementById('selectPhotoBtn');

    // Click en el botón "Seleccionar foto"
    selectPhotoBtn.addEventListener('click', () => {
      fileInput.click();
    });

    // Click en la zona de drop para abrir selector de archivo
    dropZone.addEventListener('click', () => {
      fileInput.click();
    });

    // Cambio en el input de archivo
    fileInput.addEventListener('change', (e) => {
      const file = e.target.files[0];
      if (file) {
        previewImage(file);
      }
    });

    // Prevenir comportamiento por defecto en drag
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
      dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
      e.preventDefault();
      e.stopPropagation();
    }

    // Highlight en drag over
    ['dragenter', 'dragover'].forEach(eventName => {
      dropZone.addEventListener(eventName, () => {
        dropZone.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
      });
    });

    ['dragleave', 'drop'].forEach(eventName => {
      dropZone.addEventListener(eventName, () => {
        dropZone.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
      });
    });

    // Handle drop
    dropZone.addEventListener('drop', (e) => {
      const dt = e.dataTransfer;
      const files = dt.files;
      if (files.length > 0) {
        fileInput.files = files;
        previewImage(files[0]);
      }
    });

    // Previsualizar imagen
    function previewImage(file) {
      if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = (e) => {
          preview.src = e.target.result;
          uploadPrompt.classList.add('hidden');
          imagePreview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
      }
    }

    // Quitar foto
    removeBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      fileInput.value = '';
      preview.src = '';
      imagePreview.classList.add('hidden');
      uploadPrompt.classList.remove('hidden');
    });
  </script>
@endsection
