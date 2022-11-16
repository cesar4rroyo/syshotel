<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ManagementRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        if ($this->status == 'Disponible') {
            dd($this->all());
            $rules = [
                'start_date' => 'required|date',
                'room_id' => 'required|integer',
                'days' => 'required|integer',
                'amount' => 'required',
            ];
            if ($this->billingToggle == 'on') {
                $rules['client_id'] = 'required|integer';
                $rules['payment_type'] = 'required';
                $rules['document'] = 'required';
                $rules['documentNumber'] = 'required';
            }
        }
        return [
            //
        ];
    }
}