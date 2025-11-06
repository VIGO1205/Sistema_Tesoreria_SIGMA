<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alumno;
use App\Models\Matricula;
use App\Models\Pago;   
use App\Models\Deuda;  
use Carbon\Carbon;


class HomeController extends Controller
{
    

    public function index() {
    $totalAlumnos = Alumno::where('estado',1)->count(); // Aquí obtienes el valor
    $totalMatriculas = Matricula::where('estado',1)->count();

    $mesActual = Carbon::now()->month;
        $anioActual = Carbon::now()->year;

        $totalPagosMes = Pago::whereMonth('fecha_pago', $mesActual) // Filtra por el mes actual de la columna 'fecha'
                             ->whereYear('fecha_pago', $anioActual)   // Filtra por el año actual
                             ->sum('monto');      
        $totalDeudasPendientes = Deuda::where('estado', 1) // O 'estado', 'pendiente', 'status', etc.
                                      ->sum('monto_total');



    return view('administrativo-index', compact('totalAlumnos','totalMatriculas','totalPagosMes','totalDeudasPendientes',)); // Aquí la pasas a la vista

}
}
