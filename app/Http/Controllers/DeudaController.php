<?php

namespace App\Http\Controllers;

use App\Models\Deuda;
use Illuminate\Http\Request;
use App\Models\ConceptoPago;

class DeudaController extends Controller
{
    private static function doSearch($sqlColumns, $search, $maxEntriesShow)
    {
        if (!isset($search)) {
            $query = Deuda::where('estado', '=', '1')->orderBy('id_concepto', 'asc')
                ->orderBy('id_deuda', 'asc')->paginate($maxEntriesShow);
        } else {
            $query = Deuda::where('estado', '=', '1')
                ->whereAny($sqlColumns, 'LIKE', "%{$search}%")
                ->orderBy('id_concepto', 'asc')
                ->orderBy('id_deuda', 'asc')
                ->paginate($maxEntriesShow);
                
        }

        return $query;
    }

    public function index(Request $request)
    {
        $sqlColumns = ['id_deuda', 'id_alumno', 'id_concepto', 'periodo', 'monto_total','observacion'];
        $resource = 'financiera';

        $maxEntriesShow = $request->input('showing', 10);
        $paginaActual = $request->input('page', 1);
        $search = $request->input('search');

        if (!is_numeric($paginaActual) || $paginaActual <= 0) $paginaActual = 1;
        if (!is_numeric($maxEntriesShow) || $maxEntriesShow <= 0) $maxEntriesShow = 10;

        $query = self::doSearch($sqlColumns, $search, $maxEntriesShow);

        if ($paginaActual > $query->lastPage()) {
            $paginaActual = 1;
            $request['page'] = $paginaActual;
            $query = self::doSearch($sqlColumns, $search, $maxEntriesShow);
        }

        $data = [
            'titulo' => 'Deudas',
            'columnas' => ['ID','Periodo', 'Alumno', 'Concepto',  'Monto Total (S/)','Observaciones'],
            'filas' => [],
            'showing' => $maxEntriesShow,
            'paginaActual' => $paginaActual,
            'totalPaginas' => $query->lastPage(),
            'resource' => $resource,
            'view' => 'deuda_view',
            'create' => 'deuda_create',
            'edit' => 'deuda_edit',
            'delete' => 'deuda_delete',
        ];

        foreach (['created', 'edited', 'abort', 'deleted'] as $flag) {
            if ($request->input($flag, false)) {
                $data[$flag] = $request->input($flag);
            }
        }

        foreach ($query as $deuda) {
            array_push($data['filas'], [
                $deuda->id_deuda,
                $deuda->periodo,
                $deuda->alumno ? $deuda->alumno->primer_nombre . ' ' . $deuda->alumno->apellido_paterno : 'Sin nombre', 
                $deuda->concepto ? $deuda->concepto->descripcion : 'Sin concepto',
                $deuda->monto_total,
                $deuda->observacion,
                number_format($deuda->monto_total, 2)
            ]);
        }

        return view('gestiones.deuda.index', compact('data'));
    }

    public function create()
    {
        $conceptos = ConceptoPago::where('estado', 1)->get();

        $escalasPorConcepto = [];
        foreach ($conceptos as $concepto) {
            // Si ya existe el concepto, agrega la escala, si no, crea el array
            if (!isset($escalasPorConcepto[$concepto->id_concepto])) {
                $escalasPorConcepto[$concepto->id_concepto] = [];
            }
            // Evita duplicados
            if (!in_array($concepto->escala, $escalasPorConcepto[$concepto->id_concepto])) {
                $escalasPorConcepto[$concepto->id_concepto][] = $concepto->escala;
            }
            $montosPorConceptoEscala[$concepto->id_concepto][$concepto->escala] = $concepto->monto;
        }



        $data = [
            'return' => route('deuda_view', ['abort' => true]),
            'conceptos' => $conceptos,
            'escalasPorConcepto' => $escalasPorConcepto,
            'montosPorConceptoEscala' => $montosPorConceptoEscala,
        ];

        return view('gestiones.deuda.create', compact('data'));
    }

    public function createNewEntry(Request $request)
    {
        $request->validate([
            'codigo_educando' => [
                'required',
                'numeric',
                'exists:alumnos,codigo_educando'
            ],
            'id_concepto' => 'required|numeric',
            'fecha_limite' => 'required|date',
            'monto_total' => 'required|numeric|min:0',
            'observacion' => 'nullable|max:255',
        ], [
            'codigo_educando.required' => 'Ingrese un código de educando.',
            'codigo_educando.numeric' => 'El código de educando debe ser numérico.',
            'codigo_educando.exists' => 'El alumno no existe.',
            'id_concepto.required' => 'Seleccione un concepto de pago.',
            'id_concepto.numeric' => 'El concepto de pago debe ser numérico.',
            'fecha_limite.required' => 'Ingrese una fecha límite válida.',
            'fecha_limite.date' => 'La fecha límite debe tener un formato de fecha válido.',
            'monto_total.required' => 'Ingrese un monto total válido.',
            'monto_total.numeric' => 'El monto total debe ser un número.',
            'monto_total.min' => 'El monto total no puede ser negativo.',
            'observacion.max' => 'La observación no puede superar los 255 caracteres.',
        ]);

        $alumno = \App\Models\Alumno::where('codigo_educando', $request->input('codigo_educando'))->first();
        
        Deuda::create([
            'id_alumno' => $alumno->id_alumno,
            'id_concepto' => $request->input('id_concepto'),
            'fecha_limite' => $request->input('fecha_limite'),
            'monto_total' => $request->input('monto_total'),
            'periodo' => $request->input('periodo'),
            'monto_a_cuenta' => 0,
            'monto_adelantado' => 0,
            'observacion' => $request->input('observacion'),
            'estado' => 1
        ]);

        return redirect(route('deuda_view', ['created' => true]));
    }

    public function edit(Request $request, $id)
    {
        if (!isset($id)) {
            return redirect(route('deuda_view'));
        }

        $deuda = Deuda::findOrFail($id);

        $data = [
            'return' => route('deuda_view', ['abort' => true]),
            'id' => $id,
            'default' => [
                'id_alumno' => $deuda->id_alumno,
                'id_concepto' => $deuda->id_concepto,
                'fecha_limite' => $deuda->fecha_limite->format('Y-m-d'),
                'monto_total' => $deuda->monto_total,
                'periodo' => $deuda->periodo,
                'monto_a_cuenta' => $deuda->monto_a_cuenta,
                'monto_adelantado' => $deuda->monto_adelantado,
                'observacion' => $deuda->observacion,
            ]
        ];

        return view('gestiones.deuda.edit', compact('data'));
    }

    public function editEntry(Request $request, $id)
    {
        if (!isset($id)) {
            return redirect(route('deuda_view'));
        }

        $validated = $request->validate([
            'fecha_limite' => 'required|date',
            'monto_total' => 'required|numeric|min:0',
            'observacion' => 'nullable|max:255',
        ]);

        // Buscar deuda específica
        $deuda = Deuda::find($id);

        if (!$deuda) {
            return redirect()->route('deuda_view')->with('error', 'Deuda no encontrada.');
        }

        // Actualizar campos necesarios
        $deuda->update([
            'fecha_limite' => $request->input('fecha_limite'),
            'monto_total' => $request->input('monto_total'),
            'observacion' => $request->input('observacion'),
        ]);

        return redirect()->route('deuda_view', ['edited' => true]);
    }

    public function delete(Request $request)
    {
        $id = $request->input('id');
        $deuda = Deuda::find($id);
        $deuda->update(['estado' => '0']);

        return redirect(route('deuda_view', ['deleted' => true]));
    }
}
