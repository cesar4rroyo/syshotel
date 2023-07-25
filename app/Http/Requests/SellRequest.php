<?php

namespace App\Http\Requests;

use App\Models\Concept;
use App\Models\Payments;
use Illuminate\Foundation\Http\FormRequest;

class SellRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    private function getTypeById(int $id): string
    {
        $tyes = [
            '1' => 'CASH',
            '2' => 'CARD',
            '3' => 'DIGITALWALLET',
            '4' => 'DEPOSIT',
            '5' => 'TRANSFER'
        ];

        return $tyes[$id];
    }

    private function evaluatePaymentType(array $payments): string
    {
        if (count($payments) == 1) {
            return $payments[0]['type'];
        } else {
            return 'MIXED';
        }
    }

    private function preparePaymentData($data): array
    {
        $types = $data->payment_type_id;
        $amounts = $data->payment_amount;
        $notes = $data->notes;
        $pos = $data->pos;
        $card = $data->card;
        $bank = $data->bank;
        $noperation = $data->noperation;
        $digitalwallet = $data->digitalwallet;

        $payments = [];

        foreach ($types as $key => $type) {
            $payments[] = [
                'type' => $this->getTypeById($type),
                'date' => date('Y-m-d H:i:s'),
                'number' => 'aaa',
                'amount' => $amounts[$key],
                'comment' => $notes[$key] ?? '',
                'concept_id' => Concept::SELL_PRODUCT_OR_SERVICE_ID,
                'branch_id' => session()->get('branchId'),
                'business_id' => session()->get('businessId'),
                'pos_id' => $pos[$key] ?? null,
                'card_id' => $card[$key] ?? null,
                'bank_id' => $bank[$key] ?? null,
                'nrooperation' => $noperation[$key] ?? null,
                'digitalwallet_id' => $digitalwallet[$key] ?? null,
            ];
        }
        return $payments;
    }

    private function prepareProductsData($data): array
    {
        $products = $data->productId;
        $quantities = $data->quantity;
        $prices = $data->price;
        $subtotals = $data->subtotal;

        $productsData = [];

        foreach ($products as $key => $product) {
            $productsData[] = [
                'product_id' => $product,
                'quantity' => $quantities[$key],
                'price' => $prices[$key],
                'subtotal' => $subtotals[$key],
            ];
        }
        return $productsData;
    }

    private function prepareBillingData($data): array
    {
        return [
            'date' => $data->date,
            'number' => $data->documentNumber,
            'type' => $data->document,
            'client_id' => $data->clientBilling,
        ];
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
            'processtype_id' => 1,
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
