<div id="divBilling" style="display: none">
    <h1 class=" font-bold">{{ __('maintenance.control.management.billing') }}</h1>
    <hr>
    <div class="flex space-x-6 mt-3">
        <div class="flex flex-col space-y-1 w-full">
            <label class="font-medium text-sm text-gray-600" for="payment_type">{{ trans('maintenance.control.management.paymentType') }}</label>
            <select onchange="handleChangePayments()" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block w-full px-4 py-2.5" name="payment_type" id="payment_type">
                @foreach ($cboPaymentTypes as $key => $value)
                    <option value="{{ $key }}" {{ isset($formData['model']) && $formData['model']->payment_type == $key ? 'selected' : null }}>{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div style="display: none" id="paymentDiv" class="flex flex-col space-y-1 w-full">
            <label id="labelType" class="font-medium text-sm text-gray-600" for="amount1"></label>
            <input class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block w-full px-4 py-2.5" type="text" name="amount[]" id="amount1">
        </div>
    </div>
    <div id="divOtherPayments" class="grid grid-cols-4 gap-1 mt-3">
    </div>
    <div class="flex space-x-6 mt-3">
        <div class="flex flex-col space-y-1 w-full">
            <label class="font-medium text-sm text-gray-600" for="document">{{ trans('maintenance.control.management.documentType') }}</label>
            <select onchange="handleChangeDocumentType()" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block w-full px-4 py-2.5" name="document" id="document">
                @foreach ($cboDocumentTypes as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div id="divDocumentNumber" style="display: none;" class="flex flex-col space-y-1 w-full">
            <label class="font-medium text-sm text-gray-600" for="documentNumber">{{ trans('maintenance.control.management.documentNumber') }}</label>
            <input class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block w-full px-4 py-2.5 bg-blue-100" type="text" name="documentNumber" id="documentNumber" required readonly>
        </div>
    </div>
</div>
<script>
    function handleChangeDocumentType()
    {
        var documentType = document.getElementById('document').value;
        var divDocumentNumber = document.getElementById('divDocumentNumber');

        axios.get('{{ route($routes['documentType']) }}' + '?type=' + documentType)
            .then(function (response) {
                document.getElementById('documentNumber').value = response.data.documentNumber;
                divDocumentNumber.style.display = 'initial';
            })
            .catch(function (error) {
                console.log(error);
                divDocumentNumber.style.display = 'none';
            });
    }
    function handleChangePayments()
    {
        var payment_type = document.getElementById('payment_type').value;
        if(payment_type != 5) {
            document.getElementById('paymentDiv').style.display = 'inherit';
            document.getElementById('divOtherPayments').style.display = 'none';
            document.getElementById('labelType').innerHTML = document.getElementById('payment_type').options[document.getElementById('payment_type').selectedIndex].text;
        } else if (payment_type == 5){
            document.getElementById('paymentDiv').style.display = 'none';
            document.getElementById('divOtherPayments').style.display = 'grid';
            var options = document.getElementById('payment_type').options;
            var count = 0;
            for (var i = 0; i < options.length; i++) {
                if (options[i].value != 5 && options[i].value != 0) {
                    count++;
                }
            }
            var container = document.getElementById('divOtherPayments');
            container.innerHTML = '';
            for (var i = 0; i < count; i++) {
                var div = document.createElement('div');
                div.className = 'flex flex-col space-y-1 w-full';
                var label = document.createElement('label');
                label.className = 'font-medium text-sm text-gray-600';
                label.innerHTML = 'Monto ' + options[i+1].text;
                var input = document.createElement('input');
                input.className = 'border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block w-full px-4 py-2.5';
                input.type = 'number';
                input.name = 'amount[]';
                input.id = 'amount'+(i+2);
                div.appendChild(label);
                div.appendChild(input);
                container.appendChild(div);
            }
        }else{
            return false;
        }
    }
</script>