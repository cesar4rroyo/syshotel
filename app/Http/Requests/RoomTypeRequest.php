<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Elegant\Sanitizer\Laravel\SanitizesInput;

class RoomTypeRequest extends FormRequest
{
    use SanitizesInput;
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
            'name' => 'required|string|max:255',
            'capacity' => 'required',
            'price' => 'required',
            'branch_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El campo nombre es obligatorio',
            'capacity.required' => 'El campo capacidad es obligatorio',
            'price.required' => 'El campo precio es obligatorio',
            'branch_id.required' => 'El campo sucursal es obligatorio',
        ];
    }

    public function filters()
    {
        return [
            'name' => 'trim|escape|uppercase',
            'capacity' => 'trim|escape|uppercase',
            'price' => 'trim|escape|uppercase',
            'branch_id' => 'trim|escape|uppercase',
        ];
    }
}
