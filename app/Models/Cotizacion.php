<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Cotizacion extends Model
{
    use HasFactory;

    protected $table = 'cotizaciones';

    protected $fillable = [
        'requisicion_id',
        'proveedor_id',
        'creado_por',
        'monto',
        'moneda',
        'pdf_path',
        'notas',
        'seleccionada',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'seleccionada' => 'boolean',
    ];

    public function requisicion(): BelongsTo
    {
        return $this->belongsTo(Requisicion::class);
    }

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

    public function ordenCompra(): HasOne
    {
        return $this->hasOne(OrdenCompra::class);
    }
}
