
@if(count($list) == 0)
@include('utils.noresult')
@else
<div class="flex space-x-6 overflow-y-auto" id="divTableProducts" style="height: 300px">
    <table id="productsTable" class="w-full text-base font-medium text-center text-gray-500">
        <thead class="text-base font-medium text-gray-900">
            <tr>
                <th scope="col" class="py-3 px-4 border-b border-gray-300">Nro.</th>
                <th scope="col" class="py-3 px-4 border-b border-gray-300">Descripción</th>
                <th scope="col" class="py-3 px-4 border-b border-gray-300">F. Pago</th>
                <th scope="col" class="py-3 px-4 border-b border-gray-300">Total</th>
                <th scope="col" class="py-3 px-4 border-b border-gray-300">Notas</th>
            </tr>
        </thead>
        <tbody style="font-size: .4rem" class="border-b border-gray-300">
            @foreach ($list as $key => $value)
            <tr>
                <td class="py-3 px-4 text-sm">{{ $value->numberList }}</td>
                <td class="py-3 px-4 text-sm">{{ $value->description . ' - Cliente: ' . $value->client }}</td>
                <td class="py-3 px-4 text-sm">{{ $value->paymentType }}</td>
                <td class="py-3 px-4 text-sm">{{ 'S/. ' . $value->total }}</td>
                <td class="py-3 px-4 text-sm">{{ $value->comments }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
<div class="flex w-full mt-3">
    <div class="flex items-center justify-end space-x-5 py-3 w-full">
        <button class="px-5 py-2 rounded-lg bg-red-500 text-white flex items-center space-x-2" onclick="cerrarModal();">
            {{ trans('maintenance.utils.cancel') }}
        </button>
    </div>
</div>
