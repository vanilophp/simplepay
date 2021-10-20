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
use Konekt\Enum\Enum;
use Vanilo\Payment\Contracts\PaymentResponse;
use Vanilo\Payment\Contracts\PaymentStatus;
use Vanilo\Payment\Models\PaymentStatusProxy;
use Vanilo\Simplepay\Concerns\HasSimplepayCredentials;
use Vanilo\Simplepay\Exceptions\InvalidSignatureException;
use Vanilo\Simplepay\Models\TransactionStatus;

class SimplepayPaymentResponse implements PaymentResponse
{
    use HasSimplepayCredentials;

    private string $ipnResponseJson;

    private string $signature;

    private string $paymentId;

    private string $transactionId;

    private ?float $amountPaid = null;

    private ?PaymentStatus $status = null;

    private ?TransactionStatus $transactionStatus = null;

    private \SimplePayIpn $ipn;

    public function __construct(string $merchanId, string $secretKey, bool $isSandbox, string $ipnResponseJson)
    {
        $this->ipnResponseJson = $ipnResponseJson;
        $this->merchanId = $merchanId;
        $this->secretKey = $secretKey;
        $this->isSandbox = $isSandbox;

        $this->resolve();
    }

    public function displayIpnConfirmation(): bool
    {
        return $this->ipn->runIpnConfirm();
    }

    public function wasSuccessful(): bool
    {
        return $this->nativeStatus->isSuccess();
    }

    public function getStatus(): PaymentStatus
    {
        if (null === $this->status) {
            switch ($this->getNativeStatus()->value()) {
                case TransactionStatus::AUTHORIZED:
                    $this->status = PaymentStatusProxy::AUTHORIZED();
                    break;
                case TransactionStatus::FINISHED:
                    $this->status = PaymentStatusProxy::PAID();
                    break;
                case TransactionStatus::TIMEOUT:
                    $this->status = PaymentStatusProxy::TIMEOUT();
                    break;
                case TransactionStatus::CANCELLED:
                    $this->status = PaymentStatusProxy::CANCELLED();
                    break;
                case TransactionStatus::FRAUD:
                    $this->status = PaymentStatusProxy::DECLINED();
                    break;
                case TransactionStatus::INFRAUD:
                    $this->status = PaymentStatusProxy::ON_HOLD();
                    break;
                case TransactionStatus::REFUND:
                    $this->status = PaymentStatusProxy::REFUNDED();
                    break;
                default:
                    $this->status = PaymentStatusProxy::DECLINED();
            }
        }

        return $this->status;
    }

    public function getNativeStatus(): Enum
    {
        return $this->transactionStatus;
    }

    public function getMessage(): string
    {
        return $this->transactionStatus->value();
    }

    public function getTransactionId(): ?string
    {
        return $this->transactionId;
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
        $ipnData = json_decode($this->ipnResponseJson, true);

        $this->checkIpn($ipnData);

        $this->transactionId = (string) $ipnData['transactionId'];
        $this->paymentId = $ipnData['orderRef'];

        $query = new \SimplePayQuery();
        $query->addConfig([
            'HUF_MERCHANT' => $this->merchanId,
            'HUF_SECRET_KEY' => $this->secretKey,
            'SANDBOX' => $this->isSandbox,
        ]);

        $query->addConfigData('merchantAccount', $this->merchanId);
        $query->addSimplePayId($this->transactionId);
        $query->runQuery();
        $response = $query->getReturnData();

        if (!Arr::get($response, 'responseSignatureValid', false)) {
            throw new InvalidSignatureException();
        }

        $transaction = $response['transactions'][0];

        $refundStatus = Arr::get($transaction, 'refundStatus');
        if ($refundStatus) {
            $this->transactionStatus = TransactionStatus::REFUND();
            $this->status = 'FULL' == $refundStatus ? PaymentStatusProxy::REFUNDED() : PaymentStatusProxy::PARTIALLY_REFUNDED();
        } else {
            $this->transactionStatus = TransactionStatus::create($transaction['status']);
        }

        $this->amountPaid = $transaction['remainingTotal'];
    }

    private function checkIpn(array $data): void
    {
        $ipn = new \SimplePayIpn();
        $ipn->addConfig([
            'HUF_MERCHANT' => $this->merchanId,
            'HUF_SECRET_KEY' => $this->secretKey,
            'SANDBOX' => $this->isSandbox,
        ]);

        if (!$ipn->isIpnSignatureCheck($data)) {
            throw new InvalidSignatureException();
        }

        $this->ipn = $ipn;
    }
}
