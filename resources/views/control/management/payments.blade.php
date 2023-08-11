<div class="flex space-x-6 mt-3" id="divPaymentsTable">
    <table id="tablePayments" class="w-full text-base font-medium text-left text-gray-500">
        <thead class="text-base font-medium text-gray-900">
            <tr>
                <th scope="col" class="py-3 px-4 border-b border-gray-300">F. Pago</th>
                <th scope="col" class="py-3 px-4 border-b border-gray-300">Monto</th>
                <th scope="col" class="py-3 px-4 border-b border-gray-300">Detalles</th>
            </tr>
        </thead>
        <tbody class="border-b border-gray-300">
            @foreach ($payments as $item)
            <tr>
                <td class="py-3 px-4">
                    <input type="text" name="payment_type[]" id="payment_type[]" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block w-full px-4 py-2.5" readonly value="{{ $item->description }}">
                </td>
                <td class="py-3 px-4">
                    <input  name="payment_amount[]" id="payment_amount[]" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block w-full px-4 py-2.5 payment-amount" value="{{ $item->pivot->amount }}" readonly>
                </td>
                <td class="py-3 px-4" id="tdListDetails">
                    <ul></ul>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>