<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequisicionArchivo extends Model
{
    use HasFactory;

    protected $table = 'requisicion_archivos';

    protected $fillable = [
        'requisicion_id',
        'nombre_original',
        'ruta',
        'tipo',
    ];

    public function requisicion()
    {
        return $this->belongsTo(
            Requisicion::class,
            'requisicion_id'
        );
    }
}
