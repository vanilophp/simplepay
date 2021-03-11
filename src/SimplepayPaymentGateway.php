<?php

declare(strict_types=1);

/**
 * Contains the SimplepayPaymentGateway class.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-03-11
 *
 */

namespace Vanilo\Simplepay;

use Illuminate\Http\Request;
use Vanilo\Contracts\Address;
use Vanilo\Payment\Contracts\Payment;
use Vanilo\Payment\Contracts\PaymentGateway;
use Vanilo\Payment\Contracts\PaymentRequest;
use Vanilo\Payment\Contracts\PaymentResponse;
use Vanilo\Simplepay\Factories\RequestFactory;
use Vanilo\Simplepay\Messages\SimplepayPaymentResponse;

class SimplepayPaymentGateway implements PaymentGateway
{
    public const DEFAULT_ID = 'simplepay';

    private ?RequestFactory $requestFactory = null;

    public static function getName(): string
    {
        return 'SimplePay';
    }

    public function createPaymentRequest(Payment $payment, Address $shippingAddress = null, array $options = []): PaymentRequest
    {
        if (null === $this->requestFactory) {
            $this->requestFactory = new RequestFactory(
                // $this->posId,
                // $this->returnUrl,
                // $this->cancelUrl,
                // $this->isSandbox
            );
        }

        return $this->requestFactory->create($payment, $options);
    }

    public function processPaymentResponse(Request $request, array $options = []): PaymentResponse
    {
        return new SimplepayPaymentResponse($request, $this->posId, $this->isSandbox);
    }

    public function isOffline(): bool
    {
        return false;
    }
}
