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

use Illuminate\Http\Request;
use Vanilo\Payment\Contracts\PaymentResponse;

class SimplepayPaymentResponse implements PaymentResponse
{
    private Request $request;

    private string $paymentId;

    private ?float $amountPaid = null;

    public function __construct(Request $request, string $posId, bool $isSandbox)
    {
        $this->request = $request;
        $this->posId = $posId;
        $this->isSandbox = $isSandbox;
    }

    public function wasSuccessful(): bool
    {
        return true;
    }

    public function getMessage(): string
    {
        return '';
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
}
