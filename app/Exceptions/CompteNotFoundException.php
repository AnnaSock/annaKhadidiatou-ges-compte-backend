<?php

namespace App\Exceptions;

use App\Enums\HttpStatusCodes;
use App\Enums\ResponseMessages;
use Exception;

class CompteNotFoundException extends Exception
{
    protected $message = ResponseMessages::COMPTE_NON_TROUVE;
    protected $code = HttpStatusCodes::NOT_FOUND;
}
