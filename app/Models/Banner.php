<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'uuid',
    'user_id',
    'estado_id',
    'image_path',
    'titulo',
])]
class Banner extends Model
{
    use SoftDeletes;

    /**
     * Usuario que creó el banner.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Estado del banner.
     */
    public function estado(): BelongsTo
    {
        return $this->belongsTo(Estado::class);
    }
}