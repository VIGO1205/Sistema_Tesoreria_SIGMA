@extends('layouts.pasarela')

@section('title', 'Pagar con ' . ucfirst($metodo))

@section('styles')
<style>
    .metodo-logo {
        text-align: center;
        padding: 2rem;
        background: @if($metodo == 'yape') #722282 @else #00C1A2 @endif;
        color: white;
        border-radius: 15px 15px 0 0;
        margin: -2rem -2rem 2rem -2rem;
    }

    .metodo-logo i {
        font-size: 4rem;
        margin-bottom: 1rem;
    }

    .simulacion-box {
        background: #f8f9fa;
        border: 2px dashed #dee2e6;
        border-radius: 10px;
        padding: 2rem;
        text-align: center;
        margin: 1.5rem 0;
    }

    .simulacion-box.activo {
        background: #d4edda;
        border-color: #28a745;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    .phone-input {
        font-size: 1.5rem;
        text-align: center;
        letter-spacing: 2px;
        font-weight: 600;
    }

    .step-indicator {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2rem;
    }

    .step {
        flex: 1;
        text-align: center;
        padding: 1rem;
        background: #e9ecef;
        position: relative;
    }

    .step.active {
        background: var(--secondary-color);
        color: white;
    }

    .step.completed {
        background: var(--success-color);
        color: white;
    }
</style>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-body">
                <div class="metodo-logo">
                    <i class="@if($metodo == 'yape') fab fa-app-store @else fas fa-mobile-alt @endif"></i>
                    <h2>{{ strtoupper($metodo) }}</h2>
                    <p class="mb-0">Pago rápido y seguro desde tu celular</p>
                </div>

                <!-- Orden Info -->
                <div class="alert alert-info">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Orden:</strong> {{ $orden->codigo_orden }}<br>
                            <strong>Alumno:</strong> {{ $orden->alumno->nombre }} {{ $orden->alumno->apellido }}
                        </div>
                        <div class="text-end">
                            <h3 class="mb-0">S/ {{ number_format($orden->monto_total, 2) }}</h3>
                        </div>
                    </div>
                </div>

                <!-- Step Indicator -->
                <div class="step-indicator">
                    <div class="step active">
                        <strong>1</strong><br>Ingresa los datos
                    </div>
                    <div class="step">
                        <strong>2</strong><br>Confirma el pago
                    </div>
                    <div class="step">
                        <strong>3</strong><br>Comprobante
                    </div>
                </div>

                <!-- Form -->
                <form action="{{ route('pasarela.procesar', $orden->codigo_orden) }}" method="POST" enctype="multipart/form-data" id="pagoForm">
                    @csrf
                    <input type="hidden" name="metodo_pago" value="{{ $metodo }}">

                    <!-- Paso 1: Número de celular y monto -->
                    <div id="paso1">
                        <h5><i class="fas fa-mobile-alt me-2"></i>Paso 1: Datos del pago</h5>
                        
                        <div class="mb-4">
                            <label for="celular" class="form-label">Número de celular registrado en {{ strtoupper($metodo) }}</label>
                            <input 
                                type="text" 
                                class="form-control phone-input" 
                                id="celular" 
                                name="celular" 
                                placeholder="999 999 999"
                                maxlength="9"
                                pattern="[9][0-9]{8}"
                                required
                            >
                            <div class="form-text">Debe empezar con 9 y tener 9 dígitos</div>
                        </div>

                        <div class="mb-4">
                            <label for="monto_pago" class="form-label">Monto a Pagar</label>
                            <div class="input-group">
                                <span class="input-group-text">S/</span>
                                <input 
                                    type="number" 
                                    class="form-control form-control-lg text-end" 
                                    id="monto_pago" 
                                    name="monto_pago" 
                                    placeholder="0.00"
                                    step="0.01"
                                    min="1"
                                    max="{{ $orden->monto_total }}"
                                    required
                                    style="font-size: 1.5rem; font-weight: 600;"
                                >
                            </div>
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Monto total de la orden: <strong>S/ {{ number_format($orden->monto_total, 2) }}</strong>
                                <br>Puedes pagar el monto total o una parte.
                            </div>
                        </div>

                        <button type="button" class="btn btn-primary btn-lg w-100" onclick="validarYContinuar()">
                            <i class="fas fa-paper-plane me-2"></i>Enviar Solicitud de Pago
                        </button>
                    </div>

                    <!-- Paso 2: Simulación de envío -->
                    <div id="paso2" style="display: none;">
                        <div class="simulacion-box activo">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <h4>¡Solicitud Enviada!</h4>
                            <p>Hemos enviado una solicitud de pago de <strong>S/ <span id="montoMostrar">0.00</span></strong> a tu app de {{ strtoupper($metodo) }}.</p>
                            <p>Por favor, <strong>abre tu app y confirma el pago</strong>.</p>
                        </div>
                        <button type="submit" class="btn btn-success btn-lg w-100">
                            <i class="fas fa-check-circle me-2"></i>Confirmar Pago y Generar Comprobante
                        </button>
                    </div>

                    <a href="{{ route('pasarela.orden', $orden->codigo_orden) }}" class="btn btn-outline-secondary mt-3 w-100">
                        <i class="fas fa-arrow-left me-2"></i>Volver a Métodos de Pago
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function validarYContinuar() {
        const celular = document.getElementById('celular').value;
        const monto = parseFloat(document.getElementById('monto_pago').value);
        const montoMax = {{ $orden->monto_total }};

        if (celular.length !== 9 || !celular.startsWith('9')) {
            alert('Por favor, ingresa un número de celular válido');
            return;
        }

        if (!monto || monto <= 0) {
            alert('Por favor, ingresa un monto válido');
            return;
        }

        if (monto > montoMax) {
            alert(`El monto no puede ser mayor a S/ ${montoMax.toFixed(2)}`);
            return;
        }

        // Mostrar monto en el paso 2
        document.getElementById('montoMostrar').textContent = monto.toFixed(2);
        
        // Cambiar de paso
        document.getElementById('paso1').style.display = 'none';
        document.getElementById('paso2').style.display = 'block';
        document.querySelectorAll('.step')[1].classList.add('active');
        document.querySelectorAll('.step')[0].classList.add('completed');
    }

    // Solo números en celular
    document.getElementById('celular').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    // Validar monto en tiempo real
    document.getElementById('monto_pago').addEventListener('input', function(e) {
        const monto = parseFloat(this.value);
        const montoMax = {{ $orden->monto_total }};
        
        if (monto > montoMax) {
            this.value = montoMax.toFixed(2);
        }
        
        if (monto < 0) {
            this.value = '';
        }
    });
</script>
@endsection
