<?php

namespace App\Models;

use App\Enums\TypeTransaction;
use App\Enums\StatutTransaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    public $incrementing = false; // empêche l'auto-incrément
    protected $keyType = 'string'; // UUID = string

    protected $fillable = [
        'montant',
        'date',
        'type_transaction',
        'statut_transaction',
        'compte_id',
    ];

    protected $casts = [
        'type_transaction' => TypeTransaction::class,
        'statut_transaction' => StatutTransaction::class,
    ];

    public function compte(): BelongsTo
    {
        return $this->belongsTo(Compte::class);
    }
}
