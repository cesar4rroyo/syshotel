@if(count($lista) == 0)
@include('utils.noresult')
@else
<table id="example1" class="w-full text-base font-medium text-left text-gray-500">
	@include('utils.theader', ['cabecera' => $cabecera])
	<tbody class="border-b border-gray-300">
		<?php
		$contador = $inicio + 1;
		?>
		@foreach ($lista as $key => $value)
        <tr>
			<td class="py-3 px-4">{{ $value->created_at }}</td>
			<td class="py-3 px-4">{{ $value->number }}</td>
			<td class="py-3 px-4">{{ $value->concept->name }}</td>
			<td class="py-3 px-4">{{ $value->amount }}</td>
			<td class="py-3 px-4">{{ $value->client?->name }}</td>
			<td class="py-3 px-4">{{ $value->notes }}</td>
			<td class="py-3 px-4">
				<div class="flex items-center space-x-4 text-lg">
					@include('utils.basebuttons', ['ruta' => $ruta, 'id' => $value->id, 'titulo_modificar' => $titulo_modificar, 'titulo_eliminar' => $titulo_eliminar])
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
