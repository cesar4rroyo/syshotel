<form id="formSell" method="POST" action="{{ route($storesell) }}">
    <input type="hidden" name="roomId" value="{{ $roomId }}">
    <input type="hidden" name="processId" value="{{ $processId }}">
    <input type="hidden" name="type" value="{{ $type }}">
    <div class="flex items-center justify-between w-full rounded-xl bg-white mt-3">
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">{{ $errors->first('error') }}</strong>
            </div>
        @endif
        <div class="flex flex-col w-1/2 space-y-6">
            @include('utils.search', [
                'placeholder' => $placeholder,
                'name' => 'search',
                'route' => route($find),
                'propName' => 'name',
                'propOnClick' => 'addToCart',
            ])
        </div>
        
        <div class="flex flex-col w-1/2 space-y-6">
            <div class="flex flex-col w-full space-y-6 overflow-y-scroll" style="height: 340px;">
                <table class="table-auto w-full" id="tblProductsSelected">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">{{ __('maintenance.sell.products.name') }}</th>
                            <th class="px-4 py-2">{{ __('maintenance.sell.products.quantity') }}</th>
                            <th class="px-4 py-2">{{ __('maintenance.sell.products.subtotal') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cartProducts as $key => $item)
                            <tr>
                                <td class="border px-4 py-2">{{ $item['name'] }}</td>
                                <td class="border px-4 py-2">
                                    <div style="display: flex">
                                        <input type="hidden" name="productId[]" value="{{ $key }}">
                                        <input type="hidden" name="price[]" value="{{ $item['price'] }}">
                                        <input type="hidden" name="subtotal[]" value="{{ $item['total'] }}">
                                        <input onchange="handleChangeQuantity({{ $key }})" type="number" name="quantity[]" id="quantity-{{ $key }}" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block p-2.5 mr-2 ml-2" value="{{ $item['quantity'] }}">
                                        <button type="button" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="removeFromCart({{ $key }});">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                                <td class="border px-4 py-2">{{ $item['total'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div>
                <h1 class="font-bold text-xl mb-3 ml-1">Total</h1>
                <input type="text" name="totalCart" id="totalCart" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block p-2.5 mr-2 ml-2 w-full" value="{{ $cartProducts->sum('total') }}" readonly>
            </div>
        </div>
    </div>
</form>
<div class="flex items-center justify-end space-x-5 py-3 w-full">
    <button class="px-5 py-2 rounded-lg bg-blue-corp text-white flex items-center space-x-2" id="btnGuardar" onclick="saveselll();">
        <i class="far fa-save"></i>
        <p>Agregar a habitación</p>
    </button>
    <button class="px-5 py-2 rounded-lg bg-red-500 text-white flex items-center space-x-2" id="btnCancelar{{$entidad}}" onclick="cerrarModal();">
        {{ trans('maintenance.utils.cancel') }}
    </button>
</div>

<script>

    function saveselll () {
        var formData = new FormData(document.getElementById('formSell'));
        var url = '{{ route($storesell) }}';
        var axiosConfig = {
            headers : {
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            }
        }
        var data = axios.post(url, formData, axiosConfig).then(function (response) {
            if(response.data.success){
                Intranet.notificaciones(response.data.message, "Realizado!" , "success");
                cerrarModal();
                cargarRuta(response.data.routes, 'main-container');
            }
        }).catch(function (error) {
            var message = error.response.data.message;
            Intranet.notificaciones(message, "Error!" , "error");
        });
    }

    function addToCart(id){
        var url = '{{ route($addcartroute, ':id') }}';
        url = url.replace(':id', id);
        postToCart(id);
    }

    function removeFromCart(id){
        var url = '{{ route($removecartroute, ':id') }}';
        url = url.replace(':id', id);
        var axiosConfig = {
            headers : {
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            }
        }
        var data = axios.post(url, axiosConfig).then(function (response) {
            if(response.data.success){
                var cart = response.data.cart;
                addToTable(cart);
                Intranet.notificaciones(response.data.message, "Realizado!" , "success");
            }
        }).catch(function (error) {
            Intranet.notificaciones(response.data.message, "Error!" , "error");
        });
    }

    function postToCart(id, params = null){
        var url = '{{ route($addcartroute, ':id') }}';
        url = url.replace(':id', id);
        var axiosConfig = {
            headers : {
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            }
        }
        var data = axios.post(url, params, axiosConfig).then(function (response) {
            if(response.data.success){
                var cart = response.data.cart;
                console.log(cart);
                addToTable(cart);
                Intranet.notificaciones(response.data.message, "Realizado!" , "success");
            }
        }).catch(function (error) {
            console.log(error);
            var message = error.response.data.message;
            Intranet.notificaciones(message, "Error!" , "error");
        });
    }

    function addToTable(cart){
        var table = document.getElementById("tblProductsSelected");
        var totalCart = document.getElementById("totalCart");
        var rowCount = table.rows.length;
        for (var x=rowCount-1; x>0; x--) {
            table.deleteRow(x);
        }
        var total = 0;
        Object.keys(cart).forEach(function(key){
            var row = table.insertRow(1);
            total += cart[key].total;
            row.innerHTML = insertDataToRow(cart[key].name, cart[key].quantity, cart[key].total, key, cart[key].price);
        });
        totalCart.value = total;
        handleChangeTotalAmount();
    }

    function insertDataToRow(name, quantity, total, key, price){
        return "<tr><td class='border px-4 py-2'>"+name+"</td><td class='border px-4 py-2'><div style='display: flex'><input type='hidden' name='productId[]' value='"+key+"'><input type='hidden' name='price[]' value='"+price+"'><input type='hidden' name='subtotal[]' value='"+total+"'><input onchange='handleChangeQuantity("+key+")' type='number' name='quantity[]' id='quantity-"+key+"' class='border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block p-2.5 mr-2 ml-2' value='"+quantity+"'><button type='button' class='bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded' onclick='removeFromCart("+key+");'><i class='fas fa-trash'></i></button></div></td><td class='border px-4 py-2'>"+total+"</td></tr>";
    }




</script>
