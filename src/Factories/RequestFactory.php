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
use Vanilo\Simplepay\Messages\SimplepayPaymentRequest;

final class RequestFactory
{
    public function create(Payment $payment, array $options = []): SimplepayPaymentRequest
    {
        $result = new SimplepayPaymentRequest();

        $result
            ->setCurrency($payment->getCurrency())
            ->setAmount($payment->getAmount());
            // @todo add them based on how it gets initialized
            //->setPosId($this->posId)
            //->setIsSandbox($this->isSandbox);

        // @todo check if these apply to SimplePay
//        if (isset($options['return_url'])) {
//            $result->setReturnUrl($options['return_url']);
//        }
//
//        if (isset($options['cancel_url'])) {
//            $result->setCancelUrl($options['cancel_url']);
//        }

        if (isset($options['view'])) {
            $result->setView($options['view']);
        }

        return $result;
    }
}
