<div id="divBilling">
    <h1 class=" font-bold">{{ __('maintenance.control.management.billing') }}</h1>
    <hr>
    <div id="divErrors"></div>
    <div class="flex space-x-6 mt-3">
        <div class="flex flex-col space-y-1 w-full">
            <label class="font-medium text-sm text-gray-600" for="date">
                {{ __('maintenance.sell.date') }}
            </label>
            <input type="date" name="date" id="date" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block w-full px-4 py-2.5" readonly value="{{ date('Y-m-d') }}">
        </div>
        <div class="flex flex-col space-y-1 w-full">
            <label class="font-medium text-sm text-gray-600" for="date">
                {{ __('maintenance.sell.number') }}
            </label>
            <input type="text" name="number" id="number" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block w-full px-4 py-2.5" readonly value="{{ $number }}">
        </div>
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
    <div class="flex space-x-6 mt-3">
        <div class="flex flex-col space-y-1 w-full">
            <label class="font-medium text-sm text-gray-600" for="clientBilling">
                {{ trans('maintenance.control.management.clientBilling') }}
                <span
                    onclick="modal('{{ URL::route($routes['client'], ['from'=>'billing']) }}', 'Agregar Nuevo Cliente', this);"
                    class="bg-gray-100 text-gray-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded mr-2 dark:bg-gray-700 dark:text-gray-300 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="mr-1 w-3 h-3" fill="currentColor"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                    </svg>
                    {{ __('maintenance.utils.new') }}
                </span>
            </label>
            <select class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block w-full px-4 py-2.5" name="clientBilling" id="clientBilling">
                @foreach ($cboClients as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
                </select>
        </div>
    </div>
    @include('utils.tablebilling', ['route' => $paymentRoute])
</div>
<script>

    $( document ).ready( function() {
        var total = document.getElementById('totalCart').value;
        var input = document.getElementsByClassName('payment-amount')[0];
        input.value = total;
    });

    function handleChangeDocumentType()
    {
        var documentType = document.getElementById('document').value;
        var divDocumentNumber = document.getElementById('divDocumentNumber');
        var selectClient = document.getElementById('clientBilling');

        if(documentType != ''){
            axios.get('{{ route($routes['documentType']) }}' + '?type=' + documentType)
            .then(function (response) {
                document.getElementById('documentNumber').value = response.data.documentNumber;
                divDocumentNumber.style.display = 'initial';
                selectClient.innerHTML = '';
                var clients = response.data.cboClients;
                for (var key in clients) {
                    var option = document.createElement('option');
                    option.value = key;
                    option.text = clients[key];
                    selectClient.appendChild(option);
                }
            })
            .catch(function (error) {
                console.log(error);
                divDocumentNumber.style.display = 'none';
            });
        }else{
            divDocumentNumber.style.display = 'none';
        }
    }

    function savepayment(e){
        e.preventDefault();
        if(!verifyTotalAmounts()){
            return;
        }
        var total = document.getElementById('totalCart').value;
        var errorDiv = document.getElementById('divErrors');
        if(total == 0){
            Intranet.notificaciones('No hay productos seleccionados', 'Error!!', 'error');
            return;
        }
        var form = document.getElementById('formSell');
        var data = new FormData(form);
        var url = "{{ route($store) }}";
        var method = "POST";
        var axiosConfig = {
            headers : {
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            }
        }

        axios.post(url, data, axiosConfig)
            .then(function (response) {
                if(response.data.success){
                    cargarRuta('{{ URL::to('cashregister') }}', 'main-container');
                    var win = window.open(response.data.url, '_blank');
                    win.focus();
                }
            })
            .catch(function (error) {
                if(error.response.status == 422){
                    var errors = error.response.data.errors;
                    var errorsHtml = '';
                    $.each(errors, function (key, value) {
                        errorsHtml += '<li>' + value[0] + '</li>';
                    });
                    errorDiv.style.display = 'initial';
                    errorDiv.innerHTML = errorsHtml;
                }else{
                    Intranet.notificaciones('Ha ocurrido un error interno del sistema', 'Error!!', 'error');
                }
            });

        
    }

    function verifyTotalAmounts(){
        var total = document.getElementById('totalCart').value;
        var amounts = document.getElementsByClassName('payment-amount');
        var totalAmounts = 0;
        console.log(amounts.length);  
        if(amounts.length == 0){
            Intranet.notificaciones('No hay montos de pago', 'Error!!', 'error');
            return false;
        }
        for (var i = 0; i < amounts.length; i++) {
            totalAmounts += parseFloat(amounts[i].value);
        }
        if(total != totalAmounts){
            Intranet.notificaciones('El monto total no coincide con la suma de los montos de pago', 'Error!!', 'error');
            return false;
        }
        return true;
    }

    function handleChangeTotalAmount(){
        var total = document.getElementById('totalCart').value;
        var input = document.getElementsByClassName('payment-amount')[0];
        input.value = total;
    }

    function handleChangePayments()
    {
        var payment = document.getElementById('payment_type').value;
        var ruta = '{{ route($paymentRoute) }}' + '?type=' + payment;
        modal2(ruta, 'Nuevo Pago', payment);
    }
</script>