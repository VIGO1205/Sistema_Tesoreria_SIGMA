@extends('base.administrativo.blank')

@section('titulo', 'Listado de Solicitudes de Traslado')

@section('extracss')
<style>
    .badge-pendiente {
        background-color: #ffc107;
        color: #000;
    }
    .badge-aprobado {
        background-color: #28a745;
        color: #fff;
    }
    .badge-rechazado {
        background-color: #dc3545;
        color: #fff;
    }
    .badge-completado {
        background-color: #6c757d;
        color: #fff;
    }
</style>
@endsection

@section('contenido')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                <i class="fas fa-list me-2"></i>Listado de Solicitudes de Traslado
            </h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="tablaSolicitudes">
                    <thead class="table-dark">
                        <tr>
                            <th>Código</th>
                            <th>Alumno</th>
                            <th>Colegio Destino</th>
                            <th>Fecha Traslado</th>
                            <th>Fecha Solicitud</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($solicitudes as $solicitud)
                        <tr>
                            <td><strong>{{ $solicitud->codigo_solicitud }}</strong></td>
                            <td>
                                {{ $solicitud->alumno->primer_nombre }}
                                {{ $solicitud->alumno->apellido_paterno }}
                                <br>
                                <small class="text-muted">{{ $solicitud->alumno->codigo_educando }}</small>
                            </td>
                            <td>{{ $solicitud->colegio_destino }}</td>
                            <td>{{ \Carbon\Carbon::parse($solicitud->fecha_traslado)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($solicitud->fecha_solicitud)->format('d/m/Y H:i') }}</td>
                            <td>
                                <span class="badge badge-{{ $solicitud->estado }}">
                                    {{ strtoupper($solicitud->estado) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('traslado_pdf', $solicitud->codigo_solicitud) }}"
                                   class="btn btn-sm btn-danger"
                                   title="Descargar PDF">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                <p>No hay solicitudes de traslado registradas</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom-js')
<script>
$(document).ready(function() {
    $('#tablaSolicitudes').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        order: [[4, 'desc']], // Ordenar por fecha de solicitud descendente
        pageLength: 25
    });
});
</script>
@endsection
