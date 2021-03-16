<?php

declare(strict_types=1);

/**
 * Contains the RequestFactory class.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-03-11
 *
 */

namespace Vanilo\Simplepay\Factories;

use Vanilo\Payment\Contracts\Payment;
use Vanilo\Simplepay\Concerns\HasSimplepayInteraction;
use Vanilo\Simplepay\Messages\SimplepayPaymentRequest;

final class RequestFactory
{
    use HasSimplepayInteraction;

    public function create(Payment $payment, array $options = []): SimplepayPaymentRequest
    {
        $result    = new SimplepayPaymentRequest();
        $billPayer = $payment->getPayable()->getBillPayer();

        $result
            ->setMerchanId($this->merchanId)
            ->setSecretKey($this->secretKey)
            ->setIsSandbox($this->isSandbox)
            ->setReturnUrl($this->returnUrl)
            ->setCurrency($payment->getCurrency())
            ->setAmount($payment->getAmount())
            ->setPaymentId($payment->getPaymentId())
            ->setEmail($billPayer->getEmail())
            ->setName($billPayer->getFullName());

        if (isset($options['return_url'])) {
            $result->setReturnUrl($options['return_url']);
        }

        if (isset($options['lang'])) {
            $result->setLang($options['lang']);
        }

        if (isset($options['view'])) {
            $result->setView($options['view']);
        }

        return $result;
    }
}
