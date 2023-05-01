<?php

namespace App\Http\Contracts\Billing;

use App\Models\Billing;

interface BillingContract
{
    public function getDocumentBody(Billing $billing, string $igv): array;
    public function getDocumentDataBeforeSend(Billing $billing, array $businessInfo): array;
    public function generateDataToSend(Billing $billing, array $document, array $businessInfo): array;
    public function getMethod(): string;
}