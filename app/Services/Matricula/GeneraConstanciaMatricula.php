<?php

namespace App\Services\Matricula;

use App\Interfaces\IGeneraConstancia;
use App\Interfaces\IGeneraConstanciaComoResponse;
use App\Models\Matricula;
use Barryvdh\DomPDF\Facade\Pdf;

class GeneraConstanciaMatricula implements IGeneraConstancia, IGeneraConstanciaComoResponse
{
    private string $template;

    public function __construct(string $template)
    {
        $this->template = $template;
    }

    private function getDefaultOptions(): array
    {
        $options = [
            'defaultFont' => 'Arial',
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => false,
        ];

        return $options;
    }

    private function generarPDF(Matricula $matricula)
    {
        $alumno = $matricula->alumno;
        $grado = $matricula->grado;
        $nivel_educativo = $grado->nivelEducativo;

        $pdf = Pdf::loadView($this->template, [
            'operator_name' => 'nombre sds',
            'operator_username' => 'robertoa1',
            'student_dni' => $alumno->dni,
            'student_name' => join(' ', [$alumno->apellido_paterno, $alumno->apellido_materno, $alumno->primer_nombre, $alumno->otros_nombres]),
            'year' => $matricula->año_escolar,
            'level' => $nivel_educativo->nombre_nivel,
            'grade' => $grado->nombre_grado,
            'section' => $matricula->seccion->nombreSeccion,
        ]);

        $pdf->setOptions($this->getDefaultOptions());

        return $pdf;
    }

    private function obtenerMatricula(int $id): Matricula|null
    {
        return Matricula::find($id);
    }

    public function generar(int $id)
    {
        $matricula = $this->obtenerMatricula($id);

        if ($matricula === null) {
            abort(400, "La matrícula especificada no existe.");
        }

        return $this->generarPDF($matricula);
    }

    public function generarAsResponse(int $id)
    {
        return $this->generar($id)->stream('constancia_matricula_' . $id . '.pdf');
    }
}