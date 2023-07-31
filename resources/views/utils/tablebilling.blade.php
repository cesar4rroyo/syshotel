<div class="flex space-x-6 mt-3" id="divPaymentsTable">
    <table id="tablePayments" class="w-full text-base font-medium text-left text-gray-500">
        <thead class="text-base font-medium text-gray-900">
            <tr>
                <th scope="col" class="py-3 px-4 border-b border-gray-300">F. Pago</th>
                <th scope="col" class="py-3 px-4 border-b border-gray-300">Monto</th>
                <th scope="col" class="py-3 px-4 border-b border-gray-300">Detalles</th>
                <th scope="col" class="py-3 px-4 border-b border-gray-300">Acciones
                    <span
                        onclick="modal('{{ URL::route($route) }}', 'Nuevo Pago', this);"
                        class="bg-gray-100 text-gray-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded mr-2 dark:bg-gray-700 dark:text-gray-300 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="mr-1 w-3 h-3" fill="currentColor"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                        </svg>
                        {{ __('maintenance.utils.new') }}
                    </span>
                </th>
            </tr>
        </thead>
        <tbody class="border-b border-gray-300">
            <tr>
                <td class="py-3 px-4">
                    <input type="hidden" name="payment_type_id[]" id="payment_type_id[]" value="1">
                    <input type="text" name="payment_type[]" id="payment_type[]" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block w-full px-4 py-2.5" readonly value="Efectivo">
                </td>
                <td class="py-3 px-4">
                    <input type="number" name="payment_amount[]" id="payment_amount[]" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block w-full px-4 py-2.5 payment-amount" value="0" readonly>
                </td>
                <td class="py-3 px-4" id="tdListDetails">
                    <ul></ul>
                </td>
                <td class="py-3 px-4 text-center">
                    <button type="button" class="btn btn-danger btn-sm" onclick="deleteRow(this)">
                        <i style="color: red" class="fas fa-trash-alt"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<script>
    function insertDataToRowBilling(data)
    {
        console.log(data);
        var html = "<tr>";
        html += "<td class='py-3 px-4'>";
        html += "<input type='hidden' name='payment_type_id[]' id='payment_type_id[]' value='"+data.payment_type+"'>";
        html += "<input type='text' name='payment_type[]' id='payment_type[]' class='border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block w-full px-4 py-2.5' readonly value='"+data.paymenttext+"'>";
        html += "</td>";
        html += "<td class='py-3 px-4'>";
        html += "<input type='number' name='payment_amount[]' id='payment_amount[]' class='border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block w-full px-4 py-2.5 payment-amount' value='"+data.amount+"' readonly>";
        html += "</td>";
        html += "<td class='py-3 px-4' id='tdListDetails'>";
        html += "<ul>";
        if(data.notes != null && data.notes.trim() != '' && data.notes != undefined){
            html += "<input type='hidden' name='notes[]' id='notes[]' value='"+data.notes+"'>";
            html += "<li>Notas: "+data.notes+"</li>";
        }else{
            html += "<input type='hidden' name='notes[]' id='notes[]' value=''>";
        }
        if(data.pos != null && data.pos != '' && data.pos != undefined){
            html += "<input type='hidden' name='pos[]' id='pos[]' value='"+data.pos+"'>";
            html += "<li>POS: "+data.postext+"</li>";
        }else{
            html += "<input type='hidden' name='pos[]' id='pos[]' value=''>";
        }
        if(data.card != null && data.card != '' && data.card != undefined){
            html += "<input type='hidden' name='card[]' id='card[]' value='"+data.card+"'>";
            html += "<li>Tarjeta: "+data.cardtext+"</li>";
        }else{
            html += "<input type='hidden' name='card[]' id='card[]' value=''>";
        }
        if(data.noperation != null && data.noperation != '' && data.noperation != undefined){
            html += "<input type='hidden' name='noperation[]' id='noperation[]' value='"+data.noperation+"'>";
            html += "<li>N° Operación: "+data.noperation+"</li>";
        }else{
            html += "<input type='hidden' name='noperation[]' id='noperation[]' value=''>";
        }
        if(data.bank != null && data.bank != '' && data.bank != undefined){
            html += "<input type='hidden' name='bank[]' id='bank[]' value='"+data.bank+"'>";
            html += "<li>Banco: "+data.banktext+"</li>";
        }else{
            html += "<input type='hidden' name='bank[]' id='bank[]' value=''>";
        }
        if(data.digitalwallet != null && data.digitalwallet != '' && data.digitalwallet != undefined){
            html += "<input type='hidden' name='digitalwallet[]' id='digitalwallet[]' value='"+data.digitalwallet+"'>";
            html += "<li>Billetera: "+data.digitalwallettext+"</li>";
        }else{
            html += "<input type='hidden' name='digitalwallet[]' id='digitalwallet[]' value=''>";
        }
        html += "</ul>";
        html += "</td>";
        html += "<td class='py-3 px-4 text-center'>";
        html += "<button type='button' class='btn btn-danger btn-sm' onclick='deleteRow(this)'>";
        html += "<i style='color: red' class='fas fa-trash-alt'></i>";
        html += "</button>";
        html += "</td>";
        html += "</tr>";
        return html;
    }

    function deleteRow(btn)
    {
        var row = btn.parentNode.parentNode;
        row.parentNode.removeChild(row);
    }

    function addPaymentDataToTable(data)
    {
        console.log(data);
        var tablePayments = document.getElementById("tablePayments");
        var rowCount = tablePayments.rows.length;
        var row = tablePayments.insertRow(rowCount);
        row.innerHTML = insertDataToRowBilling(data); 
    }
</script>