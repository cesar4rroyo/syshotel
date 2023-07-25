
<div class="flex space-x-6 mt-3">
    <div class="flex flex-col space-y-1 w-full">
        <label class="font-medium text-sm text-gray-600" for="payment_type">{{ trans('maintenance.control.management.paymentType') }}</label>
        <select onchange="handleChangePaymentTypes()" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block w-full px-4 py-2.5" name="payment_type" id="payment_type">
            @foreach ($cboPaymentTypes as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
    </div>
    <div class="flex flex-col space-y-1 w-full">
        <label class="font-medium text-sm text-gray-600" for="amount">{{ trans('maintenance.admin.payment.amount') }}</label>
        <input class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block w-full px-4 py-2.5" type="number" name="amount" id="amount" value="0" onchange="handleChangeAmount()" required>
    </div>
</div>
<div style="display: none" class="flex space-x-6 mt-3" id="divCard">
    <div class="flex flex-col space-y-1 w-full">
        <label class="font-medium text-sm text-gray-600" for="pos">{{ trans('maintenance.admin.payment.pos') }}</label>
        <select class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block w-full px-4 py-2.5" name="pos" id="pos">
            @foreach ($pos as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
    </div>
    <div class="flex flex-col space-y-1 w-full">
        <label class="font-medium text-sm text-gray-600" for="card">{{ trans('maintenance.admin.payment.card') }}</label>
        <select class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block w-full px-4 py-2.5" name="card" id="card">
            @foreach ($cards as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
    </div>
</div>
<div style="display: none" class="flex space-x-6 mt-3" id="divDepositOrTransfer">
    <div class="flex flex-col space-y-1 w-full">
        <label class="font-medium text-sm text-gray-600" for="pos">{{ trans('maintenance.admin.payment.noperation') }}</label>
        <input class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block w-full px-4 py-2.5" type="text" name="noperation" id="noperation" required>
    </div>
    <div class="flex flex-col space-y-1 w-full">
        <label class="font-medium text-sm text-gray-600" for="bank">{{ trans('maintenance.admin.payment.bank') }}</label>
        <select class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block w-full px-4 py-2.5" name="bank" id="bank">
            @foreach ($banks as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
    </div>
</div>
<div style="display: none" class="flex space-x-6 mt-3" id="divDigitalWallet">
    <div class="flex flex-col space-y-1 w-full">
        <label class="font-medium text-sm text-gray-600" for="digitalwallet">{{ trans('maintenance.admin.payment.digitalwallet') }}</label>
        <select class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block w-full px-4 py-2.5" name="digitalwallet" id="digitalwallet">
            @foreach ($wallets as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="flex space-x-6 mt-2">
    <div class="flex flex-col space-y-1 w-full">
        <label class="font-medium text-sm text-gray-600" for="notes">{{ trans('maintenance.admin.payment.notes') }}</label>
        <textarea class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block w-full px-4 py-2.5" type="text" name="notes" id="notes"
            value="" required>
        </textarea>
    </div>
</div>
<div class="flex items-center justify-end space-x-5 py-3 w-full">
    <button class="px-5 py-2 rounded-lg bg-blue-corp text-white flex items-center space-x-2" id="btnGuardar" onclick="save()">
        <i class="far fa-save"></i>
        <p>Agregar</p>
    </button>
    <button class="px-5 py-2 rounded-lg bg-red-500 text-white flex items-center space-x-2" id="btnCancelar" onclick="cerrarModal();">
        {{ trans('maintenance.utils.cancel') }}
    </button>
</div>
<script>

    function save()
    {
        var payment_type = document.getElementById('payment_type').value;
        var paymenttext = document.getElementById('payment_type').options[document.getElementById('payment_type').selectedIndex].text;
        var amount = document.getElementById('amount').value;
        var notes = document.getElementById('notes').value;

        var pos = document.getElementById('pos').value;
        var card = document.getElementById('card').value;
        var noperation = document.getElementById('noperation').value;
        var bank = document.getElementById('bank').value;
        var digitalwallet = document.getElementById('digitalwallet').value;
        

        if(amount <= 0){
            alert('El monto debe ser mayor a 0');
            return;
        }
        data = {
            payment_type: payment_type,
            paymenttext: paymenttext,
            amount: amount,
            notes: notes,
        };

        if(payment_type == 2){
            data.pos = pos;
            data.postext = document.getElementById('pos').options[document.getElementById('pos').selectedIndex].text;
            data.card = card;
            data.cardtext = document.getElementById('card').options[document.getElementById('card').selectedIndex].text;
        }

        if(payment_type == 4 || payment_type == 5){
            data.noperation = noperation;
            data.bank = bank;
            data.banktext = document.getElementById('bank').options[document.getElementById('bank').selectedIndex].text;
        }

        if(payment_type == 3){
            data.digitalwallet = digitalwallet;
            data.digitalwallettext = document.getElementById('digitalwallet').options[document.getElementById('digitalwallet').selectedIndex].text;
        }

        addPaymentDataToTable(data);

        cerrarModal();

    }

    function handleChangeAmount()
    {
        var amount = document.getElementById('amount').value;
        if(amount <= 0){
            alert('El monto debe ser mayor a 0');
            document.getElementById('amount').value = 0;
            return;
        }
    }


    function handleChangePaymentTypes()
    {
        var payment = document.getElementById('payment_type').value;
        var divCard = document.getElementById('divCard');
        var divDepositOrTransfer = document.getElementById('divDepositOrTransfer');
        var divDigitalWallet = document.getElementById('divDigitalWallet');
        if(payment == 1){
            divCard.style.display = 'none';
            divDepositOrTransfer.style.display = 'none';
            divDigitalWallet.style.display = 'none';
        }else if(payment == 2){
            divCard.style.display = 'flex';
            divDepositOrTransfer.style.display = 'none';
            divDigitalWallet.style.display = 'none';
        }else if(payment == 5 || payment == 4){
            divCard.style.display = 'none';
            divDepositOrTransfer.style.display = 'flex';
            divDigitalWallet.style.display = 'none';
        }else if(payment == 3){
            divCard.style.display = 'none';
            divDepositOrTransfer.style.display = 'none';
            divDigitalWallet.style.display = 'block';
        }

    }
</script>