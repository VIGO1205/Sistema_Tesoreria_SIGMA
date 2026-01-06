<?php

namespace App\Http\Controllers;

use App\Helpers\CRUDTablePage;
use App\Helpers\Home\Familiar\FamiliarSidebarComponent;
use App\Helpers\Tables\CRUDTableComponent;
use App\Helpers\Tables\ViewBasedComponent;
use App\Http\Controllers\Home\Utils;
use Illuminate\Http\Request;

class FamiliarDatosController extends Controller
{
    public static function index(Request $request){
        $requested = $request->session()->get('alumno');

        if ($requested == null){
            return redirect(route('principal'));
        }
        
        $header = Utils::crearHeaderConAlumnos($request);

        $page = CRUDTablePage::new()
            ->title("Selección de Alumno")
            ->header($header)
            ->sidebar(new FamiliarSidebarComponent());

        $sexos = [
            ['id' => 'M', 'descripcion' => 'Masculino'],
            ['id' => 'F', 'descripcion' => 'Femenino']
        ];

        $estadosciviles = [
            ['id' => 'C', 'descripcion' => 'Casado'],
            ['id' => 'S', 'descripcion' => 'Soltero'],
            ['id' => 'V', 'descripcion' => 'Viudo'],
            ['id' => 'D', 'descripcion' => 'Divorciado']
        ];

        $escalas = [
            ['id' => 'A', 'descripcion' => 'A'],
            ['id' => 'B', 'descripcion' => 'B'],
            ['id' => 'C', 'descripcion' => 'C'],
            ['id' => 'D', 'descripcion' => 'D'],
            ['id' => 'E', 'descripcion' => 'E'],
        ];

        $lenguasmaternas = [
            ['id' => 'Castellano', 'descripcion' => 'Castellano'],
            ['id' => 'Quechua', 'descripcion' => 'Quechua'],
            ['id' => 'Aymara', 'descripcion' => 'Aymara'],
            ['id' => 'Ashaninka', 'descripcion' => 'Asháninka'],
            ['id' => 'Shipibo-Konibo', 'descripcion' => 'Shipibo-Konibo'],
            ['id' => 'Awajun', 'descripcion' => 'Awajún'],
            ['id' => 'Achuar', 'descripcion' => 'Achuar'],
            ['id' => 'Shawi', 'descripcion' => 'Shawi'],
            ['id' => 'Matsigenka', 'descripcion' => 'Matsigenka'],
            ['id' => 'Yanesha', 'descripcion' => 'Yánesha'],
            ['id' => 'Otro', 'descripcion' => 'Otro']
        ];


        $ubigeo = json_decode(file_get_contents(resource_path('data/ubigeo_peru.json')), true);
        $paises = $ubigeo['paises'];
        $departamentos = $ubigeo['departamentos'];
        $provincias = $ubigeo['provincias'];
        $distritos = $ubigeo['distritos'];

        $data = [
            'return' => route('principal'),
            'id' => $requested->getKey(),
            'foto_url' => $requested->foto_url,
            'sexos' => $sexos,
            'paises' => $paises,
            'provincias' => $provincias,
            'distritos' => $distritos,
            'departamentos' => $departamentos,
            'estadosciviles' => $estadosciviles,
            'lenguasmaternas' => $lenguasmaternas,
            'escalas' => $escalas,
            'default' => [
                'codigo_educando' => $requested->codigo_educando,
                'codigo_modular' => $requested->codigo_modular,
                'año_ingreso' => $requested->año_ingreso,
                'd_n_i' => $requested->dni,
                'apellido_paterno' => $requested->apellido_paterno,
                'apellido_materno' => $requested->apellido_materno,
                'primer_nombre' => $requested->primer_nombre,
                'otros_nombres' => $requested->otros_nombres,
                'sexo' => $requested->sexo,
                'fecha_nacimiento' => $requested->fecha_nacimiento,
                'pais' => $requested->pais,
                'departamento' => $requested->departamento,
                'provincia' => $requested->provincia,
                'distrito' => $requested->distrito,
                'lengua_materna' => $requested->lengua_materna,
                'estado_civil' => $requested->estado_civil,
                'religion' => $requested->religion,
                'fecha_bautizo' => $requested->fecha_bautizo,
                'parroquia_de_bautizo' => $requested->parroquia_bautizo,
                'colegio_de_procedencia' => $requested->colegio_procedencia,
                'direccion' => $requested->direccion,
                'telefono' => $requested->telefono,
                'medio_de_transporte' => $requested->medio_transporte,
                'tiempo_de_demora' => $requested->tiempo_demora,
                'material_vivienda' => $requested->material_vivienda,
                'energia_electrica' => $requested->energia_electrica,
                'agua_potable' => $requested->agua_potable,
                'desague' => $requested->desague,
                's_s__h_h' => $requested->ss_hh,
                'numero_de_habitaciones' => $requested->num_habitaciones,
                'numero_de_habitantes' => $requested->num_habitantes,
                'situacion_de_vivienda' => $requested->situacion_vivienda,
                'escala' => $requested->escala,
            ]
        ];
        $content = new ViewBasedComponent('homev2.familiares.datos_view', compact('data'));
        $page->content($content);
        return $page->render();
    }
}
