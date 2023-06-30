@if(count($lista) == 0)
@include('utils.noresult')
@else
<table id="example1" class="w-full text-base font-medium text-center text-gray-500">
	@include('utils.theader', ['cabecera' => $cabecera])
	<tbody class="border-b border-gray-300">
		<?php
		$contador = $inicio + 1;
		?>
		@foreach ($lista as $key => $value)
        <tr>
			<td class="py-3 px-4">{{ $value->datefrom . ' a ' . $value->dateto}}</td>
            <td class="py-3 px-4 border-l" style="color: {{ $value->colorstatus }}">{{ $value->status }}</td>
            <td class="py-3 px-4">{{ $value->client->full_name }}</td>
            <td class="py-3 px-4">{{ (int) $value->days }}</td>
            <td class="py-3 px-4">{{ $value->amount }}</td>
            <td class="py-3 px-4">{{ $value->notes }}</td>
            <td class="py-3 px-4">
				<div class="flex items-center space-x-4 text-lg">
					@if ($value->status == 'Pendiente')
						<button class="btn" onclick="cargarRuta('{{URL::route($ruta['checkin'], ['action'=>'LIST', 'businessId' => $value->id])}}', 'main-container');">
							<i style="color: green" class="fas fa-users"></i>
						</button>
						{{-- <button class="btn"  onclick="modal('{{URL::route($ruta['delete'], array($value->id, 'listagain'=>'SI', 'params' => $params2 ?? null))}}', 'Eliminar Reserva', this);">
							<i style="color: red" class="fas fa-trash"></i>
						</button> --}}
					@endif
				</div>
			</td>
		</tr>
		<?php
		$contador = $contador + 1;
		?>
		@endforeach
	</tbody>
    <caption style="caption-side:bottom">
        {!! $paginacion!!}
    </caption>
</table>
@endif
