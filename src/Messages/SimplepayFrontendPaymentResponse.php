<?php

declare(strict_types=1);

namespace Vanilo\Simplepay\Messages;

use Vanilo\Simplepay\Models\FrontendReturnStatus;
use Vanilo\Simplepay\Models\ResponseStatus;

class SimplepayFrontendPaymentResponse
{
    private string $paymentId;

    private ?FrontendReturnStatus $returnStatus;

    public function __construct(string $response)
    {
        $payload = json_decode(base64_decode($response, true));

        $this->returnStatus = FrontendReturnStatus::create($payload->e);
        $this->paymentId = $payload->o;
    }

    public function getReturnStatus(): ?FrontendReturnStatus
    {
        return $this->returnStatus;
    }

    public function getPaymentId(): string
    {
        return $this->paymentId;
    }
}
