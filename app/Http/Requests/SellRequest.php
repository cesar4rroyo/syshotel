<?php

namespace App\Http\Requests;

use App\Models\Concept;
use App\Models\Payments;
use App\Models\ProcessType;
use App\Traits\CashRegisterTrait;
use Illuminate\Foundation\Http\FormRequest;

class SellRequest extends FormRequest
{
    use CashRegisterTrait;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $payments = $this->preparePaymentData($this);
        $products = $this->prepareProductsData($this);
        $billing = $this->prepareBillingData($this);

        unset($this['payment_type'], $this['payment_type_id'], $this['payment_amount'], $this['notes'], $this['pos'], $this['card'], $this['bank'], $this['noperation'], $this['digitalwallet'], $this['productId'], $this['quantity'], $this['price'], $this['subtotal'], $this['search']);

        $this->merge([
            'business_id' => session()->get('businessId'),
            'branch_id' => session()->get('branchId'),
            'cashbox_id' => session()->get('cashboxId'),
            'user_id' => auth()->user()->id,
            'concept_id' => Concept::SELL_PRODUCT_OR_SERVICE_ID,
            'payment_type' => $this->evaluatePaymentType($payments),
            'status' => 'C',
            'processtype_id' => ProcessType::SELL_ID,
            'client_id' => $this->clientBilling ?? 1,
            'total' => $this->totalCart,
            'amount' => $this->totalCart,
            'payments' => $payments,
            'products' => $products,
            'billing' => $billing,
        ]);

        unset($this['totalCart'], $this['clientBilling']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'total' => 'required|numeric',
            'payment_type' => 'required',
            'document' => 'required',
            'documentNumber' => 'required',
            'client_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'total.required' => 'El total de la venta es requerido',
            'total.numeric' => 'El total de la venta debe ser un número',
            'payment_type.required' => 'El campo forma de pago es requerido',
            'document.required' => 'El campo tipo de documento es requerido',
            'documentNumber.required' => 'El campo número de documento es requerido',
            'client_id.required' => 'El cliente es requerido',
        ];
    }
}
