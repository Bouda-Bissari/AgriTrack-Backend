<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = ['message', 'title', 'user_id', 'intervention_id'];

    /**
     * Utilisateur qui reçoit la notification.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    /**
     * Intervention concernée par la notification.
     */
    public function intervention(): BelongsTo
    {
        return $this->belongsTo(Intervention::class);
    }
}
