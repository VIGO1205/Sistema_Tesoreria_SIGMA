<?php

namespace App\Http\Controllers;

use App\Models\Administrativo;
use App\Models\ConceptoAccion;
use App\Models\RegistroHistorico;
use App\Models\User;
use Illuminate\Http\Request;

class HistorialAccionesController extends Controller
{ 
    private static function doSearch($sqlColumns, $search, $maxEntriesShow){
        if (!isset($search)){
            $query = RegistroHistorico::where('estado', '=', '1')->paginate($maxEntriesShow);
        } else {
            $query = RegistroHistorico::where('estado', '=', '1')
                ->whereAny($sqlColumns, 'LIKE', "%{$search}%")
                ->paginate($maxEntriesShow);    
        }

        return $query;
    }
    public function index(Request $request){
        $sqlColumns = ['id_registro_historico', 'id_concepto_accion', 'id_autor', 'id_entidad_afectada', 'tipo_entidad_afectada', 'fecha_accion', 'observacion'];
        $resource = 'administrativa';

        $maxEntriesShow = $request->input('showing', 10);
        $paginaActual = $request->input('page', 1);
        $search = $request->input('search');

        if (!is_numeric($paginaActual) || $paginaActual <= 0) $paginaActual = 1;
        if (!is_numeric($maxEntriesShow) || $maxEntriesShow <= 0) $maxEntriesShow = 10;
        
        $query = HistorialAccionesController::doSearch($sqlColumns, $search, $maxEntriesShow);

        if ($paginaActual > $query->lastPage()){
            $paginaActual = 1;
            $request['page'] = $paginaActual;
            $query = HistorialAccionesController::doSearch($sqlColumns, $search, $maxEntriesShow);
        }
        
        $data = [
            'titulo' => 'Historial de Acciones',
            'columnas' => [
                'ID',
                'Concepto de la Acción',
                'Nombre del autor',
                'Usuario del autor',
                'Se afectó a',
                'Fecha de la Acción',
                'Observaciones',
            ],
            'filas' => [],
            'showing' => $maxEntriesShow,
            'paginaActual' => $paginaActual,
            'totalPaginas' => $query->lastPage(),
            'resource' => $resource,
            'view' => 'historial_de_acciones_view',
            'create' => 'nivel_educativo_create',
            'edit' => 'nivel_educativo_edit',
            'delete' => 'nivel_educativo_delete',
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

        foreach ($query as $accion){
            $conceptoAccion = ConceptoAccion::find($accion->id_concepto_accion);
            $usuario = User::find($accion->id_autor);


            $autor = Administrativo::where('id_usuario', '=', $usuario->getKey())->first();


            array_push($data['filas'],
            [
                $accion->getKey(),
                $conceptoAccion->accion,
                $autor->primer_nombre,
                $usuario->username,
                $accion->tipo_entidad_afectada,
                $accion->fecha_accion,
                $accion->observacion,
            ]); 
        }
        return view('gestiones.historial_de_acciones.index', compact('data'));
    }
}
