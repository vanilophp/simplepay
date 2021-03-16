<?php

declare(strict_types=1);

namespace Vanilo\Simplepay\Models;

use Konekt\Enum\Enum;

class ResponseStatus extends Enum
{
    const SUCCESS = 'SUCCESS';
    const CANCEL  = 'CANCEL';
    const FAIL    = 'FAIL';
    const TIMEOUT = 'TIMEOUT';
}
