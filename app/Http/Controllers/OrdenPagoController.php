<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\OrdenPago;
use App\Models\DetalleOrdenPago;
use App\Models\Deuda;
use App\Models\Matricula;
use App\Models\ConceptoPago;
use App\Models\Politica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;

class OrdenPagoController extends Controller
{
    public function index(Request $request)
    {
        $resource = 'financiera';
        $maxEntriesShow = $request->input('showing', 10);
        $paginaActual = $request->input('page', 1);
        $search = $request->input('search');

        if (!is_numeric($paginaActual) || $paginaActual <= 0) $paginaActual = 1;
        if (!is_numeric($maxEntriesShow) || $maxEntriesShow <= 0) $maxEntriesShow = 10;

        $query = OrdenPago::with(['alumno', 'matricula.grado', 'matricula.seccion', 'detalles.deuda']);

        if ($search) {
            $query->whereHas('alumno', function($q) use ($search) {
                $q->where('primer_nombre', 'LIKE', "%{$search}%")
                  ->orWhere('apellido_paterno', 'LIKE', "%{$search}%")
                  ->orWhere('codigo_alumno', 'LIKE', "%{$search}%");
            })->orWhere('codigo_orden', 'LIKE', "%{$search}%");
        }

        $ordenes = $query->paginate($maxEntriesShow);

        if ($paginaActual > $ordenes->lastPage() && $ordenes->lastPage() > 0) {
            $paginaActual = 1;
            $request['page'] = $paginaActual;
            $ordenes = $query->paginate($maxEntriesShow);
        }

        $data = [
            'titulo' => 'Órdenes de Pago',
            'columnas' => [
                'Código',
                'Alumno',
                'Grado/Sección',
                'Monto Total',
                'Fecha Emisión',
                'Fecha Vencimiento',
                'Estado'
            ],
            'filas' => [],
            'showing' => $maxEntriesShow,
            'paginaActual' => $paginaActual,
            'totalPaginas' => $ordenes->lastPage(),
            'resource' => $resource,
            'view' => 'orden_pago_view',
            'create' => 'orden_pago_create',
            'edit' => null,
            'delete' => 'orden_pago_delete',
        ];

        if ($request->input("created", false)) {
            $data['created'] = $request->input('created');
        }

        if ($request->input("edited", false)) {
            $data['edited'] = $request->input('edited');
        }

        if ($request->input("abort", false)) {
            $data['abort'] = $request->input('abort');
        }

        if ($request->input("deleted", false)) {
            $data['deleted'] = $request->input('deleted');
        }

        foreach ($ordenes as $orden) {
            $alumnoNombre = trim(
                ($orden->alumno->primer_nombre ?? '') . ' ' . 
                ($orden->alumno->apellido_paterno ?? '') . ' ' . 
                ($orden->alumno->apellido_materno ?? '')
            );
            
            $grado = $orden->matricula->grado->nombre_grado ?? 'N/A';
            $seccion = $orden->matricula->seccion->nombreSeccion ?? 'N/A';

            array_push($data['filas'], [
                $orden->id_orden,
                $orden->codigo_orden,
                $alumnoNombre,
                $grado . ' - ' . $seccion,
                'S/ ' . number_format($orden->monto_total, 2),
                $orden->fecha_orden_pago->format('d/m/Y'),
                $orden->fecha_vencimiento->format('d/m/Y'),
                strtoupper($orden->estado)
            ]);
        }

        return view('gestiones.orden_pago.index', compact('data'));
    }

    public function create()
    {
        return view('gestiones.orden_pago.create');
    }

    public function buscarAlumno($codigo)
    {
        \Log::info('Buscando alumno con código: ' . $codigo);
        
        $alumno = Alumno::where('codigo_educando', $codigo)->first();

        \Log::info('Alumno encontrado: ' . ($alumno ? 'SÍ - ID: ' . $alumno->id_alumno : 'NO'));

        if (!$alumno) {
            return response()->json([
                'success' => false,
                'error' => 'Alumno no encontrado'
            ], 404);
        }

        $matricula = Matricula::where('id_alumno', $alumno->id_alumno)
            ->where('estado', true)
            ->with(['grado.niveleducativo', 'seccion'])
            ->first();

        if (!$matricula) {
            return response()->json([
                'success' => false,
                'error' => 'Alumno sin matrícula activa. Por favor, registre una matrícula para este alumno.'
            ], 404);
        }

        $hoy = Carbon::now();
        $mesActual = Carbon::now()->month;
        $anioActual = Carbon::now()->year;
        
        $meses = [
            'ENERO' => 1, 'FEBRERO' => 2, 'MARZO' => 3, 'ABRIL' => 4,
            'MAYO' => 5, 'JUNIO' => 6, 'JULIO' => 7, 'AGOSTO' => 8,
            'SETIEMBRE' => 9, 'OCTUBRE' => 10, 'NOVIEMBRE' => 11, 'DICIEMBRE' => 12
        ];
        
        // Verificar si hay una orden pendiente que aún no ha vencido
        $ordenReciente = OrdenPago::where('id_alumno', $alumno->id_alumno)
            ->where('estado', 'pendiente')
            ->where('fecha_vencimiento', '>=', $hoy)
            ->orderBy('fecha_orden_pago', 'desc')
            ->first();
        
        if ($ordenReciente) {
            $fechaVencimiento = Carbon::parse($ordenReciente->fecha_vencimiento);
            
            return response()->json([
                'success' => true,
                'orden_reciente' => true,
                'fecha_vencimiento' => $fechaVencimiento->format('d/m/Y'),
                'ultima_orden' => $ordenReciente->codigo_orden,
                'alumno' => [
                    'id_alumno' => $alumno->id_alumno,
                    'nombre_completo' => trim(
                        ($alumno->primer_nombre ?? '') . ' ' . 
                        ($alumno->otros_nombres ?? '') . ' ' . 
                        ($alumno->apellido_paterno ?? '') . ' ' . 
                        ($alumno->apellido_materno ?? '')
                    ),
                    'dni' => $alumno->dni,
                    'codigo_educando' => $alumno->codigo_educando,
                ],
                'matricula' => [
                    'id_matricula' => $matricula->id_matricula,
                    'grado' => $matricula->grado->nombre_grado ?? 'N/A',
                    'seccion' => $matricula->nombreSeccion ?? 'N/A',
                    'nivel_educativo' => $matricula->grado->niveleducativo->nombre_nivel ?? 'N/A',
                    'escala' => $matricula->escala ?? 'N/A',
                    'año_academico' => $matricula->año_escolar,
                ],
                'deudas' => [],
                'politica_descuento' => 0
            ]);
        }
        
        $todasLasDeudas = Deuda::where('id_alumno', $alumno->id_alumno)
            ->where('estado', true)
            ->whereRaw('monto_a_cuenta < monto_total') // Solo deudas no pagadas completamente
            ->whereDoesntHave('detallesOrdenPago', function($query) use ($hoy) {
                $query->whereHas('ordenPago', function($q) use ($hoy) {
                    $q->where('estado', 'pendiente')
                      ->where('fecha_vencimiento', '>=', $hoy);
                });
            })
            ->with('conceptoPago')
            ->orderBy('fecha_limite', 'asc')
            ->get();

        // Verificar si no tiene ninguna deuda pendiente
        if ($todasLasDeudas->isEmpty()) {
            return response()->json([
                'success' => false,
                'sin_deudas' => true,
                'message' => 'El alumno está al día con sus pagos. No hay deudas pendientes para generar una orden de pago.'
            ], 404);
        }

        $tieneDeudasAnteriores = false;
        $deudasAnteriores = [];
        $deudasActualYPosteriores = [];

        foreach ($todasLasDeudas as $deuda) {
            // Saltar deudas ya pagadas completamente (considerando adelantos)
            $totalPagado = $deuda->monto_a_cuenta + $deuda->monto_adelantado;
            if ($totalPagado >= $deuda->monto_total) {
                continue;
            }
            
            $descripcion = $deuda->conceptoPago->descripcion ?? '';
            $partes = explode(' ', $descripcion);
            
            if (count($partes) >= 2) {
                $mesDeuda = $meses[$partes[0]] ?? 0;
                $anioDeuda = intval($partes[1]);
                
                // Separar deudas anteriores vs actuales/futuras
                if ($anioDeuda < $anioActual || ($anioDeuda == $anioActual && $mesDeuda < $mesActual)) {
                    $tieneDeudasAnteriores = true;
                    $deudasAnteriores[] = $deuda;
                } else if ($anioDeuda == $anioActual && $mesDeuda >= $mesActual) {
                    // Solo incluir mes actual y futuros
                    $deudasActualYPosteriores[] = $deuda;
                }
            }
        }

        $deudas = $tieneDeudasAnteriores ? collect($deudasAnteriores) : collect($deudasActualYPosteriores);

        // Si no hay deudas pendientes después del filtrado, el alumno está al día
        if ($deudas->isEmpty()) {
            return response()->json([
                'success' => false,
                'al_dia' => true,
                'message' => '¡Felicidades! El estudiante está completamente al día con sus pagos. No tiene deudas pendientes.'
            ], 200);
        }

        $politicasMora = Politica::where('tipo', 'mora')->where('estado', true)->get();
        $politicaDescuento = Politica::where('tipo', 'descuento')->where('estado', true)->first();

        // Encontrar la primera deuda pendiente (la más próxima a pagar)
        $primeraDeudaPendiente = $deudas->first();
        $mesesMaximosAdelantar = 0;
        
        if ($primeraDeudaPendiente && !$tieneDeudasAnteriores) {
            $descripcion = $primeraDeudaPendiente->conceptoPago->descripcion ?? '';
            $partes = explode(' ', $descripcion);
            
            if (count($partes) >= 2) {
                $mesesMap = [
                    'ENERO' => 1, 'FEBRERO' => 2, 'MARZO' => 3, 'ABRIL' => 4,
                    'MAYO' => 5, 'JUNIO' => 6, 'JULIO' => 7, 'AGOSTO' => 8,
                    'SETIEMBRE' => 9, 'OCTUBRE' => 10, 'NOVIEMBRE' => 11, 'DICIEMBRE' => 12
                ];
                
                $mesDeuda = $mesesMap[$partes[0]] ?? 0;
                $anioDeuda = intval($partes[1]);
                
                // Calcular meses disponibles desde el mes ACTUAL hasta diciembre
                // Si estamos en Noviembre (11): 12 - 11 = 1 mes (Diciembre)
                // Si estamos en Octubre (10): 12 - 10 = 2 meses (Nov y Dic)
                // Si estamos en Diciembre (12): 12 - 12 = 0 meses
                if ($anioDeuda == $anioActual) {
                    // Si la primera deuda pendiente es del mes actual o futura
                    if ($mesDeuda >= $mesActual) {
                        // Calcular desde el mes ACTUAL, no desde la deuda
                        $mesesMaximosAdelantar = 12 - $mesActual;
                    }
                }
            }
        }

        return response()->json([
            'success' => true,
            'tiene_deudas_anteriores' => $tieneDeudasAnteriores,
            'meses_maximos_adelantar' => $mesesMaximosAdelantar,
            'alumno' => [
                'id_alumno' => $alumno->id_alumno,
                'nombre_completo' => trim(
                    ($alumno->primer_nombre ?? '') . ' ' . 
                    ($alumno->otros_nombres ?? '') . ' ' . 
                    ($alumno->apellido_paterno ?? '') . ' ' . 
                    ($alumno->apellido_materno ?? '')
                ),
                'dni' => $alumno->dni,
                'codigo_educando' => $alumno->codigo_educando,
            ],
            'matricula' => [
                'id_matricula' => $matricula->id_matricula,
                'grado' => $matricula->grado->nombre_grado ?? 'N/A',
                'seccion' => $matricula->nombreSeccion ?? 'N/A',
                'nivel_educativo' => $matricula->grado->niveleducativo->nombre_nivel ?? 'N/A',
                'escala' => $matricula->escala ?? 'N/A',
                'año_academico' => $matricula->año_escolar,
            ],
            'deudas' => $deudas->map(function($deuda) use ($mesActual, $anioActual, $politicasMora, $mesesMaximosAdelantar) {
                $descripcion = $deuda->conceptoPago->descripcion ?? '';
                $partes = explode(' ', $descripcion);
                $mesesMap = [
                    'ENERO' => 1, 'FEBRERO' => 2, 'MARZO' => 3, 'ABRIL' => 4,
                    'MAYO' => 5, 'JUNIO' => 6, 'JULIO' => 7, 'AGOSTO' => 8,
                    'SETIEMBRE' => 9, 'OCTUBRE' => 10, 'NOVIEMBRE' => 11, 'DICIEMBRE' => 12
                ];
                
                $mesDeuda = count($partes) >= 2 ? ($mesesMap[$partes[0]] ?? 0) : 0;
                $anioDeuda = count($partes) >= 2 ? intval($partes[1]) : 0;
                
                // Usar el valor calculado globalmente
                $mesesDisponibles = $mesesMaximosAdelantar;
                
                $esDeudaAnterior = false;
                if ($anioDeuda < $anioActual || ($anioDeuda == $anioActual && $mesDeuda < $mesActual)) {
                    $esDeudaAnterior = true;
                }
                
                $porcentajeMora = 0;
                $aplicaMora = false;
                $tipoMora = '';
                $descripcionPoliticaMora = '';
                
                if ($deuda->fecha_limite) {
                    $fechaLimite = Carbon::parse($deuda->fecha_limite);
                    $hoy = Carbon::now();
                    
                    if ($hoy->gt($fechaLimite)) {
                        $aplicaMora = true;
                        
                        if ($esDeudaAnterior) {
                            $politicaMora = $politicasMora->where('condiciones', 'like', '%Meses Anteriores%')->first();
                            $porcentajeMora = $politicaMora ? $politicaMora->porcentaje : 15.00;
                            $descripcionPoliticaMora = $politicaMora ? $politicaMora->condiciones : 'Mora por meses anteriores';
                            $tipoMora = 'anterior';
                        } else {
                            $diaActual = $hoy->day;
                            
                            foreach ($politicasMora as $politica) {
                                if ($politica->dias_minimo && $politica->dias_maximo) {
                                    if ($diaActual >= $politica->dias_minimo && $diaActual <= $politica->dias_maximo) {
                                        $porcentajeMora = $politica->porcentaje;
                                        $descripcionPoliticaMora = $politica->condiciones;
                                        $tipoMora = 'actual';
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
                
                return [
                    'id_deuda' => $deuda->id_deuda,
                    'concepto' => $descripcion,
                    'monto_total' => $deuda->monto_total,
                    'monto_a_cuenta' => $deuda->monto_a_cuenta,
                    'monto_pendiente' => $deuda->monto_total - $deuda->monto_a_cuenta,
                    'fecha_limite' => $deuda->fecha_limite ? 
                        Carbon::parse($deuda->fecha_limite)->format('d/m/Y') : 'N/A',
                    'periodo' => $descripcion,
                    'mes' => $mesDeuda,
                    'anio' => $anioDeuda,
                    'meses_disponibles_adelantar' => $mesesDisponibles,
                    'es_deuda_anterior' => $esDeudaAnterior,
                    'aplica_mora' => $aplicaMora,
                    'porcentaje_mora' => $porcentajeMora,
                    'tipo_mora' => $tipoMora,
                    'descripcion_politica' => $descripcionPoliticaMora,
                ];
            }),
            'politica_descuento' => $politicaDescuento ? $politicaDescuento->porcentaje : 10.00,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_alumno' => 'required|exists:alumnos,id_alumno',
            'id_matricula' => 'required|exists:matriculas,id_matricula',
            'deudas' => 'nullable|array',
            'deudas.*' => 'required|exists:deudas,id_deuda',
            'meses_adelantar' => 'required|integer|min:0|max:12',
            'fecha_vencimiento' => 'required|date',
            'tiene_deudas_anteriores' => 'nullable|in:0,1',
        ], [
            'id_alumno.required' => 'Por favor ingrese el código del alumno',
            'fecha_vencimiento.required' => 'Debe ingresar la fecha de vencimiento',
        ]);

        DB::beginTransaction();
        
        try {
            $tieneDeudas = $request->has('deudas') && is_array($request->deudas) && count($request->deudas) > 0;
            $tieneMesesAdelantar = $request->meses_adelantar > 0;
            $tieneDeudasAnteriores = $request->input('tiene_deudas_anteriores', '0') === '1';
            
            // Si tiene deudas anteriores, debe seleccionar al menos una deuda o adelantar meses
            // Si NO tiene deudas anteriores, puede generar orden para el mes actual sin seleccionar nada
            if ($tieneDeudasAnteriores && !$tieneDeudas && !$tieneMesesAdelantar) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debe seleccionar al menos una deuda atrasada o indicar meses a adelantar.'
                ], 400);
            }
            
            $deudas = collect();
            if ($tieneDeudas) {
                $deudas = Deuda::whereIn('id_deuda', $request->deudas)
                    ->where('estado', true)
                    ->where('monto_a_cuenta', 0)
                    ->get();
                
                if ($deudas->isEmpty()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Las deudas seleccionadas no son válidas o ya tienen pagos registrados.'
                    ], 400);
                }
            } elseif (!$tieneDeudasAnteriores) {
                // Si NO tiene deudas anteriores y NO seleccionó checkboxes
                // Puede ser que ya pagó el mes actual y solo quiere adelantar
                if ($tieneMesesAdelantar) {
                    // Solo quiere adelantar, no necesita deuda del mes actual
                    $deudas = collect();
                } else {
                    // NO está adelantando, buscar deuda del mes actual
                    $deudaMesActual = Deuda::where('id_alumno', $request->id_alumno)
                        ->where('estado', true)
                        ->whereRaw('monto_a_cuenta < monto_total')
                        ->whereDoesntHave('detallesOrdenPago', function ($query) {
                            $query->whereHas('ordenPago', function ($q) {
                                $q->whereIn('estado', ['pendiente', 'pagado']);
                            });
                        })
                        ->orderBy('id_deuda', 'asc')
                        ->first();
                    
                    if (!$deudaMesActual) {
                        return response()->json([
                            'success' => false,
                            'message' => 'No se encontró ninguna deuda pendiente.'
                        ], 400);
                    }
                    
                    $deudas = collect([$deudaMesActual]);
                }
            }
            
            // Validación final: debe tener al menos deudas seleccionadas O meses a adelantar
            if ($deudas->isEmpty() && !$tieneMesesAdelantar) {
                return response()->json([
                    'success' => false,
                    'message' => 'Las deudas seleccionadas no son válidas o ya tienen pagos registrados.'
                ], 400);
            }
            
            $ultimaOrden = OrdenPago::orderBy('id_orden', 'desc')->first();
            $numero = $ultimaOrden ? ($ultimaOrden->id_orden + 1) : 1;
            $codigoOrden = 'OP-' . date('Y') . '-' . str_pad($numero, 4, '0', STR_PAD_LEFT);

            $montoTotalDeudas = $deudas->sum('monto_total');
            
            $orden = OrdenPago::create([
                'codigo_orden' => $codigoOrden,
                'id_alumno' => $request->id_alumno,
                'id_matricula' => $request->id_matricula,
                'monto_total' => 0,
                'numero_cuenta' => '1234567890',
                'fecha_orden_pago' => now(),
                'fecha_vencimiento' => $request->fecha_vencimiento,
                'estado' => 'pendiente',
            ]);

            $montoTotal = 0;
            $politicaDescuento = Politica::where('tipo', 'descuento')->where('estado', true)->first();
            $porcentajeDescuento = $politicaDescuento ? $politicaDescuento->porcentaje : 10.00;

            foreach ($deudas as $deuda) {
                $montoMora = 0;
                $porcentajeMora = 0;
                $idPoliticaMora = null;
                
                if ($deuda->fecha_limite) {
                    $fechaLimite = Carbon::parse($deuda->fecha_limite);
                    $hoy = Carbon::now();
                    
                    if ($hoy->gt($fechaLimite)) {
                        $descripcion = $deuda->conceptoPago->descripcion ?? '';
                        $partes = explode(' ', $descripcion);
                        $meses = [
                            'ENERO' => 1, 'FEBRERO' => 2, 'MARZO' => 3, 'ABRIL' => 4,
                            'MAYO' => 5, 'JUNIO' => 6, 'JULIO' => 7, 'AGOSTO' => 8,
                            'SETIEMBRE' => 9, 'OCTUBRE' => 10, 'NOVIEMBRE' => 11, 'DICIEMBRE' => 12
                        ];
                        
                        if (count($partes) >= 2) {
                            $mesDeuda = $meses[$partes[0]] ?? 0;
                            $anioDeuda = intval($partes[1]);
                            $mesActual = $hoy->month;
                            $anioActual = $hoy->year;
                            
                            if ($anioDeuda < $anioActual || ($anioDeuda == $anioActual && $mesDeuda < $mesActual)) {
                                $politicaMoraAnterior = Politica::where('tipo', 'mora')
                                    ->where(function($query) {
                                        $query->where('condiciones', 'like', '%Meses Anteriores%')
                                              ->orWhere('condiciones', 'like', '%mes anterior%')
                                              ->orWhere('nombre', 'like', '%Mora Meses Anteriores%');
                                    })
                                    ->where('estado', true)
                                    ->first();
                                
                                if ($politicaMoraAnterior) {
                                    $porcentajeMora = $politicaMoraAnterior->porcentaje;
                                    $idPoliticaMora = $politicaMoraAnterior->id_politica;
                                } else {
                                    $porcentajeMora = 15.00;
                                    $idPoliticaMora = null;
                                }
                                
                                $montoMora = $deuda->monto_total * ($porcentajeMora / 100);
                            } else {
                                $diaActual = $hoy->day;
                                $politicasMora = Politica::where('tipo', 'mora')->where('estado', true)->get();
                                
                                foreach ($politicasMora as $politica) {
                                    if ($politica->dias_minimo && $politica->dias_maximo) {
                                        if ($diaActual >= $politica->dias_minimo && $diaActual <= $politica->dias_maximo) {
                                            $porcentajeMora = $politica->porcentaje;
                                            $idPoliticaMora = $politica->id_politica;
                                            $montoMora = $deuda->monto_total * ($porcentajeMora / 100);
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                
                $montoSubtotal = $deuda->monto_total + $montoMora;
                
                DetalleOrdenPago::create([
                    'id_orden' => $orden->id_orden,
                    'id_deuda' => $deuda->id_deuda,
                    'id_concepto' => $deuda->id_concepto,
                    'id_politica' => $idPoliticaMora,
                    'monto_base' => $deuda->monto_total,
                    'monto_ajuste' => $montoMora,
                    'monto_subtotal' => $montoSubtotal,
                ]);

                $deuda->update([
                    'monto_total' => $montoSubtotal
                ]);

                $montoTotal += $montoSubtotal;
            }
            
            if ($request->meses_adelantar > 0) {
                $primeraDeuda = $deudas->first();
                $montoMensual = $primeraDeuda ? $primeraDeuda->monto_total : 0;
                
                $deudasFuturas = Deuda::where('id_alumno', $request->id_alumno)
                    ->where('estado', true)
                    ->whereRaw('monto_a_cuenta < monto_total') // Solo deudas no pagadas completamente
                    ->whereDoesntHave('detallesOrdenPago', function ($query) {
                        $query->whereHas('ordenPago', function ($q) {
                            $q->whereIn('estado', ['pendiente', 'pagado']);
                        });
                    })
                    ->orderBy('id_deuda', 'asc')
                    ->get();
                
                $idsDeudaActual = $deudas->pluck('id_deuda')->toArray();
                $deudasParaAdelantar = $deudasFuturas->filter(function($deuda) use ($idsDeudaActual) {
                    return !in_array($deuda->id_deuda, $idsDeudaActual);
                });
                
                $deudasAdelantadas = $deudasParaAdelantar->take($request->meses_adelantar);
                
                if ($deudasAdelantadas->count() < $request->meses_adelantar) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'No hay suficientes deudas futuras disponibles para adelantar ' . $request->meses_adelantar . ' mes(es). Solo hay ' . $deudasAdelantadas->count() . ' disponible(s).'
                    ], 400);
                }
                
                foreach ($deudasAdelantadas as $deudaAdelantada) {
                    $montoBase = $deudaAdelantada->monto_total;
                    $montoDescuento = $montoBase * ($porcentajeDescuento / 100);
                    $montoConDescuento = $montoBase - $montoDescuento;
                    
                    DetalleOrdenPago::create([
                        'id_orden' => $orden->id_orden,
                        'id_deuda' => $deudaAdelantada->id_deuda,
                        'id_concepto' => $deudaAdelantada->id_concepto,
                        'id_politica' => $politicaDescuento ? $politicaDescuento->id_politica : null, // Política de descuento
                        'monto_base' => $montoBase,
                        'monto_ajuste' => -$montoDescuento,
                        'monto_subtotal' => $montoConDescuento,
                    ]);
                    
                    $deudaAdelantada->update([
                        'monto_total' => $montoConDescuento
                    ]);
                    
                    $montoTotal += $montoConDescuento;
                }
            }
            
            $orden->update(['monto_total' => $montoTotal]);

            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Orden de pago creada exitosamente',
                    'pdf_url' => route('orden_pago_pdf', $orden->id_orden),
                    'redirect_url' => route('orden_pago_view', ['created' => true])
                ]);
            }

            return redirect()
                ->route('orden_pago_pdf', $orden->id_orden);

        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear orden de pago: ' . $e->getMessage()
                ], 500);
            }
            
            return back()
                ->withInput()
                ->with('abort', 'Error al crear orden de pago: ' . $e->getMessage());
        }
    }

    public function generarPDF($id)
    {
        $orden = OrdenPago::with([
            'alumno',
            'matricula.grado',
            'matricula.seccion',
            'detalles.deuda.conceptoPago',
            'detalles.conceptoPago',
            'detalles.politica'
        ])->findOrFail($id);

        $html = view('gestiones.orden_pago.pdf', compact('orden'))->render();
        
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Arial');
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="orden-pago-' . $orden->codigo_orden . '.pdf"'
        ]);
    }

    public function anular($id)
    {
        $orden = OrdenPago::findOrFail($id);
        
        if ($orden->estado === 'pagado') {
            return back()->with('abort', 'No se puede anular una orden ya pagada');
        }

        $orden->update(['estado' => 'anulado']);

        return back()->with('edited', 'Orden de pago anulada exitosamente');
    }

    public function delete(Request $request)
    {
        $ids = $request->input('ids', []);
        
        DB::beginTransaction();
        
        try {
            OrdenPago::whereIn('id_orden', $ids)->delete();
            DB::commit();
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
