<?php

namespace App\Http\Contracts\Payment;

use App\Models\PaymentProcess;

interface PaymentContract
{
    public function savePayment(array $data, int $processId = null): PaymentProcess;
    public function getPaymentName(): string;
}
