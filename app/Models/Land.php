<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Land extends Model
{
    protected $fillable = [
        'name',
        'city',
        'cultureType',
        'area',
        'ownershipdoc',
        'latitude',
        'longitude',
        'statut',
        'user_id'
    ];

    /**
     * Propriétaire du terrain.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    /**
     * Liste des interventions demandées sur ce terrain.
     */
    public function interventions(): HasMany
    {
        return $this->hasMany(Intervention::class);
    }
}
