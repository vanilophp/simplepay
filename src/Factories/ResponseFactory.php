<?php

declare(strict_types=1);

namespace Vanilo\Simplepay\Factories;

use Illuminate\Http\Request;
use Vanilo\Simplepay\Concerns\HasSimplepayInteraction;
use Vanilo\Simplepay\Exceptions\MalformedSimplepayResponseException;
use Vanilo\Simplepay\Messages\SimplepayPaymentResponse;

final class ResponseFactory
{
    use HasSimplepayInteraction;

    public function create(Request $request, array $options = []): SimplepayPaymentResponse
    {
        if ($request->get('r') && $request->get('s')) {
            return new SimplepayPaymentResponse(
                $this->merchanId,
                $this->secretKey,
                $this->isSandbox,
                $request->get('r'),
                $request->get('s')
            );
        }

        throw new MalformedSimplepayResponseException();
    }
}
