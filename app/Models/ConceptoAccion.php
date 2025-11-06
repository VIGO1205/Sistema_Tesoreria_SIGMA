<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConceptoAccion extends Model
{
    use HasFactory;

    protected $table = 'concepto_accion';

    protected $primaryKey = 'id_concepto_accion'; 
    public $incrementing = true;
    protected $keyType = 'int'; 

    protected $fillable = [
        'accion',
        'estado',
    ];
 
    protected function casts(): array
    {
        return [
            'estado' => 'boolean', 
        ];
    }


}
