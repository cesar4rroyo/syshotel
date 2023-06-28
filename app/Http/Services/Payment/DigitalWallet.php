<?php

namespace App\Http\Services\Payment;

use App\Http\Contracts\Payment\PaymentContract;
use App\Models\PaymentProcess;

class DigitalWallet implements PaymentContract
{
    public function savePayment(array $data): PaymentProcess
    {
        return PaymentProcess::crate([
            'date' => $data['date'],
            'number' => $data['number'],
            'process_id' => $data['process_id'],
            'description' => $this->getPaymentName(),
            'status' => 'A',
            'amount' => $data['amount'],
            'digitalwallet_id' => $data['digitalwallet_id'],
            'comment' => $data['comment'] ?? '',
            'branch_id' => $data['branch_id'],
            'business_id' => $data['business_id'],
        ]);
    }

    public function getPaymentName(): string
    {
        return 'DIGITALWALLET';
    }
}
