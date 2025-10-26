<?php

namespace App\Enums;

enum StatutCompte: string
{
    case Actif = 'actif';
    case Bloque = 'bloqué';
    case Ferme = 'fermé';
}
