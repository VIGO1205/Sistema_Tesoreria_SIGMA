<?php

namespace App\Http\Controllers;

use App\Models\DepartamentoAcademico;
use App\Models\Personal;
use Illuminate\Http\Request;

class DepartamentoAcademicoController extends Controller
{
    private static function doSearch($sqlColumns, $search, $maxEntriesShow){
        if (!isset($search)){
            $query = DepartamentoAcademico::where('estado', '=', '1')->paginate($maxEntriesShow);
        } else {
            $query = DepartamentoAcademico::where('estado', '=', '1')
                ->whereAny($sqlColumns, 'LIKE', "%{$search}%")
                ->paginate($maxEntriesShow);
        }

        return $query;
    }
    public function index(Request $request){
        $sqlColumns = ['id_departamento', 'nombre'];
        $resource = 'personal';

        $maxEntriesShow = $request->input('showing', 10);
        $paginaActual = $request->input('page', 1);
        $search = $request->input('search');

        if (!is_numeric($paginaActual) || $paginaActual <= 0) $paginaActual = 1;
        if (!is_numeric($maxEntriesShow) || $maxEntriesShow <= 0) $maxEntriesShow = 10;
        
        $query = DepartamentoAcademicoController::doSearch($sqlColumns, $search, $maxEntriesShow);

        if ($paginaActual > $query->lastPage()){
            $paginaActual = 1;
            $request['page'] = $paginaActual;
            $query = DepartamentoAcademicoController::doSearch($sqlColumns, $search, $maxEntriesShow);
        }
        
        $data = [
            'titulo' => 'Departamentos Académicos',
            'columnas' => [
                'ID',
                'Nombre',
            ],
            'filas' => [],
            'showing' => $maxEntriesShow,
            'paginaActual' => $paginaActual,
            'totalPaginas' => $query->lastPage(),
            'resource' => $resource,
            'view' => 'departamento_academico_view',
            'create' => 'departamento_academico_create',
            'edit' => 'departamento_academico_edit',
            'delete' => 'departamento_academico_delete',
        ];

        if ($request->input("created", false)){
            $data['created'] = $request->input('created');
        }

        if ($request->input("edited", false)){
            $data['edited'] = $request->input('edited');
        }

        if ($request->input("abort", false)){
            $data['abort'] = $request->input('abort');
        }

        if ($request->input("deleted", false)){
            $data['deleted'] = $request->input('deleted');
        }

        foreach ($query as $nivel){
            array_push($data['filas'],
            [
                $nivel->id_departamento,
                $nivel->nombre
            ]); 
        }
        return view('gestiones.departamento_academico.index', compact('data'));
    }

    public function create(Request $request){
        $data = [
            'return' => route('departamento_academico_view', ['abort' => true]),
        ];

        return view('gestiones.departamento_academico.create', compact('data'));
    }

    public function createNewEntry(Request $request){
        $request->validate([
            'nombre' => 'required|max:90',
        ],[
            'nombre.required' => 'Ingrese un nombre válido.',
            'nombre.max' => 'El nombre no puede superar los 90 caracteres.',
        ]);

        $nombre = $request->input('nombre');

        DepartamentoAcademico::create([
            'nombre' => $nombre,
        ]);

        return redirect(route('departamento_academico_view', ['created' => true]));
    }

    public function edit(Request $request, $id){
        if (!isset($id)){
            return redirect(route('departamento_academico_view'));
        }

        $requested = DepartamentoAcademico::findOrFail($id);

        $data = [
            'return' => route('departamento_academico_view', ['abort' => true]),
            'id' => $id,
            'default' => [
                'nombre' => $requested->nombre,
            ]
        ];
        return view('gestiones.departamento_academico.edit', compact('data'));
    }

    public function editEntry(Request $request, $id){
        if (!isset($id)){
            return redirect(route('departamento_academico_view'));
        }

        $requested = DepartamentoAcademico::find($id);

        if (isset($requested)){
            $newNombre = $request->input('nombre');

            $requested->update(['nombre' => $newNombre]);
        }

        return redirect(route('departamento_academico_view', ['edited' => true]));
    }

    public function delete(Request $request){
        $id = $request->input('id');

        $requested = DepartamentoAcademico::find($id);
        $requested->update(['estado' => '0']);

        $docentes = Personal::where('id_departamento','=',$request->input('id'))->get();

        foreach($docentes as $doc){
            $doc->estado = 0;
            $doc->save();
        }


        return redirect(route('departamento_academico_view', ['deleted' => true]));
    }
}
