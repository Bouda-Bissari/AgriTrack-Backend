<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Candidature extends Model
{
    protected $fillable = [
        'statut',
        'skills',
        'motivationLetter',
        'user_id',
        'intervention_id'
    ];

    protected $casts = [
        'skills' => 'array', // Stocké en JSON
    ];
    /**
     * Travailleur qui a envoyé cette candidature.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    /**
     * Intervention concernée par la candidature.
     */
    public function intervention(): BelongsTo
    {
        return $this->belongsTo(Intervention::class);
    }
}
