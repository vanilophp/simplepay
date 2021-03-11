<?php

declare(strict_types=1);

/**
 * Contains the SimplepayPaymentRequest class.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-03-11
 *
 */

namespace Vanilo\Simplepay\Messages;

use Illuminate\Support\Facades\View;
use Vanilo\Payment\Contracts\PaymentRequest;

class SimplepayPaymentRequest implements PaymentRequest
{
    private string $currency;

    private float $amount;

    private string $view = 'simplepay::_request';

    private string $posId;

    private bool $isSandbox = false;

    public function getHtmlSnippet(array $options = []): ?string
    {
        return View::make(
            $this->view,
            [
                'url' => '', // @todo add parameters
                'autoRedirect' => $options['autoRedirect'] ?? false
            ]
        )->render();
    }

    public function willRedirect(): bool
    {
        return true;
    }

    public function setPosId(string $posId): self
    {
        $this->posId = $posId;

        return $this;
    }

    public function setIsSandbox(bool $isSandbox): SimplepayPaymentRequest
    {
        $this->isSandbox = $isSandbox;

        return $this;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function setView(string $view): self
    {
        $this->view = $view;

        return $this;
    }
}
