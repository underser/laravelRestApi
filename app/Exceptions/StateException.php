<?php
declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use JetBrains\PhpStorm\Pure;

class StateException extends Exception
{
    #[Pure] public function __construct(string $message = "Entity can't accept this state.", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
