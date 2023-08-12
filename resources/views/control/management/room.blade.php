<div class="p-6 max-w-sm {{ $room->color }} rounded-lg border border-gray-200 shadow-md">
    @include('control.management.badges', ['room' => $room])
    <a href="#">
        <h5 class="mb-2 text-xl font-semibold tracking-tight text-gray-900 dark:text-white mt-2">
            <i class=" fas fa-hotel"></i>
            {{ $room['name'] }}
        </h5>
    </a>
    <hr>
    <p class="mb-3 text-gray-900 dark:text-white font-semibold mt-2">{{ $room['roomType']['name'] . ' -  S/. ' .  $room['roomType']['price_hour']}}</p>
    <div class="flex justify-center">
        @include('control.management.actions', ['room' => $room, 'routes' => $routes])
    </div>
    @if ($room['status'] == 'Ocupado')
    <div class="flex justify-center mt-2">
        <span onclick="modal('{{URL::route($routes['sell'], ['type'=>'service', 'id' => $room['id']])}}', 'Agregar Adicionales', this);" style="font-size: 0.5rem; cursor:pointer" class="px-2 py-1 leading-tight text-yellow-700 bg-yellow-100 rounded-full dark:bg-yellow-700 dark:text-yellow-100 mr-1">
            Adicionales
        </span>
        <span onclick="modal('{{URL::route($routes['sell'], ['type'=>'product', 'id' => $room['id']])}}', 'Agregar Productos a Habitación', this);" style="font-size: 0.5rem; cursor:pointer" class="px-2 py-1 leading-tight text-yellow-700 bg-yellow-100 rounded-full dark:bg-yellow-700 dark:text-yellow-100 mr-1">
            Productos
        </span>
        <span onclick="modal('{{URL::route($routes['sell'], ['type'=>'stock', 'id' => $room['id']])}}', 'Mover Productos a Habitación', this);" style="font-size: 0.5rem; cursor:pointer" class="px-2 py-1 leading-tight text-yellow-700 bg-yellow-100 rounded-full dark:bg-yellow-700 dark:text-yellow-100">
            Mover Stocks
        </span>
    </div>
    @endif
</div>