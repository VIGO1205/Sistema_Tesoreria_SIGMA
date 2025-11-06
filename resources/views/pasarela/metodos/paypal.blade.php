@extends('layouts.pasarela')

@section('title', 'Pagar con PayPal')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-body text-center">
                <i class="fab fa-paypal fa-5x mb-3" style="color: #003087;"></i>
                <h2>PayPal</h2>
                <p class="text-muted">La forma segura de pagar online</p>

                <!-- Orden Info -->
                <div class="alert alert-info">
                    <div><strong>Orden:</strong> {{ $orden->codigo_orden }}</div>
                    <h3 class="mt-2">S/ {{ number_format($orden->monto_total, 2) }}</h3>
                </div>

                <!-- Formulario -->
                <form action="{{ route('pasarela.procesar', $orden->codigo_orden) }}" method="POST" id="paypalForm">
                    @csrf
                    <input type="hidden" name="metodo_pago" value="paypal">

                    <div class="mb-3 text-start">
                        <label class="form-label">Correo Electrónico de PayPal *</label>
                        <input 
                            type="email" 
                            name="email_paypal" 
                            class="form-control" 
                            placeholder="tu@email.com"
                            required
                        >
                    </div>

                    <div class="mb-4 text-start">
                        <label class="form-label">Contraseña de PayPal *</label>
                        <input 
                            type="password" 
                            name="password_paypal" 
                            class="form-control" 
                            placeholder="••••••••"
                            required
                        >
                        <div class="form-text">
                            <i class="fas fa-lock me-1"></i>Tus datos están protegidos y no serán almacenados
                        </div>
                    </div>

                    <!-- Simulación -->
                    <div id="procesando" style="display: none;" class="my-4">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2">Conectando con PayPal...</p>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-lg" style="background: #0070BA; color: white;">
                            <i class="fab fa-paypal me-2"></i>Pagar con PayPal
                        </button>
                        <a href="{{ route('pasarela.orden', $orden->codigo_orden) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Volver
                        </a>
                    </div>

                    <div class="mt-4">
                        <small class="text-muted">
                            <i class="fas fa-shield-alt text-success me-1"></i>
                            Protección del comprador de PayPal
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
    document.getElementById('paypalForm').addEventListener('submit', function() {
        document.getElementById('procesando').style.display = 'block';
    });
</script>
@endsection
