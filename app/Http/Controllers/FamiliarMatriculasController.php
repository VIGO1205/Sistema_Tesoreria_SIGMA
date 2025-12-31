<?php

namespace App\Http\Controllers;

use App\Helpers\Home\Familiar\FamiliarHeaderComponent;
use App\Helpers\Home\Familiar\FamiliarSidebarComponent;

use App\Http\Controllers\Home\Utils;
use App\Models\Grado;
use App\Models\Matricula;
use App\Models\Seccion;
use Illuminate\Http\Request;
use App\Helpers\CRUDTablePage;
use App\Helpers\FilteredSearchQuery;
use App\Helpers\RequestHelper;
use App\Helpers\Tables\CRUDTableComponent;
use App\Helpers\Tables\FilterConfig;
use App\Helpers\Tables\PaginatorRowsSelectorComponent;
use App\Helpers\Tables\SearchBoxComponent;
use App\Helpers\Tables\TableButtonComponent;
use App\Helpers\Tables\TableComponent;
use App\Helpers\Tables\TablePaginator;
use Carbon\Carbon;
use App\Helpers\PreMatricula\PromocionHelper;
use App\Helpers\Tables\ViewBasedComponent;

class FamiliarMatriculasController extends Controller
{
    private static function doSearch($sqlColumns, $search, $maxEntriesShow, $appliedFilters = [], Request $request)
    {
        $columnMap = [
            'ID' => 'id_matricula',
            'Año Escolar' => 'año_escolar',
            'Escala' => 'escala',
            'Grado' => 'grado.nombre_grado',
            'Sección' => 'nombreSeccion',
        ];

        $query = Matricula::where('estado', '=', true);

        $requested = $request->session()->get('alumno');
        $query->where('id_alumno', '=', $requested->getKey());

        FilteredSearchQuery::fromQuery($query, $sqlColumns, $search, $appliedFilters, $columnMap);

        $query->orderBy('año_escolar', 'desc');

        if ($maxEntriesShow == null) return $query->get();

        return $query->paginate($maxEntriesShow);
    }

    public function index(Request $request, $long = false)
    {
        $requested = $request->session()->get('alumno');

        if ($requested == null) {
            return redirect(route('principal'));
        }

        $sqlColumns = ['id_matricula', 'año_escolar', 'fecha_matricula', 'escala'];
        $resource = 'pagos';

        $params = RequestHelper::extractSearchParams($request);

        $header = Utils::crearHeaderConAlumnos($request);

        $page = CRUDTablePage::new()
            ->title("Matrículas")
            ->header($header)
            ->sidebar(new FamiliarSidebarComponent());

        $content = CRUDTableComponent::new()
            ->title("Matrículas de tu alumno");

        $filterButton = new TableButtonComponent("tablesv2.buttons.filtros");
        $content->addButton($filterButton);

        // Verificar si puede prematricular
        $infoPrematricula = PromocionHelper::obtenerInfoPrematricula($requested->getKey());

        if ($infoPrematricula['puede_prematricular']) {
            $prematriculaButton = new TableButtonComponent("tablesv2.buttons.prematricula", [
                "redirect" => "familiar_matricula_prematricula_create"
            ]);
            $content->addButton($prematriculaButton);
        }

        /* Paginador */
        $paginatorRowsSelector = new PaginatorRowsSelectorComponent();
        if ($long) $paginatorRowsSelector = new PaginatorRowsSelectorComponent([100]);
        $paginatorRowsSelector->valueSelected = $params->showing;
        $content->paginatorRowsSelector($paginatorRowsSelector);

        /* Searchbox */
        $searchBox = new SearchBoxComponent();
        $searchBox->placeholder = "Buscar...";
        $searchBox->value = $params->search;
        $content->searchBox($searchBox);

        /* Lógica del controller */
        $query = static::doSearch($sqlColumns, $params->search, $params->showing, $params->applied_filters, $request);

        if ($query->lastPage() > 0 && $params->page > $query->lastPage()) {
            $params->page = 1;
            $query = static::doSearch($sqlColumns, $params->search, $params->showing, $params->applied_filters, $request);
        }

        $gradosExistentes = Grado::select("nombre_grado")
            ->distinct()
            ->where("estado", "=", 1)
            ->pluck("nombre_grado");

        $seccionesExistentes = Seccion::select("nombreSeccion")
            ->distinct()
            ->where("estado", "=", 1)
            ->pluck("nombreSeccion");

        $filterConfig = new FilterConfig();
        $filterConfig->filters = [
            "ID", "Año Escolar", "Escala", "Grado", "Sección"
        ];
        $filterConfig->filterOptions = [
            "Escala" => ["A", "B", "C", "D", "E"],
            "Grado" => $gradosExistentes,
            "Sección" => $seccionesExistentes,
        ];
        $content->filterConfig = $filterConfig;

        $table = new TableComponent();
        $table->columns = ["ID", "Año Escolar", "Fecha", "Escala", "Grado", "Sección", "Tipo", "Observaciones"];
        $table->rows = [];

        foreach ($query as $matricula) {
            $tipo = ($matricula->tipo ?? 'matricula') === 'prematricula'
                ? '📋 Pre-matrícula'
                : '✅ Matrícula';

            array_push($table->rows, [
                $matricula->id_matricula,
                $matricula->año_escolar,
                Carbon::parse($matricula->fecha_matricula)->format('d/m/Y'),
                $matricula->escala,
                $matricula->grado->nombre_grado ?? 'N/A',
                $matricula->nombreSeccion ?? 'Por asignar',
                $tipo,
                $matricula->observaciones ?? 'Sin observaciones'
            ]);
        }

        $paginator = new TablePaginator($params->page, $query->lastPage(), [
            'search' => $params->search,
            'showing' => $params->showing,
        ]);
        $table->paginator = $paginator;

        $content->tableComponent($table);

        $page->content($content->build());

        return $page->render();
    }

    public function create(Request $request)
    {
        $alumno = $request->session()->get('alumno');
        if (!$alumno) {
            return redirect(route('principal'));
        }

        $infoPrematricula = PromocionHelper::obtenerInfoPrematricula($alumno->getKey());

        if (!$infoPrematricula['puede_prematricular']) {
            return redirect()->route('familiar_matricula_view')
                ->with('error', $infoPrematricula['mensaje_error']);
        }

        $header = Utils::crearHeaderConAlumnos($request);

        // Si es alumno nuevo, mostrar formulario especial
        if ($infoPrematricula['es_alumno_nuevo']) {
            $gradosDisponibles = PromocionHelper::obtenerGradosDisponibles();
            
            // Obtener secciones agrupadas por grado
            $seccionesPorGrado = Seccion::where('estado', 1)
                ->get()
                ->groupBy('id_grado')
                ->map(function ($secciones) {
                    return $secciones->map(function ($seccion) {
                        return ['nombreSeccion' => $seccion->nombreSeccion];
                    })->values();
                });

            $content = new ViewBasedComponent('homev2.familiares.prematricula_create_nuevo', [
                'data' => [
                    'return' => route('familiar_matricula_view'),
                    'alumno' => $alumno,
                    'info_prematricula' => $infoPrematricula,
                    'grados_disponibles' => $gradosDisponibles,
                    'secciones_por_grado' => $seccionesPorGrado,
                ]
            ]);

            return CRUDTablePage::new()
                ->title("Solicitar Prematrícula - Alumno Nuevo")
                ->header($header)
                ->sidebar(new FamiliarSidebarComponent())
                ->content($content)
                ->render();
        }

        // Alumno existente - promoción automática
        $seccionesDisponibles = Seccion::where('id_grado', $infoPrematricula['siguiente_grado']->id_grado)
            ->where('estado', 1)
            ->get();

        $content = new ViewBasedComponent('homev2.familiares.prematricula_create', [
            'data' => [
                'return' => route('familiar_matricula_view'),
                'alumno' => $alumno,
                'info_prematricula' => $infoPrematricula,
                'secciones_disponibles' => $seccionesDisponibles,
            ]
        ]);

        return CRUDTablePage::new()
            ->title("Registrar Prematrícula")
            ->header($header)
            ->sidebar(new FamiliarSidebarComponent())
            ->content($content)
            ->render();
    }

    public function store(Request $request)
    {
        $alumno = $request->session()->get('alumno');

        if ($alumno == null) {
            return redirect(route('principal'));
        }

        $infoPrematricula = PromocionHelper::obtenerInfoPrematricula($alumno->getKey());

        if (!$infoPrematricula['puede_prematricular']) {
            return redirect()->route('familiar_matricula_view')
                ->with('error', $infoPrematricula['mensaje_error']);
        }

        // Validación (igual para ambos casos ahora)
        $request->validate([
            'id_grado' => 'required|exists:grados,id_grado',
            'nombreSeccion' => 'required|string|max:10',
            'observaciones' => 'nullable|string|max:255',
        ], [
            'id_grado.required' => 'Debe seleccionar un grado.',
            'nombreSeccion.required' => 'Debe seleccionar una sección.',
        ]);

        // Determinar valores según tipo de alumno
        if ($infoPrematricula['es_alumno_nuevo']) {
            $idGrado = $request->input('id_grado');
            $escala = $alumno->escala ?? 'A'; // Escala del alumno
        } else {
            $idGrado = $infoPrematricula['siguiente_grado']->id_grado;
            $escala = $infoPrematricula['ultima_matricula']->escala ?? $alumno->escala ?? 'A';
        }

        // Verificar que la sección existe para el grado
        $seccion = Seccion::findByCompositeKeyOrFail(
            $request->input('id_grado'),
            $request->input('nombreSeccion')
        );

        // Crear la prematrícula
        Matricula::create([
            'id_alumno' => $alumno->getKey(),
            'id_grado' => $idGrado,
            'nombreSeccion' => $seccion->nombreSeccion,
            'año_escolar' => $infoPrematricula['periodo']['año_escolar'],
            'fecha_matricula' => Carbon::now(),
            'escala' => $escala,
            'tipo' => 'prematricula',
            'observaciones' => $request->input('observaciones'),
            'estado' => 1,
        ]);

        return redirect()->route('familiar_matricula_view')
            ->with('success', 'Prematrícula registrada exitosamente para el año escolar ' . $infoPrematricula['periodo']['año_escolar']);
    }
}