<?php

declare(strict_types=1);

use Vanilo\Simplepay\SimplepayPaymentGateway;

return [
    'gateway' => [
        'register' => true,
        'id' => SimplepayPaymentGateway::DEFAULT_ID
    ],
    'bind' => true,
    'pos_id' => env('SIMPLEPAY_POS_ID'),
    'sandbox' => (bool) env('SIMPLEPAY_USE_SANDBOX', false),
];
