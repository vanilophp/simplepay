<?php

declare(strict_types=1);

namespace Vanilo\Simplepay\Exceptions;

use Throwable;

final class InvalidSignatureException extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct('Invalid signature', $code, $previous);
    }
}
