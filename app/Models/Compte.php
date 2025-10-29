<?php

namespace App\Models;

use App\Enums\StatutCompte;
use App\Enums\TypeCompte;
use App\Enums\TypeTransaction;
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

    protected $appends = ['solde', 'titulaire'];

    protected $fillable = [
        'id',
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

    /**
     * Relation vers l'utilisateur titulaire
     */
    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'user_id');
    }

    /**
     * Relation vers les transactions
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    // -------------------
    // Scopes
    // -------------------

    /**
     * Scope global pour tous les comptes non supprimés
     */
    protected static function booted()
    {
        static::addGlobalScope('not_deleted', function (Builder $builder) {
            $builder->whereNull('deleted_at');
        });
    }

    /**
     * Scope local pour récupérer les comptes actifs
     */
    public function scopeActifs(Builder $query): Builder
    {
        return $query->where('statut_compte', StatutCompte::Actif->value);
    }

    /**
     * Scope pour filtrer les comptes par type (chèque ou épargne)
     */
    public function scopeTypeValide(Builder $query): Builder
    {
        return $query->whereIn('type_compte', [TypeCompte::Cheque->value, TypeCompte::Epargne->value]);
    }

    /**
     * Scope local pour récupérer un compte par son numéro
     */
    public function scopeNumero(Builder $query, string $numero): Builder
    {
        return $query->where('numero_compte', $numero);
    }

    /**
     * Scope local pour récupérer les comptes d'un client par téléphone
     */
    public function scopeClient(Builder $query, string $telephone): Builder
    {
        return $query->whereHas('utilisateur', function (Builder $q) use ($telephone) {
            $q->where('telephone', $telephone);
        });
    }

    /**
     * Scope pour filtrer selon le rôle (Admin/Client)
     */
    public function scopeParRole(Builder $query, string $role, ?string $userId = null): Builder
    {
        if ($role === 'client' && $userId) {
            return $query->where('user_id', $userId);
        }

        // si admin, on retourne tout
        return $query;
    }


    /**
     * Accessor pour récupérer le titulaire du compte (nom + prénom)
     */
    public function getTitulaireAttribute(): string
    {
        return $this->utilisateur->nom . ' ' . $this->utilisateur->prenom;
    }

    public function getSoldeAttribute(): float
    {
        $depots = $this->transactions()->where('type_transaction', TypeTransaction::Depot->value)->sum('montant');
        $retraits = $this->transactions()->where('type_transaction', TypeTransaction::Retrait->value)->sum('montant');

        return $this->solde_initial + $depots - $retraits;
    }
}
