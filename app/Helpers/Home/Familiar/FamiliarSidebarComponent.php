<?php

namespace App\Helpers\Home\Familiar;

use App\Helpers\Tables\Component;

class FamiliarSidebarComponent implements Component {
    public function render(){
        return view('components.familiar.sidebar');
    }
}