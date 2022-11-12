<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PeopleRequest extends FormRequest
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
            'dni' => 'nullable|numeric|digits:8|unique:people,id,' . $this->id,
            'ruc' => 'nullable|numeric|digits:11|unique:people,id,' . $this->id,
            'name' => 'required',
        ];
    }
}