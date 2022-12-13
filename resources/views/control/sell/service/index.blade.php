<div class="container" id="container">
    <div class="flex flex-col w-full space-y-6">
        <p class="text-4xl font-bold font-baloo">{{ __('maintenance.sell.products.title') }}</p>
    </div>
    <div class="flex items-center justify-between w-full py-8 px-12 rounded-xl bg-white mt-3">
        <div class="flex flex-col w-1/2 space-y-6">
            <input type="text" name="search" id="search" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block p-2.5 mr-2 ml-2" placeholder="Search">
            <div class="flex flex-col w-full space-y-6 overflow-y-scroll" style="height: 400px;">
                @foreach ($services as $product)
                    @include('control.sell.carditem', ['item' => $product, 'route' => 'sellservice.addToCart', 'remove' => 'sellproduct.removeFromCart'])
                @endforeach
            </div>
        </div>
        <div class="flex flex-col w-1/2 space-y-6">
            <div class="flex flex-col w-full space-y-6 overflow-y-scroll" style="height: 400px;">
                <table class="table-auto w-full" id="tblProductsSelected">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">{{ __('maintenance.sell.products.name') }}</th>
                            <th class="px-4 py-2">{{ __('maintenance.sell.products.quantity') }}</th>
                            <th class="px-4 py-2">{{ __('maintenance.sell.products.subtotal') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cartServices as $key => $item)
                            <tr>
                                <td class="border px-4 py-2">{{ $item['name'] }}</td>
                                <td class="border px-4 py-2">
                                    <div style="display: flex">
                                        <input onchange="handleChangeQuantity({{ $key }})" type="number" name="quantity" id="quantity-{{ $key }}" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block p-2.5 mr-2 ml-2" value="{{ $item['quantity'] }}">
                                        <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="removeFromCart({{ $key }});">
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
                <input type="text" name="totalCart" id="totalCart" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block p-2.5 mr-2 ml-2 w-full" value="{{ $cartServices->sum('total') }}" readonly>
            </div>
        </div>
    </div>
</div>
<script>
   
</script>
