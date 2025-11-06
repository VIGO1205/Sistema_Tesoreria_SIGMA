<?php

namespace App\Helpers\Home\Familiar;

use App\Helpers\Tables\Component;

class FamiliarHeaderComponent implements Component {

    public array $alumnos = [];
    public $alumnoSeleccionado;
    
    public function render(){
        return view('components.familiar.header', ['page' => $this]);
    }
}