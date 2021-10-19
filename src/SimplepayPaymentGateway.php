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
use Vanilo\Simplepay\Concerns\HasSimplepayInteraction;
use Vanilo\Simplepay\Factories\RequestFactory;
use Vanilo\Simplepay\Factories\ResponseFactory;
use Vanilo\Simplepay\Messages\SimplepayFrontendPaymentResponse;

class SimplepayPaymentGateway implements PaymentGateway
{
    use HasSimplepayInteraction;

    public const DEFAULT_ID = 'simplepay';

    private ?RequestFactory $requestFactory = null;

    private ?ResponseFactory $responseFactory = null;

    public static function getName(): string
    {
        return 'SimplePay';
    }

    public function createPaymentRequest(Payment $payment, Address $shippingAddress = null, array $options = []): PaymentRequest
    {
        if (null === $this->requestFactory) {
            $this->requestFactory = new RequestFactory(
                $this->merchanId,
                $this->secretKey,
                $this->isSandbox,
                $this->returnUrl
            );
        }

        return $this->requestFactory->create($payment, $options);
    }

    public function processPaymentResponse(Request $request, array $options = []): PaymentResponse
    {
        if (null === $this->responseFactory) {
            $this->responseFactory = new ResponseFactory(
                $this->merchanId,
                $this->secretKey,
                $this->isSandbox,
                $this->returnUrl
            );
        }

        return $this->responseFactory->create($request, $options);
    }

    public function processFrontendPaymentResponse(Request $request): SimplepayFrontendPaymentResponse
    {
        if (null === $this->responseFactory) {
            $this->responseFactory = new ResponseFactory(
                $this->merchanId,
                $this->secretKey,
                $this->isSandbox,
                $this->returnUrl
            );
        }

        return $this->responseFactory->createFrontendPaymentResponse($request);
    }

    public function isOffline(): bool
    {
        return false;
    }
}
