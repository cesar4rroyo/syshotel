<?php

namespace App\Http\Services\Billing;

use App\Models\Billing;
use App\Models\BillingDetails;
use App\Models\Setting;

class BillingService
{
    protected Facturacion $facturacion;
    private BillingFactory $billingFactory;
    private Setting $settings;
    private array $businessInfo;

    public function __construct(protected string $type, int $branchId)
    {
        $this->facturacion = new Facturacion($type);
        $this->billingFactory = new BillingFactory();
        $this->settings = Setting::select('ruc', 'password_sunnat', 'serverId', 'igv')->where('branch_id', $branchId)->first();
        $this->businessInfo = [
            'ruc' => $this->settings->ruc,
            'password_sunnat' => $this->settings->password_sunnat,
            'serverId' => $this->settings->serverId,
            'igv' => $this->settings->igv,
        ];
    }

    public function sendBill(Billing $billing, string $type)
    {
        $bill = $this->billingFactory->getBillingProperties($type);
        return $this->facturacion->callClient($bill->getMethod(), $bill->getDocumentDataBeforeSend($billing, $this->businessInfo));
    }
}
