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
    }

    abstract public static function defaultRole(): Role;
}
