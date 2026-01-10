<?php

namespace App\Http\Controllers;

use App\Helpers\CRUDTablePage;
use App\Helpers\FilteredSearchQuery;
use App\Helpers\RequestHelper;
use App\Helpers\TableAction;
use App\Helpers\Tables\AdministrativoHeaderComponent;
use App\Helpers\Tables\AdministrativoSidebarComponent;
use App\Helpers\Tables\CautionModalComponent;
use App\Helpers\Tables\CRUDTableComponent;
use App\Helpers\Tables\FilterConfig;
use App\Helpers\Tables\PaginatorRowsSelectorComponent;
use App\Helpers\Tables\SearchBoxComponent;
use App\Helpers\Tables\TableButtonComponent;
use App\Helpers\Tables\TableComponent;
use App\Helpers\Tables\TablePaginator;
use App\Models\Alumno;
use App\Models\ComposicionFamiliar;
use App\Models\Familiar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ComposicionFamiliarController extends Controller
{
    private static function doSearch($sqlColumns, $search, $maxEntriesShow, $appliedFilters = [])
    {
        $query = ComposicionFamiliar::with(['alumno', 'familiar'])
            ->where('estado', 1); // Solo mostrar registros activos

        if (!empty($search)) {
            $query->whereHas('alumno', function ($q) use ($search) {
                $q->where('primer_nombre', 'LIKE', "%{$search}%")
                    ->orWhere('apellido_paterno', 'LIKE', "%{$search}%")
                    ->orWhere('apellido_materno', 'LIKE', "%{$search}%");
            })->orWhereHas('familiar', function ($q) use ($search) {
                $q->where('primer_nombre', 'LIKE', "%{$search}%")
                    ->orWhere('apellido_paterno', 'LIKE', "%{$search}%")
                    ->orWhere('apellido_materno', 'LIKE', "%{$search}%");
            })->orWhere('parentesco', 'LIKE', "%{$search}%");
        }

        if ($maxEntriesShow === null) {
            return $query->get();
        } else {
            return $query->paginate($maxEntriesShow);
        }
    }

    public function index(Request $request, $long = false)
    {
        $sqlColumns = ['id_alumno', 'id_familiar', 'parentesco'];
        $resource = 'alumnos';
        $params = RequestHelper::extractSearchParams($request);

        $page = CRUDTablePage::new()
            ->title("Asignación Familiar-Alumno")
            ->sidebar(new AdministrativoSidebarComponent())
            ->header(new AdministrativoHeaderComponent());

        $content = CRUDTableComponent::new()
            ->title("Composiciones Familiares");

        /* Definición de botones */
        $createNewEntryButton = new TableButtonComponent("tablesv2.buttons.createNewEntry", ["redirect" => "composicion_familiar_create"]);

        if (!$long) {
            $vermasButton = new TableButtonComponent("tablesv2.buttons.vermas", ["redirect" => "composicion_familiar_viewAll"]);
        } else {
            $vermasButton = new TableButtonComponent("tablesv2.buttons.vermenos", ["redirect" => "composicion_familiar_view"]);
            $params->showing = 100;
        }

        $content->addButton($vermasButton);
        $content->addButton($createNewEntryButton);

        /* Paginador */
        $paginatorRowsSelector = new PaginatorRowsSelectorComponent();
        if ($long)
            $paginatorRowsSelector = new PaginatorRowsSelectorComponent([100]);

        $paginatorRowsSelector->valueSelected = $params->showing;
        $content->paginatorRowsSelector($paginatorRowsSelector);

        /* Searchbox */
        $searchBox = new SearchBoxComponent();
        $searchBox->placeholder = "Buscar por nombre de alumno, familiar o parentesco...";
        $searchBox->value = $params->search;
        $content->searchBox($searchBox);

        /* Modales usados */
        $cautionModal = CautionModalComponent::new()
            ->cautionMessage('¿Estás seguro?')
            ->action('Estás eliminando permanentemente esta asignación')
            ->columns(['ID Familiar', 'Nombre Familiar', 'ID Alumno', 'Nombre Alumno', 'Parentesco'])
            ->rows([0, 1, 2, 3, 4]) // Mapea a rows[0]=ID familiar, [1]=nombre familiar, [2]=ID alumno, [3]=nombre alumno, [4]=parentesco
            ->lastWarningMessage('Esta acción no se puede deshacer. La asignación será eliminada permanentemente de la base de datos.')
            ->confirmButton('Sí, eliminar')
            ->cancelButton('Cancelar')
            ->isForm(true)
            // Para IDs separados, si el componente soporta, agrega: ->dataInputNames(['id_familiar' => 0, 'id_alumno' => 2]) o custom
            ->build();

        $page->modals([$cautionModal]);

        /* Lógica del controller */
        $query = static::doSearch($sqlColumns, $params->search, $params->showing, $params->applied_filters);

        if ($params->page > $query->lastPage()) {
            $params->page = 1;
            $query = static::doSearch($sqlColumns, $params->search, $params->showing, $params->applied_filters);
        }

        $table = new TableComponent();
        $table->columns = ["ID Familiar", "Nombre Familiar", "ID Alumno", "Nombre Alumno", "Parentesco"];

        $table->rows = [];
        foreach ($query as $composicion) {
            $nombreFamiliar = trim($composicion->familiar->apellido_paterno . ' ' .
                                   $composicion->familiar->apellido_materno . ' ' .
                                   $composicion->familiar->primer_nombre . ' ' .
                                   $composicion->familiar->otros_nombres);
            $nombreAlumno = trim($composicion->alumno->apellido_paterno . ' ' .
                                 $composicion->alumno->apellido_materno . ' ' .
                                 $composicion->alumno->primer_nombre . ' ' .
                                 $composicion->alumno->otros_nombres);

            // Orden exacto para modal: ID familiar (0), nombre familiar (1), ID alumno (2), nombre alumno (3), parentesco (4)
            // Extras al final para delete (pos 5=id_alumno, 6=id_familiar)
            array_push(
                $table->rows,
                [
                    $composicion->id_familiar,      // 0: ID Familiar (para modal col 0)
                    $nombreFamiliar,                // 1: Nombre Familiar (col 1)
                    $composicion->id_alumno,        // 2: ID Alumno (col 2)
                    $nombreAlumno,                  // 3: Nombre Alumno (col 3)
                    $composicion->parentesco,       // 4: Parentesco (col 4)
                    $composicion->id_alumno,        // 5: id_alumno para hidden delete
                    $composicion->id_familiar       // 6: id_familiar para hidden delete
                ]
            );
        }

        $table->actions = [
            new TableAction('delete', '', $resource),  // El componente debe usar pos 5 y 6 para hidden id_alumno/id_familiar
        ];

        $paginator = new TablePaginator($params->page, $query->lastPage(), [
            'search' => $params->search,
            'showing' => $params->showing,
            'applied_filters' => $params->applied_filters
        ]);

        $table->paginator = $paginator;
        $content->tableComponent($table);
        $page->content($content->build());

        return $page->render();
    }

    public function viewAll(Request $request)
    {
        return static::index($request, true);
    }

    public function create()
    {
        // Obtener todos los alumnos activos
        $alumnos = Alumno::where('estado', true)
            ->orderBy('apellido_paterno')
            ->orderBy('apellido_materno')
            ->orderBy('primer_nombre')
            ->get();

        $alumnosData = [];
        foreach ($alumnos as $alumno) {
            $alumnosData[] = [
                'id' => $alumno->id_alumno,
                'nombre' => trim($alumno->apellido_paterno . ' ' .
                                $alumno->apellido_materno . ' ' .
                                $alumno->primer_nombre . ' ' .
                                $alumno->otros_nombres)
            ];
        }

        // Obtener todos los familiares activos
        $familiares = Familiar::where('estado', true)
            ->orderBy('apellido_paterno')
            ->orderBy('apellido_materno')
            ->orderBy('primer_nombre')
            ->get();

        $familiaresData = [];
        foreach ($familiares as $familiar) {
            $familiaresData[] = [
                'id' => $familiar->idFamiliar,
                'nombre' => trim($familiar->apellido_paterno . ' ' .
                                 $familiar->apellido_materno . ' ' .
                                 $familiar->primer_nombre . ' ' .
                                 $familiar->otros_nombres)
            ];
        }

        $parentescos = [
            ['id' => 'Padre', 'descripcion' => 'Padre'],
            ['id' => 'Madre', 'descripcion' => 'Madre'],
            ['id' => 'Tutor', 'descripcion' => 'Tutor'],
            ['id' => 'Abuelo', 'descripcion' => 'Abuelo'],
            ['id' => 'Abuela', 'descripcion' => 'Abuela'],
            ['id' => 'Tío', 'descripcion' => 'Tío'],
            ['id' => 'Tía', 'descripcion' => 'Tía'],
        ];

        $data = [
            'return' => route('composicion_familiar_view', ['abort' => true]),
            'alumnos' => $alumnosData,
            'familiares' => $familiaresData,
            'parentescos' => $parentescos,
        ];

        return view('gestiones.composicion_familiar.create', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_alumno' => 'required|exists:alumnos,id_alumno',
            'id_familiar' => 'required|exists:familiares,idFamiliar',
            'parentesco' => 'required|string|in:Padre,Madre,Tutor,Abuelo,Abuela,Tío,Tía',
            'estado' => 'nullable|integer'
        ], [
            'id_alumno.required' => 'Debe seleccionar un alumno.',
            'id_alumno.exists' => 'El alumno seleccionado no existe.',
            'id_familiar.required' => 'Debe seleccionar un familiar.',
            'id_familiar.exists' => 'El familiar seleccionado no existe.',
            'parentesco.required' => 'Debe seleccionar el parentesco.',
            'parentesco.in' => 'El parentesco seleccionado no es válido.',
        ]);

        // Verificar si la composición ya existe
        $existingComposition = ComposicionFamiliar::where('id_alumno', $request->id_alumno)
            ->where('id_familiar', $request->id_familiar)
            ->first();

        if ($existingComposition) {
            return redirect()->back()
                ->withErrors(['id_familiar' => 'Esta asignación ya existe.'])
                ->withInput();
        }

        // Crear la asignación
        ComposicionFamiliar::create([
            'id_alumno' => $request->id_alumno,
            'id_familiar' => $request->id_familiar,
            'parentesco' => $request->parentesco,
            'estado' => $request->estado ?? 1, // Por defecto estado = 1
        ]);

        return redirect()->route('composicion_familiar_view')
            ->with('success', 'Asignación creada exitosamente.');
    }

    public function delete(Request $request)
    {
        try {
            Log::info('Delete iniciado. IDs recibidos: alumno=' . ($request->input('id_alumno') ?? 'null') . ', familiar=' . ($request->input('id_familiar') ?? 'null'));

            // Obtener IDs separados del formulario
            $idAlumno = $request->input('id_alumno');
            $idFamiliar = $request->input('id_familiar');

            if (!$idAlumno || !$idFamiliar) {
                Log::warning('Delete falló: IDs no proporcionados.');
                return redirect()->route('composicion_familiar_view')
                    ->with('error', 'IDs no proporcionados en la solicitud.');
            }

            // Validar que sean enteros positivos
            $idAlumno = (int) $idAlumno;
            $idFamiliar = (int) $idFamiliar;

            if ($idAlumno <= 0 || $idFamiliar <= 0) {
                Log::warning('Delete falló: IDs inválidos - alumno=' . $idAlumno . ', familiar=' . $idFamiliar);
                return redirect()->route('composicion_familiar_view')
                    ->with('error', 'IDs deben ser números positivos válidos.');
            }

            Log::info('IDs validados: alumno=' . $idAlumno . ', familiar=' . $idFamiliar);

            // Buscar por PK compuesta
            $composicion = ComposicionFamiliar::where('id_alumno', $idAlumno)
                ->where('id_familiar', $idFamiliar)
                ->first();

            if (!$composicion) {
                Log::warning('Delete falló: Registro no encontrado - alumno=' . $idAlumno . ', familiar=' . $idFamiliar);
                return redirect()->route('composicion_familiar_view')
                    ->with('error', 'Asignación no encontrada. Verifica los datos.');
            }

            Log::info('Registro encontrado: Estado actual=' . ($composicion->estado ? 'true' : 'false'));

            // Verificar que no esté ya desactivada
            if (!$composicion->estado) {
                Log::info('Delete omitido: Ya desactivado');
                return redirect()->route('composicion_familiar_view')
                    ->with('warning', 'La asignación ya está desactivada.');
            }

            // Soft delete
            $composicion->estado = false;
            $saved = $composicion->save();

            if ($saved) {
                Log::info('Delete exitoso: Asignación desactivada');
                return redirect()->route('composicion_familiar_view')
                    ->with('success', 'Asignación desactivada correctamente.');
            } else {
                Log::error('Delete falló en save()');
                return redirect()->route('composicion_familiar_view')
                    ->with('error', 'Error al actualizar el estado.');
            }

        } catch (\Exception $e) {
            Log::error('Delete error general: ' . $e->getMessage());
            return redirect()->route('composicion_familiar_view')
                ->with('error', 'Error al procesar la eliminación. Intenta de nuevo.');
        }
    }
}
