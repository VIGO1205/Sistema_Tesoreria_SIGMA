<?php

namespace App\Http\Controllers;

use App\Models\ConceptoPago;
use Illuminate\Http\Request;

class ConceptoPagoController extends Controller
{
    private static function doSearch($sqlColumns, $search, $maxEntriesShow)
    {
        if (!isset($search)) {
            $query = ConceptoPago::where('estado', '=', '1')->paginate($maxEntriesShow);
        } else {
            $query = ConceptoPago::where('estado', '=', '1')
                ->whereAny($sqlColumns, 'LIKE', "%{$search}%")
                ->paginate($maxEntriesShow);
        }

        return $query;
    }

    public function index(Request $request)
    {
        $sqlColumns = ['id_concepto', 'descripcion', 'escala','monto'];
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
            'titulo' => 'Conceptos de Pago',
            'columnas' => ['ID', 'Descripción', 'Escala', 'Monto (S/)'],
            'filas' => [],
            'showing' => $maxEntriesShow,
            'paginaActual' => $paginaActual,
            'totalPaginas' => $query->lastPage(),
            'resource' => $resource,
            'view' => 'concepto_de_pago_view',
            'create' => 'concepto_de_pago_create',
            'edit' => 'concepto_de_pago_edit',
            'delete' => 'concepto_de_pago_delete',
        ];

        foreach (['created', 'edited', 'abort', 'deleted'] as $flag) {
            if ($request->input($flag, false)) {
                $data[$flag] = $request->input($flag);
            }
        }

        foreach ($query as $concepto) {
            array_push($data['filas'], [
                $concepto->id_concepto,
                $concepto->descripcion,
                $concepto->escala,
                number_format($concepto->monto, 2)
            ]);
        }

        return view('gestiones.conceptoPago.index', compact('data'));
    }

    public function create()
    {

        $data = [
            'return' => route('concepto_de_pago_view', ['abort' => true]),
        ];

        return view('gestiones.conceptoPago.create', compact('data'));
    }

    public function createNewEntry(Request $request)
    {

        $descripcion = $request->input('descripcion');
        $escala = $request->input('escala');
        $monto = $request->input('monto');

        $request->validate([
            'descripcion' => 'required|max:100',
            'escala' => 'required|in:A,B,C,D',
            'monto' => 'required|numeric|min:0'
        ], [
            'descripcion.required' => 'Ingrese una descripción válida.',
            'escala.required' => 'Seleccione una escala válida.',
            'monto.required' => 'Ingrese un monto válido.',
            'descripcion.max' => 'La descripción no puede superar los 100 caracteres.',
            'monto.numeric' => 'El monto debe ser un número válido.',
            'monto.min' => 'El monto no puede ser negativo.',
            'escala.in' => 'La escala debe ser A, B, C o D.',
        ]);

        

        ConceptoPago::create([
            'descripcion' => $descripcion,
            'escala' => $escala,
            'monto' => $monto,
            'estado' => 1

        ]);
        

        return redirect(route('concepto_de_pago_view', ['created' => true]));
    }

    public function edit(Request $request, $id)
    {
        if (!isset($id)) {
            return redirect(route('concepto_de_pago_view'));
        }

        $concepto = ConceptoPago::findOrFail($id);

        $data = [
            'return' => route('concepto_de_pago_view', ['abort' => true]),
            'id' => $id,
            'escalas' => ['A', 'B', 'C', 'D', 'E'],
            'default' => [
                'descripcion' => $concepto->descripcion,
                'escala' => $concepto->escala,
                'monto' => $concepto->monto
            ]
        ];

        return view('gestiones.conceptoPago.edit', compact('data'));
    }

    public function editEntry(Request $request, $id)
    {
        if (!isset($id)) {
            return redirect(route('concepto_de_pago_view'));
        }

        $request->validate([
            'monto' => 'required|numeric|min:0'
        ], [
            'monto.required' => 'Ingrese un monto válido.',
            'monto.numeric' => 'El monto debe ser un número válido.',
            'monto.min' => 'El monto no puede ser negativo.'
        ]);

        $concepto = ConceptoPago::find($id);
        $concepto->update([
            'monto' => $request->input('monto')
        ]);

        return redirect(route('concepto_de_pago_view', ['edited' => true]));
    }

    public function delete(Request $request)
    {
        $id = $request->input('id');
        $concepto = ConceptoPago::find($id);
        $concepto->update(['estado' => '0']);

        return redirect(route('concepto_de_pago_view', ['deleted' => true]));
    }
}
