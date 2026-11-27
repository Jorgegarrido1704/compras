<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class HistorialEstatus extends Model
{
    use HasFactory;

    protected $table = 'historial_estatus';

    protected $fillable = [
        'trazable_id',
        'trazable_type',
        'estatus_anterior',
        'estatus_nuevo',
        'user_id',
        'comentario',
    ];

    public function trazable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
