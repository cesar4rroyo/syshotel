<?php

namespace App\Http\Services\Payment;

use Illuminate\Support\Collection;

class PaymentService
{
    protected PaymentFactory $paymentFactory;

    public function __construct(protected string $type)
    {
        $this->paymentFactory = new PaymentFactory();
    }


    public function savePayments(array $payments): Collection
    {
        $paymentsSaved = collect();

        foreach ($payments as $payment) {
            $paymentSaved = $this->paymentFactory->create($this->type)->savePayment($payment);
            $paymentsSaved->push($paymentSaved);
        }

        return $paymentsSaved;
    }
}
