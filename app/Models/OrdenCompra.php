<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class OrdenCompra extends Model
{
    use HasFactory;

    protected $table = 'ordenes_compra';

    protected $fillable = [
        'cotizacion_id',
        'folio',
        'autorizado_por',
        'estatus',
        'motivo_rechazo',
        'fecha_autorizacion',
        'fecha_pago_programada',
        'fecha_pago_real',
        'pdf_path',
        'factura_path',
    ];

    protected $casts = [
        'fecha_autorizacion' => 'date',
        'fecha_pago_programada' => 'date',
        'fecha_pago_real' => 'date',
    ];

    public function cotizacion(): BelongsTo
    {
        return $this->belongsTo(Cotizacion::class);
    }

    public function requisicion(): HasOneThrough
    {
        return $this->hasOneThrough(
            Requisicion::class,
            Cotizacion::class,
            'id',            // FK en cotizaciones que referencia... (local key de cotizacion)
            'id',            // PK en requisiciones
            'cotizacion_id', // FK local en ordenes_compra
            'requisicion_id' // FK en cotizaciones

        );
    }

    public function autorizadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'autorizado_por');
    }

    public function historial(): MorphMany
    {
        return $this->morphMany(HistorialEstatus::class, 'trazable');
    }

    /**
     * Genera el siguiente folio consecutivo tipo PO-2026-0001
     */
    public static function generarFolio(): string
    {
        $anio = now()->year;
        $ultimo = static::where('folio', 'like', "PO-{$anio}-%")
            ->orderByDesc('id')
            ->first();

        $consecutivo = $ultimo
            ? ((int) substr($ultimo->folio, -4)) + 1
            : 1;

        return sprintf('PO-%d-%04d', $anio, $consecutivo);
    }

    public static function generarFolioServicio(): string
    {
        $anio = now()->year;
        $ultimo = static::where('folio', 'like', "SERV-{$anio}-%")
            ->orderByDesc('id')
            ->first();

        $consecutivo = $ultimo
            ? ((int) substr($ultimo->folio, -4)) + 1
            : 1;

        return sprintf('SERV-%d-%04d', $anio, $consecutivo);
    }

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
