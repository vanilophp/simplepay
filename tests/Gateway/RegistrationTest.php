<?php

declare(strict_types=1);

namespace Vanilo\Simplepay\Tests\Gateway;

use Vanilo\Payment\Contracts\PaymentGateway;
use Vanilo\Payment\PaymentGateways;
use Vanilo\Simplepay\SimplepayPaymentGateway;
use Vanilo\Simplepay\Tests\TestCase;

class RegistrationTest extends TestCase
{
    /** @test */
    public function the_gateway_is_registered_out_of_the_box_with_defaults()
    {
        $this->assertCount(2, PaymentGateways::ids());
        $this->assertContains(SimplepayPaymentGateway::DEFAULT_ID, PaymentGateways::ids());
    }

    /** @test */
    public function the_gateway_can_be_instantiated()
    {
        $simplePayGateway = PaymentGateways::make('simplepay');

        $this->assertInstanceOf(PaymentGateway::class, $simplePayGateway);
        $this->assertInstanceOf(SimplepayPaymentGateway::class, $simplePayGateway);
    }
}
