<?php

namespace App\Http\Services\Payment;

use App\Http\Contracts\Payment\PaymentContract;
use App\Models\PaymentProcess;

class Card implements PaymentContract
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
            'card_id' => $data['card_id'],
            'pos_id' => $data['pos_id'],
            'comment' => $data['comment'] ?? '',
            'branch_id' => $data['branch_id'],
            'business_id' => $data['business_id'],
        ]);
    }

    public function getPaymentName(): string
    {
        return 'CARD';
    }
}
