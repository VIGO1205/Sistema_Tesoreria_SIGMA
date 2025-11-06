@extends('layouts.pasarela')

@section('title', 'Detalles de Orden de Pago')

@section('styles')
<style>
    .orden-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 15px 15px 0 0;
        margin: -2rem -2rem 2rem -2rem;
    }

    .orden-header h2 {
        margin: 0;
        font-size: 1.8rem;
    }

    .orden-codigo {
        font-size: 2rem;
        font-weight: 700;
        letter-spacing: 2px;
        margin-top: 0.5rem;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #e0e0e0;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 600;
        color: #666;
    }

    .info-value {
        color: #2c3e50;
        font-weight: 600;
    }

    .monto-total {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 10px;
        margin: 1.5rem 0;
        text-align: center;
    }

    .monto-total .label {
        font-size: 1rem;
        color: #666;
        margin-bottom: 0.5rem;
    }

    .monto-total .valor {
        font-size: 3rem;
        font-weight: 700;
        color: var(--primary-color);
    }

    .metodo-pago-card {
        border: 2px solid #e0e0e0;
        border-radius: 15px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        height: 100%;
        text-decoration: none;
        display: block;
    }

    .metodo-pago-card:hover {
        border-color: var(--secondary-color);
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(52, 152, 219, 0.2);
    }

    .metodo-pago-card i {
        font-size: 3rem;
        margin-bottom: 1rem;
    }

    .metodo-pago-card h5 {
        color: #2c3e50;
        margin-bottom: 0.5rem;
        font-weight: 600;
    }

    .metodo-pago-card small {
        color: #666;
    }

    .metodo-yape i { color: #722282; }
    .metodo-plin i { color: #00C1A2; }
    .metodo-transferencia i { color: #0066cc; }
    .metodo-tarjeta i { color: #ff6600; }
    .metodo-paypal i { color: #003087; }

    .conceptos-list {
        list-style: none;
        padding: 0;
    }

    .conceptos-list li {
        padding: 0.75rem;
        background: #f8f9fa;
        margin-bottom: 0.5rem;
        border-radius: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .concepto-nombre {
        font-weight: 600;
    }

    .concepto-monto {
        color: var(--secondary-color);
        font-weight: 700;
        font-size: 1.1rem;
    }

    .vencimiento-alerta {
        background: #fff3cd;
        border-left: 4px solid #ffc107;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }

    .vencimiento-alerta.vencido {
        background: #f8d7da;
        border-left-color: #dc3545;
    }
</style>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-10">
        <!-- Orden Details Card -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="orden-header">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h2><i class="fas fa-file-invoice-dollar me-2"></i>Orden de Pago</h2>
                            <div class="orden-codigo">{{ $orden->codigo_orden }}</div>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-light text-dark" style="font-size: 1rem;">
                                @if($orden->estado == '0')
                                    <i class="fas fa-clock me-1"></i>Pendiente
                                @elseif($orden->estado == '1')
                                    <i class="fas fa-spinner me-1"></i>En Proceso
                                @elseif($orden->estado == '2')
                                    <i class="fas fa-check me-1"></i>Pagado
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Alert de vencimiento -->
                @php
                    $dias_restantes = \Carbon\Carbon::now()->diffInDays($orden->fecha_vencimiento, false);
                @endphp
                
                @if($dias_restantes < 0)
                    <div class="vencimiento-alerta vencido">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>¡Orden Vencida!</strong> Esta orden venció el {{ $orden->fecha_vencimiento->format('d/m/Y') }}.
                        Por favor, contacta con tesorería.
                    </div>
                @elseif($dias_restantes <= 3)
                    <div class="vencimiento-alerta">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>¡Atención!</strong> Esta orden vence en {{ $dias_restantes }} día{{ $dias_restantes != 1 ? 's' : '' }}
                        ({{ $orden->fecha_vencimiento->format('d/m/Y') }}).
                    </div>
                @endif

                <!-- Información del Estudiante -->
                <h5 class="mb-3"><i class="fas fa-user-graduate me-2"></i>Información del Estudiante</h5>
                <div class="info-row">
                    <span class="info-label">Estudiante:</span>
                    <span class="info-value">{{ $orden->alumno->primer_nombre }} {{ $orden->alumno->otros_nombres }} {{ $orden->alumno->apellido_paterno }} {{ $orden->alumno->apellido_materno }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">DNI:</span>
                    <span class="info-value">{{ $orden->alumno->dni }}</span>
                </div>
                @if($orden->matricula)
                <div class="info-row">
                    <span class="info-label">Grado:</span>
                    <span class="info-value">
                        {{ $orden->matricula->grado->nombre_grado ?? 'N/A' }} - Sección {{ $orden->matricula->nombreSeccion ?? 'N/A' }}
                    </span>
                </div>
                @endif
                <div class="info-row">
                    <span class="info-label">Fecha de Emisión:</span>
                    <span class="info-value">{{ $orden->fecha_orden_pago->format('d/m/Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Fecha de Vencimiento:</span>
                    <span class="info-value">{{ $orden->fecha_vencimiento->format('d/m/Y') }}</span>
                </div>

                <!-- Conceptos de Pago -->
                <h5 class="mt-4 mb-3"><i class="fas fa-list-ul me-2"></i>Conceptos a Pagar</h5>
                <ul class="conceptos-list">
                    @foreach($orden->detalles as $detalle)
                        <li>
                            <div>
                                <span class="concepto-nombre">{{ $detalle->conceptoPago->nombre ?? $detalle->deuda->conceptoPago->nombre }}</span>
                                @if($detalle->descripcion_ajuste)
                                    <br><small class="text-muted">{{ $detalle->descripcion_ajuste }}</small>
                                @endif
                            </div>
                            <span class="concepto-monto">S/ {{ number_format($detalle->monto_subtotal, 2) }}</span>
                        </li>
                    @endforeach
                </ul>

                <!-- Información de Pagos Realizados -->
                @if($montoPagado > 0)
                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Pagos Realizados:</strong> S/ {{ number_format($montoPagado, 2) }}
                </div>
                @endif

                <!-- Monto Total -->
                <div class="monto-total">
                    <div class="label">{{ $montoPagado > 0 ? 'SALDO PENDIENTE' : 'MONTO TOTAL A PAGAR' }}</div>
                    <div class="valor">S/ {{ number_format($saldoPendiente, 2) }}</div>
                    @if($montoPagado > 0)
                        <small class="text-muted d-block mt-2">
                            Monto original: S/ {{ number_format($orden->monto_total, 2) }}
                        </small>
                    @endif
                </div>

                @if($orden->observaciones)
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Observaciones:</strong> {{ $orden->observaciones }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Métodos de Pago -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-wallet me-2"></i>Selecciona tu Método de Pago</h3>
            </div>
            <div class="card-body">
                <p class="text-center mb-4">Elige cómo deseas realizar tu pago de forma segura</p>
                
                <div class="row g-3">
                    <!-- Yape -->
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="{{ route('pasarela.metodo', [$orden->codigo_orden, 'yape']) }}" class="metodo-pago-card metodo-yape">
                            <i class="fab fa-app-store"></i>
                            <h5>Yape</h5>
                            <small>Instantáneo</small>
                        </a>
                    </div>

                    <!-- Plin -->
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="{{ route('pasarela.metodo', [$orden->codigo_orden, 'plin']) }}" class="metodo-pago-card metodo-plin">
                            <i class="fas fa-mobile-alt"></i>
                            <h5>Plin</h5>
                            <small>Instantáneo</small>
                        </a>
                    </div>

                    <!-- Transferencia -->
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="{{ route('pasarela.metodo', [$orden->codigo_orden, 'transferencia']) }}" class="metodo-pago-card metodo-transferencia">
                            <i class="fas fa-university"></i>
                            <h5>Transferencia</h5>
                            <small>Bancaria</small>
                        </a>
                    </div>

                    <!-- Tarjeta -->
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="{{ route('pasarela.metodo', [$orden->codigo_orden, 'tarjeta']) }}" class="metodo-pago-card metodo-tarjeta">
                            <i class="fas fa-credit-card"></i>
                            <h5>Tarjeta</h5>
                            <small>Crédito/Débito</small>
                        </a>
                    </div>

                    <!-- PayPal -->
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="{{ route('pasarela.metodo', [$orden->codigo_orden, 'paypal']) }}" class="metodo-pago-card metodo-paypal">
                            <i class="fab fa-paypal"></i>
                            <h5>PayPal</h5>
                            <small>Internacional</small>
                        </a>
                    </div>

                    <!-- Volver -->
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="{{ route('pasarela.index') }}" class="metodo-pago-card" style="border-color: #6c757d;">
                            <i class="fas fa-arrow-left" style="color: #6c757d;"></i>
                            <h5>Volver</h5>
                            <small>Buscar otra orden</small>
                        </a>
                    </div>
                </div>

                <!-- Security Info -->
                <div class="text-center mt-4 pt-3 border-top">
                    <i class="fas fa-shield-alt text-success me-2"></i>
                    <small class="text-muted">Todos los pagos son procesados de forma segura y encriptada</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
