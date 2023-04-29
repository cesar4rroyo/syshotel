<?php

namespace App\Http\Services\Billing;

use App\Http\Contracts\Billing\BillingContract;
use App\Models\Billing;

class BillingFactory
{
    public function getBillingProperties(string $type): BillingContract
    {
        return match ($type) {
            'FACTURA' => resolve(Factura::class),
            'BOLETA' => resolve(Boleta::class),
            default => throw new \Exception('Tipo de documento no encontrado'),
        };
    }
}