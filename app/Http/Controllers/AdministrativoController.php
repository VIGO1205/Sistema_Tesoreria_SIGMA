<?php

namespace App\Http\Controllers;

use App\Models\Administrativo;
use App\Models\User;
use Illuminate\Http\Request;

class AdministrativoController extends Controller
{
    private static function doSearch($sqlColumns, $search, $maxEntriesShow){
        if (!isset($search)){
            $query = Administrativo::where('estado', '=', '1')->paginate($maxEntriesShow);
        } else {
            $query = Administrativo::where('estado', '=', '1')
                ->whereAny($sqlColumns, 'LIKE', "%{$search}%")
                ->paginate($maxEntriesShow);
        }

        return $query;
    }
    public function index(Request $request){
        $sqlColumns = ['id_administrativo', 'dni', 'apellido_paterno', 'apellido_materno', 'primer_nombre', 'cargo', 'sueldo'];
        $resource = 'administrativa';

        $maxEntriesShow = $request->input('showing', 10);
        $paginaActual = $request->input('page', 1);
        $search = $request->input('search');

        if (!is_numeric($paginaActual) || $paginaActual <= 0) $paginaActual = 1;
        if (!is_numeric($maxEntriesShow) || $maxEntriesShow <= 0) $maxEntriesShow = 10;
        
        $query = AdministrativoController::doSearch($sqlColumns, $search, $maxEntriesShow);

        if ($paginaActual > $query->lastPage()){
            $paginaActual = 1;
            $request['page'] = $paginaActual;
            $query = AdministrativoController::doSearch($sqlColumns, $search, $maxEntriesShow);
        }
        
        $data = [
            'titulo' => 'Administrativos',
            'columnas' => [
                'ID',
                'Cargo',
                'DNI',
                'Apellido paterno',
                'Apellido materno',
                'Primer nombre',
                'Sueldo'
            ],
            'filas' => [],
            'showing' => $maxEntriesShow,
            'paginaActual' => $paginaActual,
            'totalPaginas' => $query->lastPage(),
            'resource' => $resource,
            'view' => 'administrativo_view',
            'create' => 'administrativo_create',
            'edit' => 'administrativo_edit',
            'delete' => 'administrativo_delete',
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

        foreach ($query as $administrativo){
            array_push($data['filas'],
            [
                $administrativo->id_administrativo,
                $administrativo->cargo,
                $administrativo->dni,
                $administrativo->apellido_paterno,
                $administrativo->apellido_materno,
                $administrativo->primer_nombre,
                $administrativo->sueldo,
            ]); 
        }
        return view('gestiones.administrativo.index', compact('data'));
    }

    public function create(Request $request){
        $data = [
            'return' => route('administrativo_view', ['abort' => true]),
        ];

        return view('gestiones.administrativo.create', compact('data'));
    }

    public function createNewEntry(Request $request){
        $request->validate([
            'apellido_paterno' => 'required|max:50',
            'apellido_materno' => 'required|max:50',
            'primer_nombre' => 'required|max:50',
            'otros_nombres' => 'required',
            'd_n_i' => 'required|max:8',
            'teléfono' => 'required|max:20',
            'seguro_social' => 'required|max:20',
            'estado_civil' => 'required|max:1',
            'dirección' => 'required|max:80',
            'fecha_de_ingreso' => 'required|date',
            'cargo' => 'required|max:255',
            'sueldo' => 'required|numeric|max:999999999',
            'nombre_de_usuario' => 'required|max:50',
            'contraseña' => 'required|max:100',
        ],[
            'apellido_paterno.required' => 'El apellido paterno es obligatorio.',
            'apellido_paterno.max' => 'El apellido paterno no puede superar los 50 caracteres.',
            'apellido_materno.required' => 'El apellido materno es obligatorio.',
            'apellido_materno.max' => 'El apellido materno no puede superar los 50 caracteres.',
            'primer_nombre.required' => 'El primer nombre es obligatorio.',
            'primer_nombre.max' => 'El primer nombre no puede superar los 50 caracteres.',
            'otros_nombres.required' => 'Los otros nombres son obligatorios.',
            'otros_nombres.max' => 'Los otros nombres no pueden superar los 50 caracteres.',
            'd_n_i.required' => 'El DNI es obligatorio.',
            'd_n_i.max' => 'El DNI no puede superar los 8 caracteres.',
            'teléfono.required' => 'El teléfono es obligatorio.',
            'teléfono.max' => 'El teléfono no puede superar los 20 caracteres.',
            'seguro_social.required' => 'El seguro social es obligatorio.',
            'seguro_social.max' => 'El seguro social no puede superar los 20 caracteres.',
            'estado_civil.required' => 'El estado civil es obligatorio.',
            'estado_civil.max' => 'El estado civil no puede superar 1 carácter.',
            'dirección.required' => 'La dirección es obligatoria.',
            'dirección.max' => 'La dirección no puede superar los 80 caracteres.',
            'fecha_de_ingreso.required' => 'La fecha de ingreso es obligatoria.',
            'fecha_de_ingreso.date' => 'La fecha de ingreso debe ser una fecha válida.',
            'cargo.required' => 'El cargo es obligatorio.',
            'cargo.max' => 'El cargo no puede superar los 255 caracteres.',
            'sueldo.required' => 'El sueldo es obligatorio.',
            'sueldo.numeric' => 'El sueldo debe ser un número.',
            'sueldo.max' => 'El sueldo no puede superar los 11 dígitos.',
            'nombre_de_usuario.required' => 'El nombre de usuario es obligatorio.',
            'nombre_de_usuario.max' => 'El nombre de usuario no puede superar los 50 caracteres.',
            'contraseña.required' => 'La contraseña es obligatoria.',
            'contraseña.max' => 'La contraseña no puede superar los 100 caracteres.',
        ]);

        $apellidoPaterno = $request->input('apellido_paterno');
        $apellidoMaterno = $request->input('apellido_materno');
        $primerNombre = $request->input('primer_nombre');
        $otrosNombres = $request->input('otros_nombres');
        $dni = $request->input('d_n_i');
        $telefono = $request->input('teléfono');
        $seguroSocial = $request->input('seguro_social');
        $estadoCivil = $request->input('estado_civil');
        $direccion = $request->input('dirección');
        $fechaIngreso = $request->input('fecha_de_ingreso');
        $cargo = $request->input('cargo');
        $sueldo = $request->input('sueldo');
        $nombreUsuario = $request->input('nombre_de_usuario');
        $contraseña = $request->input('contraseña');

        $createdUser = User::create([
            'username' => $nombreUsuario,
            'password' => bcrypt($contraseña),
            'tipo' => 'Administrativo',
        ]);

        Administrativo::create([
            'id_usuario' => $createdUser->getKey(),
            'apellido_paterno' => $apellidoPaterno,
            'apellido_materno' => $apellidoMaterno,
            'primer_nombre' => $primerNombre,
            'otros_nombres' => $otrosNombres,
            'dni' => $dni,
            'direccion' => $direccion,
            'estado_civil' => $estadoCivil,
            'telefono' => $telefono,
            'seguro_social' => $seguroSocial,
            'fecha_ingreso' => $fechaIngreso,
            'cargo' => $cargo,
            'sueldo' => $sueldo,
        ]);

        return redirect(route('administrativo_view', ['created' => true]));
    }

    public function edit(Request $request, $id){
        if (!isset($id)){
            return redirect(route('administrativo_view'));
        }

        $requested = Administrativo::findOrFail($id);

        $data = [
            'return' => route('administrativo_view', ['abort' => true]),
            'id' => $id,
            'default' => [
                'apellido_paterno' => $requested->apellido_paterno,
                'apellido_materno' => $requested->apellido_materno,
                'primer_nombre' => $requested->primer_nombre,
                'otros_nombres' => $requested->otros_nombres,
                'dni' => $requested->dni,
                'direccion' => $requested->direccion,
                'estado_civil' => $requested->estado_civil,
                'telefono' => $requested->telefono,
                'seguro_social' => $requested->seguro_social,
                'fecha_ingreso' => $requested->fecha_ingreso,
                'cargo' => $requested->cargo,
                'sueldo' => $requested->sueldo,
                'estado' => $requested->estado,
            ]
        ];
        
        return view('gestiones.administrativo.edit', compact('data'));
    }

    public function editEntry(Request $request, $id){
        if (!isset($id)){
            return redirect(route('administrativo_view'));
        }

        $requested = Administrativo::find($id);

        if (isset($requested)){
            $apellidoPaterno = $request->input('apellido_paterno');
            $apellidoMaterno = $request->input('apellido_materno');
            $primerNombre = $request->input('primer_nombre');
            $otrosNombres = $request->input('otros_nombres');
            $dni = $request->input('d_n_i');
            $direccion = $request->input('dirección');
            $estadoCivil = $request->input('estado_civil');
            $telefono = $request->input('teléfono');
            $seguroSocial = $request->input('seguro_social');
            $fechaIngreso = $request->input('fecha_de_ingreso');
            $cargo = $request->input('cargo');
            $sueldo = $request->input('sueldo');

            $requested->update([
                'apellido_paterno' => $apellidoPaterno,
                'apellido_materno' => $apellidoMaterno,
                'primer_nombre' => $primerNombre,
                'otros_nombres' => $otrosNombres,
                'dni' => $dni,
                'direccion' => $direccion,
                'estado_civil' => $estadoCivil,
                'telefono' => $telefono,
                'seguro_social' => $seguroSocial,
                'fecha_ingreso' => $fechaIngreso,
                'cargo' => $cargo,
                'sueldo' => $sueldo,
            ]);
        }

        return redirect(route('administrativo_view', ['edited' => true]));
    }

    public function delete(Request $request){
        $id = $request->input('id');

        $requested = Administrativo::find($id);

        $requested->update(['estado' => '0']);

        return redirect(route('administrativo_view', ['deleted' => true]));
    }
}
