<?php

namespace App\Http\Requests;

use App\Models\Concept;
use App\Models\ProcessType;
use App\Traits\CashRegisterTrait;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class UpdateManagementRequest extends FormRequest
{

    use CashRegisterTrait;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $payments = $this->preparePaymentData($this);
        $billing = $this->prepareBillingData($this);

        unset($this['payment_type'], $this['payment_type_id'], $this['payment_amount'], $this['notes'], $this['pos'], $this['card'], $this['bank'], $this['noperation'], $this['digitalwallet']);

        $this->merge([
            'business_id' => session()->get('businessId'),
            'branch_id' => session()->get('branchId'),
            'cashbox_id' => session()->get('cashboxId'),
            'user_id' => auth()->user()->id,
            'payments' => $payments,
            'billing' => $billing,
            'payment_type' => $this->evaluatePaymentType($payments),
            'status' => $this->evaluateStatus($this->billingToggle),
            'processtype_id' => ProcessType::HOTEL_SERVICE_ID,
            'concept_id' => Concept::HOTEL_SERVICE_ID,
            'client_id' => $this->clientBilling ?? 1,
            'notes' => $this->notes_hotel,
            'amount' => $this->amount_hotel,
        ]);

        if ($this->type == 'H') {
            $this->merge([
                'end_date' => ($this->end_time) ? ($this->end_time) : Carbon::parse(date('Y-m-d') . ' ' . $this->start_time)->addHours($this->hours)->format('Y-m-d H:i:s'),
            ]);
        } else {
            $this->merge([
                'end_date' => ($this->end_date) ? $this->end_date : Carbon::parse($this->start_date)->addDays($this->days)->format('Y-m-d H:i:s'),
            ]);
        }
    }

    public function rules(): array
    {
        $rules = [
            'end_date' => 'required|date',
        ];
        if ($this->status == 'Pendiente') {
            $rules['clientBilling'] = 'required|integer';
            $rules['payment_type'] = 'required';
            $rules['document'] = 'required';
            $rules['documentNumber'] = 'required';
        }
        return $rules;
    }
}
