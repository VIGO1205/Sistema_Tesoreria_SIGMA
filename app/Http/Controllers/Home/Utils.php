<?php
namespace App\Http\Controllers\Home;

use App\Helpers\Home\Familiar\FamiliarHeaderComponent;
use App\Models\Familiar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class Utils {
    public static function crearHeaderConAlumnos(Request $request){
        $usuario = Auth::user();
        $familiar = Familiar::whereEstado(true)->whereIdUsuario($usuario->getKey())->first();

        $header = new FamiliarHeaderComponent();
        $header->alumnos = $familiar->alumnos->toArray();
        
        $alumnoSesion = $request->session()->get('alumno');
        $header->alumnoSeleccionado = $alumnoSesion;

        return $header;
    }
}
