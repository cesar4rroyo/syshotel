<?php

namespace App\Http\Requests;

use App\Models\Concept;
use App\Models\ProcessType;
use App\Traits\CashRegisterTrait;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class ManagementRequest extends FormRequest
{
    use CashRegisterTrait;


    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
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
                'start_date' => date('Y-m-d') . ' ' . $this->start_time,
                'end_date' => ($this->end_time) ? date('Y-m-d') . ' ' . $this->end_time : Carbon::parse(date('Y-m-d') . ' ' . $this->start_time)->addHours($this->hours)->format('Y-m-d H:i:s'),
            ]);
        } else {
            $this->merge([
                'start_date' => $this->start_date,
                'end_date' => ($this->end_date) ? $this->end_date : Carbon::parse($this->start_date)->addDays($this->days)->format('Y-m-d H:i:s'),
            ]);
        }

        unset($this['amount_hotel'], $this['notes_hotel']);
    }


    public function rules()
    {
        $rules = [
            'start_date' => 'required|date',
            'room_id' => 'required',
            'days' => 'required|integer',
            'amount' => 'required',
            'number' => 'required|string',
            'date' => 'required|date',
            'client_id' => 'required',
        ];
        if ($this->type == 'H') {
            $rules['start_time'] = 'required';
            $rules['hours'] = 'required|integer';
            unset($rules['days']);
            unset($rules['start_date']);
        }
        if ($this->billingToggle == 'on') {
            $rules['clientBilling'] = 'required|integer';
            $rules['payment_type'] = 'required';
            $rules['document'] = 'required';
            $rules['documentNumber'] = 'required';
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'start_date.required' => 'La fecha de inicio es requerida',
            'start_date.date' => 'La fecha de inicio debe ser una fecha válida',
            'room_id.required' => 'La habitación es requerida',
            'days.required' => 'El campo noches es requerido',
            'days.integer' => 'El campo noche tiene formato incorrecto',
            'amount.required' => 'El monto es requerido',
            'number.required' => 'El número es requerido',
            'number.string' => 'El número debe ser una cadena de texto',
            'date.required' => 'La fecha es requerida',
            'date.date' => 'La fecha debe ser una fecha válida',
            'client_id.required' => 'El cliente es requerido',
            'clientBilling.required' => 'El cliente para facturación es requerido',
            'payment_type.required' => 'El tipo de pago es requerido',
            'document.required' => 'El documento de facturación es requerido',
            'documentNumber.required' => 'El número de documento de facturación es requerido',
            'start_time.required' => 'La hora de inicio es requerida',
            'hours.required' => 'El campo horas es requerido',
        ];
    }
}
