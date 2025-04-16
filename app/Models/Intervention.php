<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Intervention extends Model
{
    protected $fillable = [
        'title',
        'type',
        'isDone',
        'productQuantity',
        'description',
        'land_id'
    ];
    /**
     * Terrain sur lequel cette intervention est réalisée.
     */
    public function land(): BelongsTo
    {
        return $this->belongsTo(Land::class);
    }
    /**
     * Candidatures des travailleurs intéressés par cette intervention.
     */
    public function candidatures(): HasMany
    {
        return $this->hasMany(Candidature::class);
    }
    /**
     * Notifications liées à cette intervention.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }
}
