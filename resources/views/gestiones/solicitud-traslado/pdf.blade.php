<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud de Traslado - {{ $nombre_completo }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #667eea;
            padding-bottom: 20px;
        }

        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 10px;
        }

        .header h1 {
            font-size: 20px;
            color: #333;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 11px;
            color: #666;
        }

        .codigo-solicitud {
            background: #667eea;
            color: white;
            padding: 10px;
            text-align: center;
            border-radius: 5px;
            margin: 20px 0;
            font-size: 16px;
            font-weight: bold;
        }

        .section {
            margin-bottom: 25px;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 15px;
            text-transform: uppercase;
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 8px;
        }

        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }

        .info-label {
            display: table-cell;
            font-weight: bold;
            width: 35%;
            color: #555;
            padding: 5px 0;
        }

        .info-value {
            display: table-cell;
            width: 65%;
            padding: 5px 0;
            color: #333;
        }

        .motivo-box {
            background: white;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
            margin-top: 10px;
            min-height: 80px;
        }

        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #e0e0e0;
        }

        .signature-section {
            margin-top: 60px;
            text-align: center;
        }

        .signature-line {
            border-top: 2px solid #333;
            width: 250px;
            margin: 0 auto;
            margin-top: 60px;
        }

        .signature-label {
            margin-top: 10px;
            font-weight: bold;
        }

        .fecha-generacion {
            text-align: right;
            font-size: 10px;
            color: #666;
            margin-top: 20px;
        }

        .importante {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }

        .importante-title {
            font-weight: bold;
            color: #856404;
            margin-bottom: 10px;
        }

        .importante-text {
            font-size: 11px;
            color: #856404;
            line-height: 1.5;
        }

        .estado-badge {
            display: inline-block;
            background: #ffc107;
            color: #333;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            margin-top: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            color: rgba(102, 126, 234, 0.1);
            font-weight: bold;
            z-index: -1;
        }
    </style>
</head>
<body>
    <div class="watermark">SOLICITUD DE TRASLADO</div>

    <!-- Header -->
    <div class="header">
        <div class="logo">🎓 SISTEMA DE TESORERÍA</div>
        <h1>SOLICITUD DE TRASLADO DE ALUMNO</h1>
        <p>Institución Educativa</p>
    </div>

    <!-- Código de Solicitud -->
    <div class="codigo-solicitud">
        CÓDIGO DE SOLICITUD: {{ $solicitud->codigo_solicitud }}
        <span class="estado-badge">{{ strtoupper($solicitud->estado) }}</span>
    </div>

    <!-- Información del Alumno -->
    <div class="section">
        <div class="section-title">📋 Información del Alumno</div>

        <div class="info-row">
            <div class="info-label">Código de Educando:</div>
            <div class="info-value">{{ $alumno->codigo_educando }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Nombre Completo:</div>
            <div class="info-value">{{ $nombre_completo }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">DNI:</div>
            <div class="info-value">{{ $alumno->dni ?? 'No registrado' }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Grado Actual:</div>
            <div class="info-value">{{ $grado_actual }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Fecha de Nacimiento:</div>
            <div class="info-value">{{ $alumno->fecha_nacimiento ? \Carbon\Carbon::parse($alumno->fecha_nacimiento)->format('d/m/Y') : 'No registrado' }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Dirección:</div>
            <div class="info-value">{{ $alumno->direccion ?? 'No registrado' }}</div>
        </div>
    </div>

    <!-- Información del Traslado -->
    <div class="section">
        <div class="section-title">🏫 Información del Traslado</div>

        <div class="info-row">
            <div class="info-label">Colegio de Destino:</div>
            <div class="info-value">{{ $solicitud->colegio_destino }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Dirección del Colegio:</div>
            <div class="info-value">{{ $solicitud->direccion_nuevo_colegio ?? 'No especificado' }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Teléfono de Contacto:</div>
            <div class="info-value">{{ $solicitud->telefono_nuevo_colegio ?? 'No especificado' }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Fecha de Traslado:</div>
            <div class="info-value">{{ \Carbon\Carbon::parse($solicitud->fecha_traslado)->format('d/m/Y') }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Fecha de Solicitud:</div>
            <div class="info-value">{{ \Carbon\Carbon::parse($solicitud->fecha_solicitud)->format('d/m/Y H:i:s') }}</div>
        </div>
    </div>

    <!-- Motivo del Traslado -->
    <div class="section">
        <div class="section-title">💬 Motivo del Traslado</div>
        <div class="motivo-box">
            {{ $solicitud->motivo_traslado }}
        </div>
    </div>

    <!-- Observaciones -->
    @if($solicitud->observaciones)
    <div class="section">
        <div class="section-title">📝 Observaciones Adicionales</div>
        <div class="motivo-box">
            {{ $solicitud->observaciones }}
        </div>
    </div>
    @endif

    <!-- Información Importante -->
    <div class="importante">
        <div class="importante-title">⚠️ INFORMACIÓN IMPORTANTE</div>
        <div class="importante-text">
            • Esta solicitud de traslado ha sido generada por el sistema de gestión académica.<br>
            • El alumno NO PRESENTA DEUDAS PENDIENTES al momento de generar esta solicitud.<br>
            • Esta solicitud debe ser presentada en secretaría académica para su procesamiento.<br>
            • Se requiere la firma del padre/madre o apoderado para validar el traslado.<br>
            • El traslado será efectivo una vez completados todos los trámites administrativos correspondientes.
        </div>
    </div>

    <!-- Firmas -->
    <div class="signature-section">
        <table style="width: 100%;">
            <tr>
                <td style="width: 50%; text-align: center;">
                    <div class="signature-line"></div>
                    <div class="signature-label">Padre/Madre o Apoderado</div>
                    <div style="font-size: 10px; color: #666;">DNI: _______________</div>
                </td>
                <td style="width: 50%; text-align: center;">
                    <div class="signature-line"></div>
                    <div class="signature-label">Secretaría Académica</div>
                    <div style="font-size: 10px; color: #666;">Sello y Firma</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="fecha-generacion">
            Documento generado el: {{ $fecha_generacion }}<br>
            Código de Solicitud: {{ $solicitud->codigo_solicitud }}
        </div>
    </div>
</body>
</html>
