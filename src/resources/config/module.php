<?php

declare(strict_types=1);

use Vanilo\Simplepay\SimplepayPaymentGateway;

return [
    'gateway'     => [
        'register' => true,
        'id'       => SimplepayPaymentGateway::DEFAULT_ID
    ],
    'bind'        => true,
    'merchant_id' => env('SIMPLEPAY_MERCHANT_ID', ''),
    'secret_key'  => env('SIMPLEPAY_SECRET_KEY', ''),
    'sandbox'     => (bool)env('SIMPLEPAY_SANDBOX', false),
    'return_url'  => env('SIMPLEPAY_RETURN_URL', '')
];
