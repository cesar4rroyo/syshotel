<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MoveStockRequest extends FormRequest
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
        return [
            'products' => 'required|array',
            'originbranch' => 'required|integer|exists:branches,id,deleted_at,NULL',
            'finalbranch' => 'required|integer|exists:branches,id,deleted_at,NULL',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, mixed>
     */

    public function messages()
    {
        return [
            'products.required' => 'Debe seleccionar al menos un producto',
            'products.array' => 'Debe seleccionar al menos un producto',
            'originbranch.required' => 'Debe seleccionar una sucursal de origen',
            'originbranch.integer' => 'Debe seleccionar una sucursal de origen',
            'originbranch.exists' => 'Debe seleccionar una sucursal de origen',
            'finalbranch.required' => 'Debe seleccionar una sucursal de destino',
            'finalbranch.integer' => 'Debe seleccionar una sucursal de destino',
            'finalbranch.exists' => 'Debe seleccionar una sucursal de destino',
        ];
    }
}