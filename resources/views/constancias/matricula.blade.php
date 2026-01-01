<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Constancia de Matrícula</title>
    <style>
        @page {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
        }

        .operator-watermark {
            position: absolute;
            left: 10mm;
            bottom: 10mm;
            transform: rotate(-90deg);
            transform-origin: left bottom;
            font-size: 16px;
            color: #ccc;
            font-weight: bold;
            white-space: nowrap;
        }

        @media print {
            .operator-watermark {
                left: 5mm;
            }
        }

        .document-container {
            background-color: white;
            padding: 15mm 20mm;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
            position: relative;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header-top {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .college-name {
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 10px 0;
            color: #000;
        }

        .college-details {
            font-size: 12px;
        }

        .divider {
            border-bottom: 2px solid #000;
            margin: 20px 0;
            width: 100%;
        }

        .title {
            text-align: center;
            font-size: 26px;
            font-weight: bold;
            text-decoration: underline;
            margin: 40px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .content {
            font-size: 16px;
            line-height: 2;
            text-align: justify;
            margin-bottom: 50px;
        }

        .student-name {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 18px;
            text-align: center;
            display: block;
            margin: 20px 0;
        }

        .academic-info {
            margin: 30px 40px;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .info-row {
            display: flex;
            border-bottom: 1px dotted #ccc;
            padding-bottom: 5px;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .label {
            font-weight: bold;
            width: 180px;
        }

        .value {
            flex: 1;
            font-weight: 500;
        }

        .closing {
            margin-top: 40px;
        }

        .date {
            text-align: right;
            margin-top: 60px;
            font-size: 16px;
        }

        .signature-section {
            margin-top: 120px;
            display: flex;
            justify-content: center;
        }

        .signature-box {
            text-align: center;
        }

        .signature-line {
            width: 300px;
            border-top: 1px solid #000;
            margin-bottom: 10px;
            margin-left: auto;
            margin-right: auto;
        }

        .footer {
            position: absolute;
            bottom: 10mm;
            left: 25mm;
            right: 25mm;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }

        @media print {
            body {
                background: none;
                padding: 0;
            }

            .document-container {
                box-shadow: none;
                border: none;
                width: 100%;
                height: 100%;
                margin: 0;
                padding: 20mm;
            }
        }
    </style>
</head>

<body>
    <div class="document-container">

        <div class="operator-watermark">Generado por {{ $operator_name }} ({{ $operator_username }}) el
            {{ now()->format('d/m/Y \a \l\a\s H:i:s') }}.
        </div>

        <div class="header">
            <div class="header-top">Ministerio de Educación</div>
            <div class="college-name">Institución Educativa SIGMA</div>
            <div class="divider"></div>
        </div>

        <div class="title">CONSTANCIA DE MATRÍCULA</div>

        <div class="content">
            <p>
                La Dirección de la Institución Educativa Privada <strong>SIGMA</strong>, hace constar por el presente
                documento que:
            </p>

            <span class="student-name">{{ $student_name }}</span>

            <p>
                Identificado(a) con DNI N° <strong>{{ $student_dni }}</strong>, culminó satisfactoriamente su
                proceso de matrícula correspondiente al Año Académico <strong>{{ $year }}</strong>
                en nuestra institución, quedando registrado con los siguientes datos:
            </p>

            <div class="academic-info">
                <div class="info-row">
                    <span class="label">Nivel Educativo:</span>
                    <span class="value">{{ $level }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Grado:</span>
                    <span class="value">{{ $grade }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Sección:</span>
                    <span class="value">{{ $section }}</span>
                </div>
            </div>

            <p class="closing">
                Se expide la presente a solicitud de la parte interesada para los fines que estime conveniente.
            </p>
        </div>

        <div class="date">
            Trujillo, {{ now()->format('d \d\e F \d\e\l Y') }}
        </div>

        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-line"></div>
                <strong>DIRECTOR(A)</strong><br>
                <span>Sello y Firma</span>
            </div>
        </div>
        <br>
        <div class="footer">
            Av. Juan Pablo II S/N Urb. San Andrés, Trujillo - La Libertad | Teléfono: (44) 538970 |
            colegiosigma.edu.pe
            <br>
            Este documento fue generado automáticamente por el Sistema de Gestión Académica.
        </div>
    </div>
</body>

</html>