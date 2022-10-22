<?php

namespace App\Http\Services;

use App\Models\Business;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BusinessService
{
    public function storeOrUpdateBussinessSettings(Request $request, int $id = null)
    {
        $rules = [
            'razon_social' => 'required',
            'nombre_comercial' => 'required',
            'ruc' => 'required|numeric|digits:11|unique:settings,ruc,' . $id,
            'direccion' => 'required',
            'telefono' => 'nullable|numeric',
            'email' => 'nullable|email',
        ];

        $messages = [
            'razon_social.required' => 'El campo Razón Social es obligatorio.',
            'nombre_comercial.required' => 'El campo Nombre Comercial es obligatorio.',
            'ruc.required' => 'El campo RUC es obligatorio.',
            'ruc.numeric' => 'El campo RUC debe ser numérico.',
            'ruc.digits' => 'El campo RUC debe tener 11 dígitos.',
            'ruc.unique' => 'El RUC ingresado ya se encuentra registrado.',
            'direccion.required' => 'El campo Dirección es obligatorio.',
            'telefono.numeric' => 'El campo Teléfono debe ser numérico.',
            'email.email' => 'El campo Email debe ser un email válido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages)->stopOnFirstFailure()->validate();

        Setting::updateOrCreate(
            ['business_id' => $id],
            [
                'razon_social' => $request->razon_social,
                'nombre_comercial' => $request->nombre_comercial,
                'ruc' => $request->ruc,
                'direccion' => $request->direccion,
                'telefono' => $request->telefono,
                'email' => $request->email,
            ]
        );
    }

    public function storeOrUpdateBusiness(Request $request)
    {
    }

    public function storeOrUpdateBusinessUser(Request $request)
    {
    }

    public function storeOrUpdateBusinessBranches(Request $request)
    {
    }

    public function storeOrUpdateBusinessProfilePicture(Request $request)
    {
    }
}