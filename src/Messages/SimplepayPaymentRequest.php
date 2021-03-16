<?php

declare(strict_types=1);

/**
 * Contains the SimplepayPaymentRequest class.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-03-11
 *
 */

namespace Vanilo\Simplepay\Messages;

use Illuminate\Support\Facades\View;
use Vanilo\Payment\Contracts\PaymentRequest;
use Vanilo\Simplepay\Concerns\HasSimplepayConfiguration;
use Vanilo\Simplepay\Exceptions\InvalidSignatureException;
use Vanilo\Simplepay\Exceptions\InvalidSimplepayRequestException;
use Vanilo\Simplepay\Support\Hash;

class SimplepayPaymentRequest implements PaymentRequest
{
    use HasSimplepayConfiguration;

    private ?string $email;

    private string $name;

    private string $currency;

    private string $lang = 'HU';

    private string $paymentId;

    private float $amount;

    private string $view = 'simplepay::_request';


    public function getHtmlSnippet(array $options = []): ?string
    {
        return View::make(
            $this->view,
            [
                'url'          => $this->createRemoteRequest(),
                'autoRedirect' => $options['autoRedirect'] ?? false
            ]
        )->render();
    }

    private function createRemoteRequest(): string
    {
        $trx = new \SimplePayStart;
        $trx->addConfig([
            'HUF_MERCHANT'   => $this->merchanId,
            'HUF_SECRET_KEY' => $this->secretKey,
            'SANDBOX'        => $this->isSandbox,
            'AUTOCHALLENGE'  => true
        ]);

        $trx->addData('currency', $this->currency);
        $trx->addData('orderRef', $this->paymentId);
        $trx->addData('methods', ['CARD']);
        $trx->addData('total', $this->amount);
        $trx->addData('customerEmail', $this->email);
        $trx->addData('language', $this->lang);
        $trx->addData('timeout', date('c', time() + 600));
        $trx->addData('url', $this->returnUrl);
        $trx->runStart();

        $responseData = $trx->getReturnData();

        if ($responseData && array_key_exists('errorCodes', $responseData)) {
            throw new InvalidSimplepayRequestException(
                sprintf(
                    'Probably the request is missing a required param. Please check the documentation for the following error code: %s',
                    $responseData['errorCodes'][0]
                ));
        }

        if (!$responseData['responseSignatureValid']) {
            throw new InvalidSignatureException('Response missing or not valied');
        }

        return $responseData['paymentUrl'];
    }

    public function willRedirect(): bool
    {
        return true;
    }

    public function setReturnUrl(string $returnUrl): self
    {
        $this->returnUrl = $returnUrl;

        return $this;
    }

    public function setMerchanId(string $merchanId): self
    {
        $this->merchanId = $merchanId;

        return $this;
    }

    public function setSecretKey(string $secretKey): self
    {
        $this->secretKey = $secretKey;

        return $this;
    }

    public function setIsSandbox(bool $isSandbox): self
    {
        $this->isSandbox = $isSandbox;

        return $this;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function setPaymentId(string $paymentId): self
    {
        $this->paymentId = $paymentId;

        return $this;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function setView(string $view): self
    {
        $this->view = $view;

        return $this;
    }

    public function setLang(string $lang): self
    {
        $this->lang = $lang;

        return $this;
    }
}
