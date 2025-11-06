<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    use HasFactory;

    protected $table = 'matriculas';   
    protected $primaryKey = 'id_matricula';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'id_alumno',
        'año_escolar',
        'fecha_matricula',
        'escala',
        'observaciones',
        'id_grado',
        'nombreSeccion',
        'estado'
    ];


    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'id_alumno', 'id_alumno');
    }

    public function grado()
    {
        return $this->belongsTo(Grado::class, 'id_grado', 'id_grado');
    }

    public function seccion()
    {
        return $this->belongsTo(Seccion::class, 'nombreSeccion', 'nombreSeccion');
    }

    protected function casts(): array
    {
        return [
            'año_escolar' => 'integer',   
            'fecha_matricula' => 'date',    
            'estado' => 'boolean',          
        ];
    }

    public function generarDeudas()
    {
        $meses = [
            1 => 'ENERO', 2 => 'FEBRERO', 3 => 'MARZO', 4 => 'ABRIL',
            5 => 'MAYO', 6 => 'JUNIO', 7 => 'JULIO', 8 => 'AGOSTO',
            9 => 'SETIEMBRE', 10 => 'OCTUBRE', 11 => 'NOVIEMBRE', 12 => 'DICIEMBRE'
        ];

        $anio = $this->año_escolar;
        $escala = $this->escala;
        $idAlumno = $this->id_alumno;

        $mesActual = Carbon::now()->month;

        for ($mes = $mesActual; $mes <= 12; $mes++) {
            $nombreMes = $meses[$mes];

            // Buscar concepto de pago con el formato correcto: "OCTUBRE 2025" y escala "A"
            $concepto = ConceptoPago::where('descripcion', "$nombreMes $anio")
                ->where('escala', $escala)
                ->where('estado', true)
                ->first();

            if (!$concepto) {
                throw new \Exception("No existe concepto de pago para $nombreMes $anio escala $escala");
            }

            $fechaLimite = Carbon::createFromDate($anio, $mes, 1)->endOfMonth()->format('Y-m-d');

            Deuda::create([
                'id_alumno' => $idAlumno,
                'id_concepto' => $concepto->id_concepto,
                'fecha_limite' => $fechaLimite,
                'monto_total' => $concepto->monto,
                'periodo' => "$nombreMes $anio",
                'monto_a_cuenta' => 0,
                'monto_adelantado' => 0,
                'observacion' => null,
                'estado' => true
            ]);
        }
    }


}
