<?php

namespace App\Models;

use App\Enums\StatutCompte;
use App\Enums\TypeCompte;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Compte extends Model
{
    use HasFactory;

    public $incrementing = false; // empêche l'auto-incrément
    protected $keyType = 'string'; // UUID = string

    protected $fillable = [
        'numero_compte',
        'solde_initial',
        'date_creation',
        'devise',
        'statut_compte',
        'type_compte',
        'version',
        'user_id',
    ];

    protected $casts = [
        'statut_compte' => StatutCompte::class,
        'type_compte' => TypeCompte::class,
    ];

    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
