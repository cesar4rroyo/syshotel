<?php

namespace App\Http\Services;

use App\Models\Branch;
use App\Models\Business;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
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
            [
                'business_id' => $request->business_id,
                'branch_id' => $request->branch_id,
            ],
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

    public function storeOrUpdateBusinessBranches(Business $business, bool $is_main): void
    {
        Branch::updateOrCreate([
            'business_id' => $business->id,
            'name' => $business->name,
            'address' => $business->address,
            'phone' => $business->phone,
            'email' => $business->email,
            'city' => $business->city,
            'is_main' => $is_main,
        ]);
    }

    public function storeOrUpdateBranchProfilePhoto(Request $request)
    {
        $branch = Branch::find($request->branch_id);
        $logo_name = $branch->name . '_' . time() . '.' . $request->file->extension();
        $request->file->storeAs('public/logos', $logo_name);
        $branch->settings->updateOrCreate([
            'business_id' => $request->business_id,
            'branch_id' => $request->branch_id,
        ], [
            'logo' => $logo_name,
        ]);
    }

    public function storeOrUpdateUserPhoto(Request $request)
    {
        $user = User::find($request->user_id);
        $user_name = $user->name . '_' . time() . '.' . $request->file->extension();
        $request->file->storeAs('public/users', $user_name);
        $user->update([
            'profile_photo_path' => $user_name,
        ]);
    }

    public function getBusinessById(int $id): Business
    {
        return Business::find($id);
    }

    public function getBranchesSettingsById(int $id): array
    {
        return Setting::where('branch_id', $id)->get()->toArray() ?? [];
    }

    public function setMainBusinessBranch(Request $request): void
    {
        $business = Business::find($request->business_id);
        $business->branches()->where('id', '!=', $request->branch_id)->update([
            'is_main' => false,
        ]);
        $business->branches()->where('id', $request->branch_id)->update([
            'is_main' => true,
        ]);
    }
}