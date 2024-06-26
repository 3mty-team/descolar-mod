<?php

namespace App\Repository\Report\EnvReader\Exceptions;

use Throwable;

class EnvAlreadyExistsException extends EnvException
{

    public function __construct($message, int $code = 500, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}