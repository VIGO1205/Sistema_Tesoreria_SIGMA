@extends('layouts.pasarela')

@section('title', 'Comprobante de Pago')

@section('styles')
<style>
    .comprobante-container {
        background: white;
        border-radius: 15px;
        overflow: hidden;
    }

    .comprobante-header {
        background: linear-gradient(135deg, #27ae60, #229954);
        color: white;
        padding: 3rem 2rem;
        text-align: center;
    }

    .checkmark {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: white;
        color: #27ae60;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        margin-bottom: 1rem;
        animation: scaleIn 0.5s ease-out;
    }

    @keyframes scaleIn {
        from { transform: scale(0); }
        to { transform: scale(1); }
    }

    .numero-operacion {
        font-size: 2rem;
        font-weight: 700;
        letter-spacing: 3px;
        background: rgba(255, 255, 255, 0.2);
        padding: 1rem;
        border-radius: 10px;
        margin-top: 1rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
        margin: 2rem 0;
    }

    .info-box {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 10px;
        border-left: 4px solid var(--secondary-color);
    }

    .info-box .label {
        font-size: 0.85rem;
        color: #666;
        margin-bottom: 0.5rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .info-box .value {
        font-size: 1.1rem;
        color: #2c3e50;
        font-weight: 700;
    }

    .conceptos-detalle {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 10px;
    }

    .concepto-item {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px dashed #dee2e6;
    }

    .concepto-item:last-child {
        border-bottom: none;
    }

    .total-final {
        background: var(--primary-color);
        color: white;
        padding: 1.5rem;
        border-radius: 10px;
        margin-top: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .estado-badge {
        display: inline-block;
        padding: 0.5rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 1rem;
    }

    .estado-pendiente {
        background: #fff3cd;
        color: #856404;
    }

    .estado-validado {
        background: #d4edda;
        color: #155724;
    }

    .accion-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    @media print {
        body * {
            visibility: hidden;
        }
        .comprobante-container, .comprobante-container * {
            visibility: visible;
        }
        .comprobante-container {
            position: absolute;
            left: 0;
            top: 0;
        }
        .accion-buttons {
            display: none !important;
        }
    }
</style>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-10">
        <div class="comprobante-container card">
            <!-- Header de Éxito -->
            <div class="comprobante-header">
                <div class="checkmark">
                    <i class="fas fa-check"></i>
                </div>
                <h1 class="mb-3">¡Pago Registrado Exitosamente!</h1>
                <p class="mb-0">Tu pago está siendo validado por nuestro equipo</p>
                <div class="numero-operacion">
                    N° {{ $pago->detallesPago->first()->nro_recibo ?? 'N/A' }}
                </div>
            </div>

            <div class="card-body p-4">
                <!-- Información Principal -->
                <div class="info-grid">
                    <div class="info-box">
                        <div class="label"><i class="fas fa-calendar me-1"></i>Fecha de Pago</div>
                        <div class="value">{{ $pago->fecha_pago->format('d/m/Y H:i') }}</div>
                    </div>

                    <div class="info-box">
                        <div class="label"><i class="fas fa-barcode me-1"></i>Código de Orden</div>
                        <div class="value">{{ $orden->codigo_orden }}</div>
                    </div>

                    <div class="info-box">
                        <div class="label"><i class="fas fa-wallet me-1"></i>Método de Pago</div>
                        <div class="value">{{ ucfirst($pago->detallesPago->first()->metodo_pago ?? $pago->tipo_pago) }}</div>
                    </div>

                    <div class="info-box">
                        <div class="label"><i class="fas fa-flag me-1"></i>Estado</div>
                        <div class="value">
                            <span class="estado-badge {{ $pago->estado == '1' ? 'estado-pendiente' : 'estado-validado' }}">
                                {{ $pago->estado == '1' ? 'Pendiente de Validación' : 'Validado' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Información del Estudiante -->
                <div class="mt-4">
                    <h5><i class="fas fa-user-graduate me-2"></i>Datos del Estudiante</h5>
                    <div class="info-grid">
                        <div class="info-box">
                            <div class="label">Nombre Completo</div>
                            <div class="value">{{ $orden->alumno->nombre }} {{ $orden->alumno->apellido }}</div>
                        </div>

                        <div class="info-box">
                            <div class="label">DNI</div>
                            <div class="value">{{ $orden->alumno->dni }}</div>
                        </div>

                        @if($orden->matricula)
                        <div class="info-box">
                            <div class="label">Grado y Sección</div>
                            <div class="value">
                                {{ $orden->matricula->grado->nombre ?? 'N/A' }} - Sección {{ $orden->matricula->seccion->nombre ?? 'N/A' }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Conceptos Pagados -->
                <div class="mt-4">
                    <h5><i class="fas fa-list-ul me-2"></i>Detalle de Conceptos</h5>
                    <div class="conceptos-detalle">
                        @foreach($pago->distribuciones as $dist)
                            <div class="concepto-item">
                                <span>{{ $dist->deuda->conceptoPago->nombre }}</span>
                                <strong>S/ {{ number_format($dist->monto_aplicado, 2) }}</strong>
                            </div>
                        @endforeach

                        <div class="total-final">
                            <span style="font-size: 1.2rem;">TOTAL PAGADO</span>
                            <span style="font-size: 2rem; font-weight: 700;">S/ {{ number_format($pago->monto, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Información Adicional -->
                @if($pago->observaciones)
                <div class="alert alert-info mt-4">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Observaciones:</strong> {{ $pago->observaciones }}
                </div>
                @endif

                <!-- Alert de Validación -->
                <div class="alert alert-warning mt-4">
                    <h6><i class="fas fa-clock me-2"></i>Proceso de Validación</h6>
                    <p class="mb-0">
                        Tu pago está siendo revisado por nuestro equipo de tesorería. 
                        Recibirás una confirmación por correo electrónico una vez validado. 
                        Este proceso puede tomar entre <strong>24 a 48 horas hábiles</strong>.
                    </p>
                </div>

                <!-- Botones de Acción -->
                <div class="accion-buttons">
                    <a href="{{ route('pasarela.comprobante.pdf', [$orden->codigo_orden, $pago->id_pago]) }}" 
                       class="btn btn-primary btn-lg flex-fill">
                        <i class="fas fa-file-pdf me-2"></i>Comprobante PDF
                    </a>
                    <button onclick="window.print()" class="btn btn-outline-secondary btn-lg flex-fill">
                        <i class="fas fa-print me-2"></i>Imprimir
                    </button>
                </div>

                <!-- Botones de Descarga de Voucher -->
                <div class="alert alert-info mt-4">
                    <h6><i class="fas fa-receipt me-2"></i>Descargar Voucher de Pago</h6>
                    <p class="mb-3">Descarga el voucher simulado en el formato que prefieras:</p>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('pasarela.voucher.pdf', $pago->id_pago) }}" 
                           class="btn btn-success" target="_blank">
                            <i class="fas fa-file-pdf me-2"></i>Voucher PDF
                        </a>
                        <a href="{{ route('pasarela.voucher.html', $pago->id_pago) }}" 
                           class="btn btn-info" target="_blank">
                            <i class="fas fa-image me-2"></i>Voucher PNG/JPG
                        </a>
                        <button onclick="abrirInstruccionesCaptura()" class="btn btn-outline-info">
                            <i class="fas fa-question-circle me-2"></i>¿Cómo capturar como imagen?
                        </button>
                    </div>
                </div>

                <div class="text-center mt-3">
                    <a href="{{ route('pasarela.index') }}" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-home me-2"></i>Realizar Otro Pago
                    </a>
                </div>

                <!-- Información de Contacto -->
                <div class="text-center mt-4 pt-4 border-top">
                    <h6>¿Necesitas ayuda?</h6>
                    <p class="text-muted mb-0">
                        <i class="fas fa-phone me-1"></i>(01) 234-5678 | 
                        <i class="fas fa-envelope me-1 ms-2"></i>tesoreria@sigma.edu.pe
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function abrirInstruccionesCaptura() {
    Swal.fire({
        title: '📸 Capturar Voucher como Imagen',
        html: `
            <div class="text-start">
                <h6>En Windows:</h6>
                <ol>
                    <li>Haz clic en "Voucher PNG/JPG"</li>
                    <li>Presiona <kbd>Windows</kbd> + <kbd>Shift</kbd> + <kbd>S</kbd></li>
                    <li>Selecciona el área del voucher</li>
                    <li>Guarda desde el portapapeles</li>
                </ol>
                
                <h6 class="mt-3">En Android/iOS:</h6>
                <ol>
                    <li>Abre el voucher en tu celular</li>
                    <li>Toma screenshot (botón power + volumen abajo)</li>
                    <li>Recorta la imagen si es necesario</li>
                </ol>

                <h6 class="mt-3">Extensiones de Navegador:</h6>
                <ul>
                    <li>Chrome: "GoFullPage" o "Awesome Screenshot"</li>
                    <li>Firefox: "Nimbus Screenshot"</li>
                </ul>

                <div class="alert alert-success mt-3 mb-0">
                    <strong>Recomendación:</strong> Descarga el PDF si necesitas formato estándar. 
                    Para imágenes, usa las herramientas de captura de tu sistema operativo.
                </div>
            </div>
        `,
        icon: 'info',
        confirmButtonText: 'Entendido',
        width: '600px'
    });
}
</script>
@endsection