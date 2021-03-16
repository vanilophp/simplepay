<?php

declare(strict_types=1);

namespace Vanilo\Simplepay\Models;

use Konekt\Enum\Enum;

class ResponseStatus extends Enum
{
    public const SUCCESS  = 'SUCCESS';
    public const CANCEL   = 'CANCEL';
    public const FAIL     = 'FAIL';
    public const TIMEOUT  = 'TIMEOUT';
    public const FINISHED = 'FINISHED';
}
