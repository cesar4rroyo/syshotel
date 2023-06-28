<?php

namespace App\Http\Services\Payment;

use App\Http\Contracts\Payment\PaymentContract;

class PaymentFactory
{
    public function create(string $type): PaymentContract
    {
        return match ($type) {
            'CARD' => resolve(Card::class),
            'CASH' => resolve(Cash::class),
            'DEPOSIT' => resolve(Deposit::class),
            'TRANSFER' => resolve(Transfer::class),
            'DIGITALWALLET' => resolve(DigitalWallet::class),
            default => throw new \Exception('Forma de Pago no encontrada'),
        };
    }
}
