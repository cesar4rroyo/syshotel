<div class="p-6 max-w-sm {{ $room->color }} rounded-lg border border-gray-200 shadow-md">
    <a href="#">
        <h5 class="mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">{{ $room['name'] }}</h5>
    </a>
    <hr>
    <p class="mb-3 text-gray-900 dark:text-white font-semibold mt-2">{{ $room['roomType']['name'] . ' -  S/. ' .  $room['roomType']['price']}}</p>
    {{-- <a href="#" class="inline-flex items-center text-blue-600 hover:underline">
        See our guideline
    </a> --}}
</div>