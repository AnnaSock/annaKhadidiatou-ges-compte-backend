<?php

namespace App\Enums;

enum TypeTransaction: string
{
    case Depot = 'dépôt';
    case Retrait = 'retrait';
    case Virement = 'virement';
    case Frais = 'frais';
}
