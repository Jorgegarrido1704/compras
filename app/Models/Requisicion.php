<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Requisicion extends Model
{
    use HasFactory;

    protected $table = 'requisiciones';

    protected $fillable = [
        'solicitante_id',
        'titulo',
        'descripcion',
        'tipo',
        'costo_servicio',
        'prioridad',
        'estatus',
        'fecha_pago_programada',
        'fecha_pago_real',
    ];

    protected $casts = [
        'costo_servicio' => 'decimal:2',
        'fecha_pago_programada' => 'date',
        'fecha_pago_real' => 'date',
    ];

    public function solicitante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'solicitante_id');
    }

    public function archivos()
    {
        return $this->hasMany(
            RequisicionArchivo::class,
            'requisicion_id'
        );
    }

    public function cotizaciones(): HasMany
    {
        return $this->hasMany(Cotizacion::class);
    }

    public function historial(): MorphMany
    {
        return $this->morphMany(HistorialEstatus::class, 'trazable');
    }

    /**
     * Cambia el estatus y deja registro en el historial de auditoría.
     */
    public function cambiarEstatus(string $nuevoEstatus, int $userId, ?string $comentario = null): void
    {
        $anterior = $this->estatus;
        $this->estatus = $nuevoEstatus;
        $this->save();

        $this->historial()->create([
            'estatus_anterior' => $anterior,
            'estatus_nuevo' => $nuevoEstatus,
            'user_id' => $userId,
            'comentario' => $comentario,
        ]);
    }
}
