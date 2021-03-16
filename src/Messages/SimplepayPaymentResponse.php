<?php

declare(strict_types=1);

/**
 * Contains the SimplepayPaymentResponse class.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-03-11
 *
 */

namespace Vanilo\Simplepay\Messages;

use Vanilo\Payment\Contracts\PaymentResponse;
use Vanilo\Simplepay\Models\ResponseStatus;

class SimplepayPaymentResponse implements PaymentResponse
{
    private string $response;

    private string $signature;

    private string $paymentId;

    private string $transactionId;

    private ?float $amountPaid = null;

    private ResponseStatus $status;

    public function __construct(string $response, string $signature)
    {
        $this->response = $response;
        $this->signature = $signature;

        $this->resolve();
    }

    public function wasSuccessful(): bool
    {
        return $this->status->equals(ResponseStatus::SUCCESS());
    }

    public function getStatus(): ResponseStatus
    {
        return $this->status;
    }

    public function getMessage(): string
    {
        return $this->status->value();
    }

    public function getTransactionId(): ?string
    {
        return $this->paymentId;
    }

    public function getAmountPaid(): ?float
    {
        return $this->amountPaid;
    }

    public function getPaymentId(): string
    {
        return $this->paymentId;
    }

    private function resolve(): void
    {
        $payload = json_decode(base64_decode($this->response, true));

        $this->status = ResponseStatus::create($payload->e);
        $this->paymentId = $payload->o;
        $this->transactionId = (string) $payload->t;
    }
}
