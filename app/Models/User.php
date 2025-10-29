<?php

namespace App\Models;

use App\Enums\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Passport\HasApiTokens;

abstract class User extends Authenticatable
{
    use HasFactory, HasApiTokens;

    public $incrementing = false; // important pour UUID
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'nom',
        'prenom',
        'telephone',
        'email',
        'mot_de_passe',
        'adresse',
        'role',
    ];

    protected $casts = [
        'role' => Role::class,
    ];

    public function comptes(): HasMany
    {
        return $this->hasMany(Compte::class);
    }

    /** 🔽 Important : Discriminateur de classe */
    protected static function booted(): void
    {
        static::creating(function ($user) {
            if (! $user->role) {
                $user->role = static::defaultRole()->value;
            }
        });

        // Résoudre la classe concrète basée sur le rôle
        static::retrieved(function ($user) {
            // Cette méthode est appelée après récupération depuis la DB
            // Laravel devrait déjà connaître la classe concrète à utiliser
        });
    }

    abstract public static function defaultRole(): Role;
}
