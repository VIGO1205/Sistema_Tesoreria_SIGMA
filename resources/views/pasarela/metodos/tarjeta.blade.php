@extends('layouts.pasarela')

@section('title', 'Pagar con Tarjeta')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-credit-card me-2"></i>Tarjeta de Crédito / Débito</h3>
            </div>
            <div class="card-body">
                <!-- Orden Info -->
                <div class="alert alert-info">
                    <div class="d-flex justify-content-between">
                        <div><strong>Orden:</strong> {{ $orden->codigo_orden }}</div>
                        <div><strong>Monto:</strong> S/ {{ number_format($orden->monto_total, 2) }}</div>
                    </div>
                </div>

                <!-- Iconos de tarjetas aceptadas -->
                <div class="text-center mb-4">
                    <i class="fab fa-cc-visa fa-3x mx-2" style="color: #1A1F71;"></i>
                    <i class="fab fa-cc-mastercard fa-3x mx-2" style="color: #EB001B;"></i>
                    <i class="fab fa-cc-amex fa-3x mx-2" style="color: #006FCF;"></i>
                </div>

                <!-- Formulario -->
                <form action="{{ route('pasarela.procesar', $orden->codigo_orden) }}" method="POST" id="tarjetaForm">
                    @csrf
                    <input type="hidden" name="metodo_pago" value="tarjeta">

                    <div class="mb-3">
                        <label class="form-label">Número de Tarjeta *</label>
                        <input 
                            type="text" 
                            name="numero_tarjeta" 
                            id="numero_tarjeta"
                            class="form-control form-control-lg text-center" 
                            placeholder="1234 5678 9012 3456"
                            maxlength="19"
                            style="font-size: 1.3rem; letter-spacing: 3px;"
                            required
                        >
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha de Vencimiento *</label>
                            <input 
                                type="text" 
                                name="fecha_vencimiento" 
                                id="fecha_vencimiento"
                                class="form-control" 
                                placeholder="MM/YY"
                                maxlength="5"
                                required
                            >
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">CVV *</label>
                            <input 
                                type="text" 
                                name="cvv" 
                                id="cvv"
                                class="form-control" 
                                placeholder="123"
                                maxlength="3"
                                required
                            >
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Nombre del Titular *</label>
                        <input 
                            type="text" 
                            name="nombre_titular" 
                            class="form-control" 
                            placeholder="Como aparece en la tarjeta"
                            style="text-transform: uppercase;"
                            required
                        >
                    </div>

                    <!-- Simulación de procesamiento -->
                    <div id="procesando" style="display: none;" class="text-center my-4">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2">Procesando pago seguro...</p>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-lock me-2"></i>Pagar S/ {{ number_format($orden->monto_total, 2) }}
                        </button>
                        <a href="{{ route('pasarela.orden', $orden->codigo_orden) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Volver
                        </a>
                    </div>

                    <div class="text-center mt-3">
                        <small class="text-muted">
                            <i class="fas fa-shield-alt text-success me-1"></i>
                            Transacción 100% segura y encriptada
                        </small>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Formatear número de tarjeta
    document.getElementById('numero_tarjeta').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\s/g, '').replace(/\D/g, '');
        let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
        e.target.value = formattedValue;
    });

    // Formatear fecha MM/YY
    document.getElementById('fecha_vencimiento').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length >= 2) {
            value = value.substring(0, 2) + '/' + value.substring(2, 4);
        }
        e.target.value = value;
    });

    // Solo números en CVV
    document.getElementById('cvv').addEventListener('input', function(e) {
        e.target.value = e.target.value.replace(/\D/g, '');
    });

    // Simular procesamiento
    document.getElementById('tarjetaForm').addEventListener('submit', function() {
        document.getElementById('procesando').style.display = 'block';
    });
</script>
@endsection
