@include('utils.errordiv', ['entidad' => $formData['entidad']])
@include('utils.formcrud', [
    'entidad' => $formData['entidad'],
    'formData' => $formData,
    'method' => $formData['method'],
    'route' => $formData['route'],
    'model' => isset($formData['model']) ? $formData['model'] : null,
])

<input type="hidden" name="action" value="{{ $formData['action'] }}">
<input type="hidden" name="business_id" value="{{ $formData['businessId'] }}">
<input type="hidden" name="branch_id" value="{{ $formData['branchId'] }}">
<div class="flex space-x-6">
    <div class="flex flex-col space-y-1 w-full">
        <label class="font-medium text-sm text-gray-600" for="razon_social">{{ trans('maintenance.admin.setting.razonsocial') }}</label>
        <input class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block w-full px-4 py-2.5" type="text" name="razon_social" id="razon_social"
            value="{{ isset($formData['model']) ? $formData['model']->razon_social : null }}" required>
    </div>
    <div class="flex flex-col space-y-1 w-full">
        <label class="font-medium text-sm text-gray-600" for="nombre_comercial">{{ trans('maintenance.admin.setting.nombrecomercial') }}</label>
        <input class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block w-full px-4 py-2.5" type="text" name="nombre_comercial" id="nombre_comercial"
            value="{{ isset($formData['model']) ? $formData['model']->nombre_comercial : null }}" required>
    </div>
</div>
<div class="flex space-x-6 mt-3">
    <div class="flex flex-col space-y-1 w-full">
        <label class="font-medium text-sm text-gray-600" for="ruc">{{ trans('maintenance.admin.setting.ruc') }}</label>
        <input class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block w-full px-4 py-2.5" type="text" name="ruc" id="ruc"
            value="{{ isset($formData['model']) ? $formData['model']->ruc : null }}" required>
    </div>
    <div class="flex flex-col space-y-1 w-full">
        <label class="font-medium text-sm text-gray-600" for="direccion">{{ trans('maintenance.admin.setting.direccion') }}</label>
        <input class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block w-full px-4 py-2.5" type="text" name="direccion" id="direccion"
            value="{{ isset($formData['model']) ? $formData['model']->direccion : null }}" required>
    </div>
</div>
<div class="flex space-x-6 mt-3">
    <div class="flex flex-col space-y-1 w-full">
        <label class="font-medium text-sm text-gray-600" for="telefono">{{ trans('maintenance.admin.setting.telefono') }}</label>
        <input class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block w-full px-4 py-2.5" type="text" name="telefono" id="telefono"
            value="{{ isset($formData['model']) ? $formData['model']->telefono : null }}" required>
    </div>
    <div class="flex flex-col space-y-1 w-full">
        <label class="font-medium text-sm text-gray-600" for="email">{{ trans('maintenance.admin.setting.email') }}</label>
        <input class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block w-full px-4 py-2.5" type="email" name="email" id="email"
            value="{{ isset($formData['model']) ? $formData['model']->email : null }}" required>
    </div>
    <div class="flex flex-col space-y-1 w-full">
        <label class="font-medium text-sm text-gray-600" for="serie">{{ trans('maintenance.admin.setting.serie') }}</label>
        <input class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block w-full px-4 py-2.5" type="serie" name="serie" id="serie"
            value="{{ isset($formData['model']) ? $formData['model']->serie : null }}" required>
    </div>
</div>
<input type="hidden" name="has_electronic_billing" id="has_electronic_billing" value="N">
<div class="flex space-x-6 mt-3 ml-5 pl-4">
    <input onchange="handleChangeHasBilling()" name="hasBilling" class="form-check-input appearance-none w-9 -ml-10 rounded-full h-5 align-top bg-white bg-no-repeat bg-contain bg-gray-300 focus:outline-none cursor-pointer shadow-sm" type="checkbox" role="switch" id="hasBilling">
    <label class="form-check-label inline-block text-gray-800" for="hasBilling">{{ trans('maintenance.admin.setting.billing') }}</label>
</div>  
<div id="divBilling" style="display: none">
    <div class="flex space-x-6 mt-3">
        <div class="flex flex-col space-y-1 w-full">
            <label class="font-medium text-sm text-gray-600" for="igv">{{ trans('maintenance.admin.setting.igv') }}</label>
            <input class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block w-full px-4 py-2.5" type="text" name="igv" id="igv"
                value="{{ isset($formData['model']) ? $formData['model']->igv : null }}" required>
        </div>
        <div class="flex flex-col space-y-1 w-full">
            <label class="font-medium text-sm text-gray-600" for="password_sunat">{{ trans('maintenance.admin.setting.password_sunat') }}</label>
            <input class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block w-full px-4 py-2.5" type="password_sunat" name="password_sunat" id="password_sunat"
                value="{{ isset($formData['model']) ? $formData['model']->password_sunat : null }}" required>
        </div>
        <div class="flex flex-col space-y-1 w-full">
            <label class="font-medium text-sm text-gray-600" for="serverId">{{ trans('maintenance.admin.setting.serverId') }}</label>
            <input class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block w-full px-4 py-2.5" type="serverId" name="serverId" id="serverId"
                value="{{ isset($formData['model']) ? $formData['model']->serverId : null }}" required>
        </div>
    </div>
</div>
<div class="flex w-full mt-3">
    @include('utils.modalbuttons', ['entidad' => $formData['entidad'], 'boton' => $formData['boton']])
</div>

</form>
<script type="text/javascript">
    $(document).ready(function() {
        configurarAnchoModal('450');
        init(IDFORMMANTENIMIENTO + '{!! $formData['entidad'] !!}', 'M', '{!! $formData['entidad'] !!}');
        document.getElementById('hasBilling').disabled = true;
    });

    function handleChangeHasBilling()
    {
        var divBilling = document.getElementById('divBilling');
        var has_electronic_billing = document.getElementById('has_electronic_billing');
        if(divBilling.style.display == 'none'){
            divBilling.style.display = 'inline';
            has_electronic_billing.value = 'Y';
        }else{
            divBilling.style.display = 'none';
            has_electronic_billing.value = 'N';
        }
    }
    
</script>
