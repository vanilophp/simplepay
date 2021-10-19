<?php

declare(strict_types=1);

namespace Vanilo\Simplepay\Factories;

use Illuminate\Http\Request;
use Vanilo\Simplepay\Concerns\HasSimplepayInteraction;
use Vanilo\Simplepay\Exceptions\MalformedSimplepayResponseException;
use Vanilo\Simplepay\Messages\SimplepayFrontendPaymentResponse;
use Vanilo\Simplepay\Messages\SimplepayPaymentResponse;

final class ResponseFactory
{
    use HasSimplepayInteraction;

    public function create(Request $request): SimplepayPaymentResponse
    {
        return new SimplepayPaymentResponse(
            $this->merchanId,
            $this->secretKey,
            $this->isSandbox,
            $request->getContent()
        );
    }

    public function createFrontendPaymentResponse(Request $request): SimplepayFrontendPaymentResponse
    {
        if ($request->get('r')) {
            return new SimplepayFrontendPaymentResponse(
                $request->get('r')
            );
        }

        throw new MalformedSimplepayResponseException();
    }
}
