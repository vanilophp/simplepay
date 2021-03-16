<?php

declare(strict_types=1);

namespace Vanilo\Simplepay\Concerns;

trait HasSimplepayConfiguration
{
    use HasSimplepayCredentials;

    private string $returnUrl;
}
