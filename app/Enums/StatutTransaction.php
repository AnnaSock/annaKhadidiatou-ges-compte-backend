<?php

namespace App\Enums;

enum StatutTransaction: string
{
    case Valide = 'validé';
    case EnAttente = 'en_attente';
    case Annule = 'annulé';
}
