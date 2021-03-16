<?php

declare(strict_types=1);

namespace Vanilo\Simplepay\Concerns;

trait HasSimplepayCredentials
{
    private string $merchanId;

    private string $secretKey;

    private bool $isSandbox;
}
