<?php

namespace App\Http\Controllers;

use App\Models\SolicitudPrematricula;
use App\Models\Grado;
use App\Models\User;
use App\Models\Alumno;
use App\Models\Familiar;
use App\Models\Matricula;
use App\Models\Seccion;
use App\Helpers\PreMatricula\PromocionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SolicitudPrematriculaController extends Controller
{
    /**
     * Formulario público de solicitud (desde login, sin autenticación)
     */
    public function create()
    {

        $grados = Grado::where('estado', true)->get();

        $parentescos = [
            ['id' => 'Padre', 'descripcion' => 'Padre'],
            ['id' => 'Madre', 'descripcion' => 'Madre'],
            ['id' => 'Tutor Legal', 'descripcion' => 'Tutor Legal'],
            ['id' => 'Abuelo/a', 'descripcion' => 'Abuelo/a'],
            ['id' => 'Tío/a', 'descripcion' => 'Tío/a'],
            ['id' => 'Hermano/a Mayor', 'descripcion' => 'Hermano/a Mayor'],
            ['id' => 'Otro', 'descripcion' => 'Otro'],
        ];

        $sexos = [
            ['id' => 'M', 'descripcion' => 'Masculino'],
            ['id' => 'F', 'descripcion' => 'Femenino'],
        ];

        return view('solicitud_prematricula.create', compact('grados', 'parentescos', 'sexos'));
    }

    /**
     * Guardar solicitud y crear usuario limitado
     */
    public function store(Request $request)
    {
        $request->validate([
            // Datos del apoderado
            'dni_apoderado' => 'required|string|size:8',
            'apellido_paterno_apoderado' => 'required|string|max:50',
            'apellido_materno_apoderado' => 'nullable|string|max:50',
            'primer_nombre_apoderado' => 'required|string|max:50',
            'otros_nombres_apoderado' => 'nullable|string|max:100',
            'numero_contacto' => 'required|string|max:20',
            'correo_electronico' => 'nullable|email|max:100',
            'direccion_apoderado' => 'nullable|string|max:255',
            'parentesco' => 'required|string|max:50',
            
            // Datos del alumno
            'dni_alumno' => 'required|string|size:8|unique:solicitudes_prematricula,dni_alumno|unique:alumnos,dni',
            'apellido_paterno_alumno' => 'required|string|max:50',
            'apellido_materno_alumno' => 'nullable|string|max:50',
            'primer_nombre_alumno' => 'required|string|max:50',
            'otros_nombres_alumno' => 'nullable|string|max:100',
            'sexo' => 'required|in:M,F',
            'fecha_nacimiento' => 'required|date|before:today',
            'direccion_alumno' => 'nullable|string|max:255',
            'telefono_alumno' => 'nullable|string|max:20',
            'colegio_procedencia' => 'nullable|string|max:100',
            'id_grado' => 'required|exists:grados,id_grado',
            
            // Documentos
            'partida_nacimiento' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'certificado_estudios' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'foto_alumno' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'dni_alumno.unique' => 'Ya existe una solicitud o alumno registrado con este DNI.',
            'dni_alumno.size' => 'El DNI del alumno debe tener 8 dígitos.',
            'dni_apoderado.size' => 'El DNI del apoderado debe tener 8 dígitos.',
            'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy.',
            'numero_contacto.required' => 'El número de contacto es obligatorio.',
        ]);

        DB::beginTransaction();

        try {
            // Guardar documentos
            $rutaPartida = null;
            $rutaCertificado = null;
            $rutaFoto = null;

            if ($request->hasFile('partida_nacimiento')) {
                $rutaPartida = $request->file('partida_nacimiento')
                    ->store('solicitudes_prematricula/partidas', 'public');
            }

            if ($request->hasFile('certificado_estudios')) {
                $rutaCertificado = $request->file('certificado_estudios')
                    ->store('solicitudes_prematricula/certificados', 'public');
            }

            if ($request->hasFile('foto_alumno')) {
                $rutaFoto = $request->file('foto_alumno')
                    ->store('solicitudes_prematricula/fotos', 'public');
            }

            // Crear usuario limitado para el apoderado (si no existe)
            $usuario = User::where('username', $request->dni_apoderado)->first();
            
            if (!$usuario) {
                $usuario = User::create([
                    'name' => $request->primer_nombre_apoderado . ' ' . $request->apellido_paterno_apoderado,
                    'username' => $request->dni_apoderado,
                    'tipo' => 'PreApoderado',
                    'email' => $request->correo_electronico ?? $request->dni_apoderado . '@temporal.com',
                    'password' => Hash::make($request->dni_apoderado),
                    'estado' => true,
                ]);
            }

            // Crear solicitud
            $solicitud = SolicitudPrematricula::create([
                // Apoderado
                'dni_apoderado' => $request->dni_apoderado,
                'apellido_paterno_apoderado' => $request->apellido_paterno_apoderado,
                'apellido_materno_apoderado' => $request->apellido_materno_apoderado,
                'primer_nombre_apoderado' => $request->primer_nombre_apoderado,
                'otros_nombres_apoderado' => $request->otros_nombres_apoderado,
                'numero_contacto' => $request->numero_contacto,
                'correo_electronico' => $request->correo_electronico,
                'direccion_apoderado' => $request->direccion_apoderado,
                'parentesco' => $request->parentesco,
                // Alumno
                'dni_alumno' => $request->dni_alumno,
                'apellido_paterno_alumno' => $request->apellido_paterno_alumno,
                'apellido_materno_alumno' => $request->apellido_materno_alumno,
                'primer_nombre_alumno' => $request->primer_nombre_alumno,
                'otros_nombres_alumno' => $request->otros_nombres_alumno,
                'sexo' => $request->sexo,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'direccion_alumno' => $request->direccion_alumno,
                'telefono_alumno' => $request->telefono_alumno,
                'colegio_procedencia' => $request->colegio_procedencia,
                'id_grado' => $request->id_grado,
                // Documentos
                'partida_nacimiento' => $rutaPartida,
                'certificado_estudios' => $rutaCertificado,
                'foto_alumno' => $rutaFoto,
                // Estado
                'estado' => 'pendiente',
                'id_usuario' => $usuario->id_usuario,
            ]);

            DB::commit();

            return redirect()->route('solicitud_prematricula.exito')->with([
                'solicitud_id' => $solicitud->id_solicitud,
                'usuario' => $request->dni_apoderado,
                'password' => $request->dni_apoderado,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Error al procesar la solicitud: ' . $e->getMessage()]);
        }
    }

    /**
     * Página de éxito con credenciales
     */
    public function exito()
    {
        if (!session('solicitud_id')) {
            return redirect()->route('solicitud_prematricula.create');
        }

        return view('solicitud_prematricula.exito');
    }

    /**
     * Vista del pre-apoderado: Estado de su solicitud
     */
    public function estadoSolicitud(Request $request)
    {
        $usuario = auth()->user();
        
        $solicitud = SolicitudPrematricula::where('id_usuario', $usuario->id_usuario)
            ->with('grado')
            ->first();

        if (!$solicitud) {
            abort(404, 'No se encontró solicitud de prematrícula.');
        }

        return view('solicitud_prematricula.estado', compact('solicitud'));
    }

    /**
     * ADMIN: Aprobar solicitud
     */
    public function aprobar(Request $request, $id)
    {
        $solicitud = SolicitudPrematricula::findOrFail($id);

        if ($solicitud->estado === 'aprobada') {
            return back()->withErrors(['error' => 'Esta solicitud ya fue aprobada.']);
        }

        $request->validate([
            'nombreSeccion' => 'required|string|max:10',
            'escala' => 'required|in:A,B,C,D,E',
        ]);

        DB::beginTransaction();

        try {
            // 1. Crear el Familiar (Apoderado)
            $familiar = Familiar::updateOrCreate(
                ['dni' => $solicitud->dni_apoderado],
                [
                    'apellido_paterno' => $solicitud->apellido_paterno_apoderado,
                    'apellido_materno' => $solicitud->apellido_materno_apoderado,
                    'primer_nombre' => $solicitud->primer_nombre_apoderado,
                    'otros_nombres' => $solicitud->otros_nombres_apoderado,
                    'numero_contacto' => $solicitud->numero_contacto,
                    'correo_electronico' => $solicitud->correo_electronico,
                    'estado' => true,
                ]
            );

            // 2. Crear el Alumno
            $alumno = Alumno::create([
                'dni' => $solicitud->dni_alumno,
                'apellido_paterno' => $solicitud->apellido_paterno_alumno,
                'apellido_materno' => $solicitud->apellido_materno_alumno,
                'primer_nombre' => $solicitud->primer_nombre_alumno,
                'otros_nombres' => $solicitud->otros_nombres_alumno,
                'sexo' => $solicitud->sexo,
                'fecha_nacimiento' => $solicitud->fecha_nacimiento,
                'direccion' => $solicitud->direccion_alumno,
                'telefono' => $solicitud->telefono_alumno,
                'colegio_procedencia' => $solicitud->colegio_procedencia,
                'foto' => $solicitud->foto_alumno,
                'escala' => $request->escala,
                'año_ingreso' => date('Y'),
                'estado' => true,
            ]);

            // 3. Vincular Familiar con Alumno
            if (!$familiar->alumnos()->where('id_alumno', $alumno->id_alumno)->exists()) {
                $familiar->alumnos()->attach($alumno->id_alumno, [
                    'parentesco' => $solicitud->parentesco,
                ]);
            }

            // 4. Obtener período de prematrícula
            $periodoPrematricula = PromocionHelper::obtenerPeriodoPrematricula();

            // 5. Crear la Prematrícula
            Matricula::create([
                'id_alumno' => $alumno->id_alumno,
                'id_grado' => $solicitud->id_grado,
                'nombreSeccion' => $request->nombreSeccion,
                'año_escolar' => $periodoPrematricula['año_escolar'],
                'fecha_matricula' => now(),
                'escala' => $request->escala,
                'tipo' => 'prematricula',
                'observaciones' => 'Prematrícula aprobada desde solicitud #' . $solicitud->id_solicitud,
                'estado' => true,
            ]);

            // 6. Actualizar usuario a tipo Familiar
            $usuario = User::find($solicitud->id_usuario);
            if ($usuario) {
                $usuario->update([
                    'tipo' => 'Familiar',
                    'idFamiliar' => $familiar->idFamiliar,
                ]);
            }

            // 7. Actualizar solicitud
            $solicitud->update([
                'estado' => 'aprobada',
                'revisado_por' => auth()->id(),
                'fecha_revision' => now(),
                'observaciones' => $request->observaciones,
            ]);

            DB::commit();

            return back()->with('success', 'Solicitud aprobada. El alumno ha sido registrado con prematrícula.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al aprobar: ' . $e->getMessage()]);
        }
    }

    /**
     * ADMIN: Rechazar solicitud
     */
    public function rechazar(Request $request, $id)
    {
        $request->validate([
            'motivo_rechazo' => 'required|string|max:500',
        ]);

        $solicitud = SolicitudPrematricula::findOrFail($id);

        $solicitud->update([
            'estado' => 'rechazada',
            'motivo_rechazo' => $request->motivo_rechazo,
            'revisado_por' => auth()->id(),
            'fecha_revision' => now(),
        ]);

        return back()->with('success', 'Solicitud rechazada.');
    }
}