<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Orden de Pago - {{ $orden->codigo_orden }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #000;
            padding: 30px 40px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 25px;
        }
        
        .header h1 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        
        .info-columns {
            margin-bottom: 20px;
        }
        
        .info-left {
            float: left;
            width: 50%;
        }
        
        .info-right {
            float: right;
            width: 48%;
            text-align: right;
        }
        
        .info-line {
            margin-bottom: 3px;
            line-height: 1.4;
        }
        
        .clear {
            clear: both;
        }
        
        .student-info {
            margin-top: 25px;
            margin-bottom: 15px;
        }
        
        .student-line {
            margin-bottom: 2px;
            line-height: 1.3;
        }
        
        .detalles-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .detalles-table th,
        .detalles-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
            word-wrap: break-word;
        }
        
        .detalles-table th {
            background: #fff;
            font-weight: bold;
        }
        
        .detalles-table td {
            vertical-align: top;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .total-row {
            border-top: 2px solid #000;
        }
        
        .instructions {
            margin-top: 20px;
            text-align: justify;
            line-height: 1.6;
        }
        
        .payment-options {
            background: #f9f9f9;
            padding: 12px;
            border: 2px solid #333;
            margin: 15px 0;
            border-radius: 5px;
        }
        
        .payment-options p {
            margin-bottom: 6px;
            line-height: 1.5;
        }
        
        .payment-option-title {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 8px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 4px;
        }
        
        .warning {
            margin-top: 15px;
            font-weight: bold;
            text-align: center;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
        }
        
        hr {
            border: none;
            border-top: 1px solid #000;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    @php
        // Función para obtener monto mensual según escala
        function obtenerMontoPorEscala($escala) {
            $montos = [
                'A' => 500.00,
                'B' => 400.00,
                'C' => 300.00,
                'D' => 200.00,
                'E' => 100.00,
            ];
            return $montos[strtoupper($escala)] ?? 100.00;
        }
        
        $montoMensual = obtenerMontoPorEscala($orden->matricula->escala ?? 'E');
    @endphp
    
    <div class="header">
        <h1>ORDEN DE PAGO</h1>
    </div>
    
    <div class="info-columns">
        <div class="info-left">
            <div class="info-line">INSTITUCIÓN EDUCATIVA SIGMA</div>
            <div class="info-line">OFICINA DE TESORERÍA</div>
            <div class="info-line">AV. LARCO 1232 - TRUJILLO</div>
        </div>
        <div class="info-right">
            <div class="info-line">{{ $orden->matricula->grado->nombre_grado ?? 'N/A' }} - SECCIÓN {{ $orden->matricula->seccion->nombreSeccion ?? 'N/A' }}</div>
            <div class="info-line">AÑO ACADÉMICO: {{ $orden->matricula->año_escolar }}</div>
            <div class="info-line">ORDEN DE PAGO: {{ $orden->codigo_orden }}</div>
            <div class="info-line">
                ESCALA DE PAGO: {{ $orden->matricula->escala }} - 
                MONTO MENSUAL: S/ {{ number_format($montoMensual, 2) }}
            </div>
            <div class="info-line">CÓDIGO ESTUDIANTE: {{ $orden->alumno->codigo_educando ?? 'N/A' }}</div>
        </div>
    </div>
    
    <div class="clear"></div>
    
    <div class="student-info">
        <div class="student-line">
            <strong>ESTUDIANTE: {{ strtoupper(trim(($orden->alumno->apellido_paterno ?? '') . ' ' . ($orden->alumno->apellido_materno ?? '') . ', ' . ($orden->alumno->primer_nombre ?? '') . ' ' . ($orden->alumno->otros_nombres ?? ''))) }}</strong>
        </div>
        <div class="student-line">
            <strong>DNI: {{ $orden->alumno->dni ?? 'N/A' }}</strong>
        </div>
    </div>
    
    <table class="detalles-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 5%;">N°</th>
                <th style="width: 30%;">CONCEPTO</th>
                <th style="width: 35%;">TIPO AJUSTE</th>
                <th class="text-right" style="width: 15%;">AJUSTE (S/)</th>
                <th class="text-right" style="width: 15%;">SUBTOTAL (S/)</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalGeneral = 0;
            @endphp
            @foreach($orden->detalles as $index => $detalle)
                @php
                    $totalGeneral += $detalle->monto_subtotal;
                    $tieneAjuste = $detalle->monto_ajuste != 0;
                    $esMora = $tieneAjuste && $detalle->monto_ajuste > 0;
                    $esDescuento = $tieneAjuste && $detalle->monto_ajuste < 0;
                    $tipoAjuste = '';
                    $montoAjuste = '';
                    
                    if ($tieneAjuste && $detalle->politica) {
                        if ($esMora) {
                            $porcentajeMora = number_format($detalle->politica->porcentaje, 0);
                            $tipoAjuste = 'Mora ' . $porcentajeMora . '%';
                            if ($detalle->politica->condiciones) {
                                $tipoAjuste .= ' - ' . $detalle->politica->condiciones;
                            }
                            $montoAjuste = '+ ' . number_format(abs($detalle->monto_ajuste), 2);
                        } elseif ($esDescuento) {
                            $porcentajeDesc = number_format($detalle->politica->porcentaje, 0);
                            $tipoAjuste = 'Descuento ' . $porcentajeDesc . '%';
                            if ($detalle->politica->condiciones) {
                                $tipoAjuste .= ' - ' . $detalle->politica->condiciones;
                            }
                            $montoAjuste = '- ' . number_format(abs($detalle->monto_ajuste), 2);
                        }
                    }
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ strtoupper($detalle->conceptoPago->descripcion ?? 'N/A') }}</td>
                    <td style="font-size: 9px;">{{ $tipoAjuste ?: '-' }}</td>
                    <td class="text-right">{{ $montoAjuste ?: '-' }}</td>
                    <td class="text-right">{{ number_format($detalle->monto_subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4" class="text-right"><strong>TOTAL</strong></td>
                <td class="text-right"><strong>{{ number_format($totalGeneral, 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>
    
    @php
        $cantidadDeudas = $orden->detalles->count();
    @endphp
    
    @if($cantidadDeudas > 1)
    <div class="instructions" style="background: #f5f5f5; padding: 12px; border: 1px solid #000; margin-bottom: 15px;">
        <p style="font-size: 12px; font-weight: bold; margin-bottom: 8px;">OPCIONES DE PAGO DISPONIBLES:</p>
        
        <p style="margin-bottom: 6px;"><strong>OPCION 1: PAGO COMPLETO</strong></p>
        <p style="margin-left: 15px; margin-bottom: 8px; font-size: 10px;">
            Pague el total de <strong>S/ {{ number_format($totalGeneral, 2) }}</strong> en una sola transaccion. 
            Este metodo cancela automaticamente todas las deudas de esta orden.
        </p>
        
        <p style="margin-bottom: 6px;"><strong>OPCION 2: PAGO EN 2 PARTES</strong></p>
        <p style="margin-left: 15px; margin-bottom: 8px; font-size: 10px;">
            Puede dividir el pago del total en <strong>maximo 2 transacciones</strong>. 
            Ejemplo: Primera parte S/ 150.00 y Segunda parte S/ {{ number_format($totalGeneral - 150, 2) }}.
            <br><strong>IMPORTANTE:</strong> Solo se permiten 2 pagos parciales. No podra hacer un tercer pago parcial.
        </p>
        
        <p style="margin-bottom: 6px;"><strong>OPCION 3: PAGO POR MES</strong></p>
        <p style="margin-left: 15px; margin-bottom: 8px; font-size: 10px;">
            Pague cada deuda mensual por separado sin limite de pagos individuales:
            @foreach($orden->detalles as $detalle)
            <br>- {{ $detalle->conceptoPago->descripcion ?? 'N/A' }}: S/ {{ number_format($detalle->monto_subtotal, 2) }}
            @endforeach
        </p>
        
        <p style="margin-bottom: 6px;"><strong>OPCION 4: COMBINACION</strong></p>
        <p style="margin-left: 15px; font-size: 10px;">
            Puede combinar las opciones anteriores. Ejemplo: Pagar 1 mes completo y luego dividir el resto en 2 partes.
        </p>
    </div>
    @else
    <div class="instructions" style="background: #f5f5f5; padding: 12px; border: 1px solid #000; margin-bottom: 15px;">
        <p style="font-size: 12px; font-weight: bold; margin-bottom: 8px;">INSTRUCCIONES DE PAGO:</p>
        <p style="font-size: 10px;">
            El monto total a pagar es <strong>S/ {{ number_format($totalGeneral, 2) }}</strong>. 
            Puede realizar el pago completo en una sola transaccion o dividirlo en maximo 2 pagos parciales.
        </p>
    </div>
    @endif
    
    <div class="instructions">
        <p><strong>FORMAS DE PAGO:</strong></p>
        <p>Los pagos pueden realizarse en las siguientes modalidades utilizando el codigo de matricula: <strong>{{ $orden->alumno->codigo_educando ?? 'N/A' }}</strong></p>
        <p><strong>1. BANCO INTERBANK</strong> (VENTANILLA, AGENTES Y TRANSFERENCIA) - Cuenta Corriente: <strong>198-254-87</strong></p>
        <p><strong>2. TESORERIA DEL COLEGIO</strong> - Av. Larco 1232 - Trujillo, en horario de atencion de 8:00 AM a 1:00 PM.</p>
        <p><strong>3. YAPE/PLIN</strong> - Al numero 987 654 321 (Indicar nombre del estudiante y concepto de pago)</p>
    </div>
    
    <div class="instructions" style="margin-top: 10px;">
        <p><strong>IMPORTANTE:</strong> Despues de realizar el pago, debe registrar el comprobante en el sistema SIGMA ingresando con su usuario y contrasena en la opcion <strong>PAGOS &gt; REGISTRAR COMPROBANTE</strong>. El plazo para registrar su comprobante es hasta el <strong>{{ $orden->fecha_vencimiento->format('d/m/Y') }}</strong>.</p>
    </div>
    
    <div class="instructions" style="margin-top: 10px;">
        <p>Para pagos realizados en bancos o aplicativos moviles, debera adjuntar una copia del voucher o captura de pantalla al momento del registro en el sistema.</p>
    </div>
    
    <div class="warning">
        <p>EL NO CUMPLIR CON EL PAGO EN LA FECHA INDICADA GENERARA MORAS SEGUN EL REGLAMENTO VIGENTE DE LA INSTITUCION.</p>
    </div>
    
    <div class="footer">
        <p><strong>Institucion Educativa SIGMA</strong> | Av. Larco 1232 - Trujillo | Telefono: (044) 123456</p>
        <p>Documento generado el {{ $orden->fecha_orden_pago->format('d/m/Y H:i:s') }} | Valido hasta {{ $orden->fecha_vencimiento->format('d/m/Y') }}</p>
    </div>
</body>
</html>
