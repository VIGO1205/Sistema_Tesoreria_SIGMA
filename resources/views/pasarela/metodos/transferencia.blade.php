@extends('layouts.pasarela')

@section('title', 'Pagar con Transferencia Bancaria')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-10">
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-university me-2"></i>Transferencia Bancaria</h3>
            </div>
            <div class="card-body">
                <!-- Orden Info -->
                <div class="alert alert-info">
                    <div class="d-flex justify-content-between">
                        <div><strong>Orden:</strong> {{ $orden->codigo_orden }}</div>
                        <div><strong>Monto:</strong> S/ {{ number_format($orden->monto_total, 2) }}</div>
                    </div>
                </div>

                <!-- Datos de la cuenta del colegio -->
                <div class="card mb-4" style="background: #f8f9fa;">
                    <div class="card-body">
                        <h5><i class="fas fa-building me-2"></i>Datos de Cuenta del Colegio</h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Banco:</strong> {{ $cuenta_colegio['banco'] }}</p>
                                <p><strong>Titular:</strong> {{ $cuenta_colegio['titular'] }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Número de Cuenta:</strong> <code>{{ $cuenta_colegio['numero'] }}</code></p>
                                <p><strong>CCI:</strong> <code>{{ $cuenta_colegio['cci'] }}</code></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Formulario -->
                <form action="{{ route('pasarela.procesar', $orden->codigo_orden) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="metodo_pago" value="transferencia">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Banco de Origen *</label>
                            <select name="banco" class="form-select" required>
                                <option value="">Selecciona tu banco</option>
                                @foreach($bancos as $codigo => $nombre)
                                    <option value="{{ $codigo }}">{{ $nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Número de Cuenta Origen *</label>
                            <input type="text" name="numero_cuenta" class="form-control" placeholder="Ej: 191-2345678-0-95" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Voucher de Transferencia *</label>
                        <input type="file" name="voucher" class="form-control" accept="image/*" required>
                        <div class="form-text">Sube una foto clara del voucher (JPG, PNG, máx 5MB)</div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-check-circle me-2"></i>Registrar Transferencia
                        </button>
                        <a href="{{ route('pasarela.orden', $orden->codigo_orden) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Volver
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
