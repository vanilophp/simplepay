<?php

declare(strict_types=1);

namespace Vanilo\Simplepay\Concerns;

trait HasSimplepayInteraction
{
    use HasSimplepayConfiguration;

    public function __construct(string $merchanId, string $secretKey, bool $isSandbox, string $returnUrl)
    {
        $this->merchanId = $merchanId;
        $this->secretKey = $secretKey;
        $this->isSandbox = $isSandbox;
        $this->returnUrl = $returnUrl;
    }
}
