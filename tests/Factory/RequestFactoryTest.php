<?php

declare(strict_types=1);

namespace Vanilo\Simplepay\Tests\Factory;

use Vanilo\Payment\Factories\PaymentFactory;
use Vanilo\Payment\Models\PaymentMethod;
use Vanilo\Simplepay\Factories\RequestFactory;
use Vanilo\Simplepay\Messages\SimplepayPaymentRequest;
use Vanilo\Simplepay\SimplepayPaymentGateway;
use Vanilo\Simplepay\Tests\Dummies\Order;
use Vanilo\Simplepay\Tests\TestCase;

class RequestFactoryTest extends TestCase
{
    /** @test */
    public function it_creates_a_request_object()
    {
        $factory = new RequestFactory('merch', 'secret', true, 'return');
        $method = PaymentMethod::create([
            'gateway' => SimplepayPaymentGateway::getName(),
            'name' => 'SimplePay',
        ]);

        $order = Order::create(['currency' => 'USD', 'amount' => 13.99]);

        $payment = PaymentFactory::createFromPayable($order, $method);

        $this->assertInstanceOf(
            SimplepayPaymentRequest::class,
            $factory->create($payment)
        );
    }
}
