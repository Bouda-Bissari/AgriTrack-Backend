<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'firstName',
        'lastName',
        'bio',
        'email',
        'phoneNumber',
        'password',
        'role',
        'is_blocked',
        'profilImage',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    /**
     * Terres appartenant à cet utilisateur (si c'est un propriétaire).
     */
    public function lands(): HasMany
    {
        return $this->hasMany(Land::class);
    }
    /**
     * Notifications reçues par l'utilisateur.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }
    /**
     * Candidatures envoyées par un travailleur.
     */
    public function candidatures(): HasMany
    {
        return $this->hasMany(Candidature::class);
    }
}
