<?php

namespace App\Exceptions;

use Exception;

class CompteNotFoundException extends Exception
{
    protected $message = 'Aucun compte trouvé';
    protected $code = 404;
}
