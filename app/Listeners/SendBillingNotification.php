<?php

namespace App\Listeners;

use App\Events\BillingEvents;
use App\Http\Services\Billing\BillingService;
use App\Models\Billing;
use App\Models\Setting;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendBillingNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\BillingEvents  $event
     * @return void
     */
    public function handle(BillingEvents $event)
    {
        $billing = Billing::with('details')->find($event->billing->id);
        $billingService = new BillingService($billing->type, $billing->branch_id);
        $res = $billingService->sendBill($billing, $billing->type);
        if ($res['success']) {
            $billing->update([
                'status' => Billing::STATUS_SENT,
                'solicitudId' => $res['solicitudId']
            ]);
        } else {
            $billing->update([
                'status' => Billing::STATUS_ERROR,
            ]);
            app('log')->error('Error al enviar la factura electrónica', [
                'billing' => $billing,
                'error' => $res['message']
            ]);
        }
    }
}
