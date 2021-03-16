<?php

declare(strict_types=1);

/**
 * Contains the SimplepayPaymentResponse class.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-03-11
 *
 */

namespace Vanilo\Simplepay\Messages;

use Illuminate\Support\Arr;
use Vanilo\Payment\Contracts\PaymentResponse;
use Vanilo\Simplepay\Concerns\HasSimplepayCredentials;
use Vanilo\Simplepay\Exceptions\InvalidSignatureException;
use Vanilo\Simplepay\Exceptions\SimplepayTransactionNotFinishedException;
use Vanilo\Simplepay\Models\ResponseStatus;

class SimplepayPaymentResponse implements PaymentResponse
{
    use HasSimplepayCredentials;

    private string $response;

    private string $signature;

    private string $paymentId;

    private string $transactionId;

    private ?float $amountPaid = null;

    private ResponseStatus $status;

    public function __construct(string $merchanId, string $secretKey, bool $isSandbox, string $response, string $signature)
    {
        $this->response = $response;
        $this->signature = $signature;
        $this->merchanId = $merchanId;
        $this->secretKey = $secretKey;
        $this->isSandbox = $isSandbox;

        $this->resolve();
    }

    public function wasSuccessful(): bool
    {
        return $this->status->equals(ResponseStatus::SUCCESS());
    }

    public function getStatus(): ResponseStatus
    {
        return $this->status;
    }

    public function getMessage(): string
    {
        return $this->status->value();
    }

    public function getTransactionId(): ?string
    {
        return $this->paymentId;
    }

    public function getAmountPaid(): ?float
    {
        return $this->amountPaid;
    }

    public function getPaymentId(): string
    {
        return $this->paymentId;
    }

    private function resolve(): void
    {
        $payload = json_decode(base64_decode($this->response, true));

        $this->status = ResponseStatus::create($payload->e);
        $this->paymentId = $payload->o;
        $this->transactionId = (string) $payload->t;

        if ($this->wasSuccessful()) {
            $query = new \SimplePayQuery();
            $query->addConfig([
                'HUF_MERCHANT' => $this->merchanId,
                'HUF_SECRET_KEY' => $this->secretKey,
                'SANDBOX' => $this->isSandbox
            ]);

            $query->addConfigData('merchantAccount', $this->merchanId);
            $query->addSimplePayId($this->transactionId);
            $query->runQuery();
            $response = $query->getReturnData();

            if (!Arr::get($response, 'responseSignatureValid', true)) {
                throw new InvalidSignatureException();
            }

            $transaction = $response['transactions'][0];

            if (!ResponseStatus::create($transaction['status'])->equals(ResponseStatus::FINISHED())) {
                throw new SimplepayTransactionNotFinishedException();
            }

            $this->amountPaid = $transaction['total'];
        }
    }
}
