<div class="grid grid-cols-2 gap-8">
    @include('components.forms.string', [
        'label' => 'Nombre de Usuario',
        'error' => $errors->first(Str::snake('Nombre de Usuario')) ?? false,
        'value' => old(Str::snake('Nombre de Usuario')) ?? $data['default']['nombre_de_usuario'] ?? '',
    ])

    @include('components.forms.password', [
        'label' => 'Contraseña',
        'error' => $errors->first(Str::snake('Contraseña')) ?? false,
        'value' => old(Str::snake('Contraseña')) ?? $data['default']['contraseña'] ?? '',
    ])
</div>