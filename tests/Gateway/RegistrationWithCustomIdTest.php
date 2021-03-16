<?php

declare(strict_types=1);

namespace Vanilo\Simplepay\Tests\Gateway;

use Vanilo\Payment\Contracts\PaymentGateway;
use Vanilo\Payment\PaymentGateways;
use Vanilo\Simplepay\SimplepayPaymentGateway;
use Vanilo\Simplepay\Tests\TestCase;

class RegistrationWithCustomIdTest extends TestCase
{
    protected function setUp(): void
    {
        PaymentGateways::reset();
        parent::setUp();
    }

    /** @test */
    public function the_gateway_id_can_be_changed_from_within_the_configuration()
    {
        $this->assertCount(2, PaymentGateways::ids());
        $this->assertContains('yesipay', PaymentGateways::ids());
    }

    /** @test */
    public function the_gateway_can_be_instantiated()
    {
        $simplePayGateway = PaymentGateways::make('yesipay');

        $this->assertInstanceOf(PaymentGateway::class, $simplePayGateway);
        $this->assertInstanceOf(SimplepayPaymentGateway::class, $simplePayGateway);
    }

    protected function resolveApplicationConfiguration($app)
    {
        parent::resolveApplicationConfiguration($app);

        config(['vanilo.simplepay.gateway.id' => 'yesipay']);
    }
}
