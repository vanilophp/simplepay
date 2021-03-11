<?php

declare(strict_types=1);

/**
 * Contains the ModuleServiceProvider class.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-03-11
 *
 */

namespace Vanilo\Simplepay\Providers;

use Konekt\Concord\BaseModuleServiceProvider;
use Vanilo\Payment\PaymentGateways;
use Vanilo\Simplepay\SimplepayPaymentGateway;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    public function boot()
    {
        parent::boot();

        if ($this->config('gateway.register', true)) {
            PaymentGateways::register(
                $this->config('gateway.id', SimplepayPaymentGateway::DEFAULT_ID),
                SimplepayPaymentGateway::class
            );
        }

        if ($this->config('bind', true)) {
            $this->app->bind(SimplepayPaymentGateway::class, function ($app) {
                return new SimplepayPaymentGateway(
                    $this->config('pos_id'),
                    $this->config('sandbox')
                );
            });
        }

        $this->publishes([
            $this->getBasePath() . '/' . $this->concord->getConvention()->viewsFolder() =>
            resource_path('views/vendor/simplepay'),
            'simplepay'
        ]);
    }
}
