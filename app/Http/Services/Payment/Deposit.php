<?php

namespace App\Http\Services\Payment;

use App\Http\Contracts\Payment\PaymentContract;
use App\Models\PaymentProcess;
use App\Models\PaymentType;

class Deposit implements PaymentContract
{
    public function savePayment(array $data, int $processId = null): PaymentProcess
    {
        return PaymentProcess::create([
            'date' => $data['date'],
            'number' => $data['number'],
            'process_id' => $data['process_id'] ?? $processId,
            'description' => $this->getPaymentName(),
            'status' => 'A',
            'amount' => $data['amount'],
            'bank_id' => $data['bank_id'],
            'comment' => $data['comment'] ?? '',
            'branch_id' => $data['branch_id'],
            'business_id' => $data['business_id'],
            'payment_id' => PaymentType::DEPOSIT_ID,
            'concept_id' => $data['concept_id'],
            'nrooperation' => $data['nrooperation'] ?? '-',
        ]);
    }

    public function getPaymentName(): string
    {
        return 'DEPOSIT';
    }
}
